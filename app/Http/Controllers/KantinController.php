<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang; // Pastikan model Barang sudah ada
use App\Models\User;   // Pastikan model User sudah ada
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class KantinController extends Controller
{
    public function index()
    {
        return view('kantin.index');
    }
    /**
     * Halaman utama kasir: menampilkan keranjang dari session.
     */
    public function kasir(Request $request)
    {
        // 1. Ambil data keranjang dari session
        $keranjang = $request->session()->get('keranjang', []);
        $total = array_sum(array_column($keranjang, 'subtotal'));

        return view('kantin.kasir', [
            'keranjang' => $keranjang,
            'total' => $total,
        ]);
    }

    /**
     * Menambahkan atau mengupdate barang ke keranjang (Session).
     */
    public function scan(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string',
        ]);

        $kodeBarang = $request->kode_barang;
        $keranjang = $request->session()->get('keranjang', []);
        $barang = Barang::where('kode', $kodeBarang)->first();

        // 1. Cek apakah barang ada di database
        if (!$barang) {
            return response()->json(['success' => false, 'message' => 'Kode barang tidak ditemukan.']);
        }

        // 2. ✨ LOGIKA CEK STOK KOSONG ✨
        if ($barang->stok <= 0) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal! Stok ' . $barang->nama_barang . ' habis!'
            ], 422); // Gunakan status 422 agar ditangkap sebagai error oleh AJAX
        }

        $barangId = $barang->id;

        // 3. Cek apakah barang sudah ada di keranjang
        if (isset($keranjang[$barangId])) {
            // ✨ VALIDASI: Apakah penambahan melebihi stok yang ada? ✨
            if (($keranjang[$barangId]['qty'] + 1) > $barang->stok) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal! Jumlah di keranjang sudah mencapai batas maksimal stok (' . $barang->stok . ').'
                ], 422);
            }

            // Jika aman, tambahkan kuantitas
            $keranjang[$barangId]['qty']++;
            $keranjang[$barangId]['subtotal'] = $keranjang[$barangId]['qty'] * $barang->harga;
        } else {
            // Jika belum ada, tambahkan item baru (sudah pasti stok minimal 1 karena lolos cek di atas)
            $keranjang[$barangId] = [
                'id' => $barangId,
                'nama_barang' => $barang->nama_barang,
                'harga_satuan' => $barang->harga, 
                'qty' => 1,
                'subtotal' => $barang->harga,
            ];
        }

        $request->session()->put('keranjang', $keranjang);
        $total = array_sum(array_column($keranjang, 'subtotal'));

        return response()->json([
            'success' => true, 
            'message' => $barang->nama_barang . ' ditambahkan.',
            'item' => $keranjang[$barangId],
            'total' => number_format($total, 0, ',', '.'), 
            'keranjang_count' => count($keranjang),
        ]);
    }

    /**
     * Update quantity of an item in the keranjang (Session) via AJAX.
     */
    public function updateQty(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:barangs,id',
            'qty' => 'required|integer|min:0',
        ]);

        $itemId = $request->id;
        $newQty = $request->qty;
        $keranjang = $request->session()->get('keranjang', []);
        
        if (!isset($keranjang[$itemId])) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan.'], 404);
        }

        // --- LOGIKA CEK STOK ---
        if ($newQty > 0) {
            $barang = \App\Models\Barang::find($itemId);
            if ($newQty > $barang->stok) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Stok tidak mencukupi. Stok ' . $barang->nama_barang . ' tersisa: ' . $barang->stok
                ], 422); // Error 422: Unprocessable Entity
            }
        }
        // -----------------------

        $namaBarang = $keranjang[$itemId]['nama_barang'];

        if ($newQty <= 0) {
            unset($keranjang[$itemId]);
            $action = 'removed';
        } else {
            $keranjang[$itemId]['qty'] = $newQty;
            $keranjang[$itemId]['subtotal'] = $newQty * $keranjang[$itemId]['harga_satuan'];
            $action = 'updated';
        }
        
        $request->session()->put('keranjang', $keranjang);
        $total = array_sum(array_column($keranjang, 'subtotal'));

        return response()->json([
            'success' => true,
            'message' => 'Kuantitas ' . $namaBarang . ' diperbarui.',
            'action' => $action,
            'itemId' => $itemId,
            'newQty' => $newQty,
            'subtotal' => isset($keranjang[$itemId]) ? number_format($keranjang[$itemId]['subtotal'], 0, ',', '.') : null,
            'total' => number_format($total, 0, ',', '.'),
        ]);
    }

    /**
     * Menghapus item dari keranjang (Session). (Digunakan oleh AJAX)
     */
    public function hapus(Request $request, $id)
    {
        $keranjang = $request->session()->get('keranjang', []);

        if (isset($keranjang[$id])) {
            $namaBarang = $keranjang[$id]['nama_barang'];
            unset($keranjang[$id]);
            $request->session()->put('keranjang', $keranjang);
            
            $total = array_sum(array_column($keranjang, 'subtotal'));

            return response()->json([
                'success' => true, 
                'message' => $namaBarang . ' dihapus dari keranjang.', 
                'itemId' => $id,
                'total' => number_format($total, 0, ',', '.'),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Item tidak ditemukan di keranjang.'], 404);
    }
    
    /**
     * Memproses pembayaran, menyimpan transaksi, dan mengurangi saldo user.
     */
    public function bayar(Request $request)
    {
        $keranjang = $request->session()->get('keranjang', []);

        if (empty($keranjang)) {
            return response()->json(['success' => false, 'message' => 'Keranjang belanja kosong!'], 400);
        }

        $request->validate(['user_kartu' => 'required|string']);

        $user = User::where('card_id', $request->user_kartu)
                    ->orWhere('username', $request->user_kartu)
                    ->first();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Kartu pelajar tidak dikenali.'], 404);
        }

        $totalBelanja = array_sum(array_column($keranjang, 'subtotal'));
        
        if ($user->saldo < $totalBelanja) {
            return response()->json(['success' => false, 'message' => 'Saldo tidak cukup.'], 402);
        }

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'total' => $totalBelanja,
            ]);

            foreach ($keranjang as $item) {
                // 1. Ambil data barang & Lock untuk keamanan stok (Race Condition)
                $barang = \App\Models\Barang::lockForUpdate()->find($item['id']);

                // 2. Validasi stok sekali lagi (jaga-jaga stok habis saat user sedang buka keranjang)
                if ($barang->stok < $item['qty']) {
                    throw new \Exception("Stok " . $barang->nama_barang . " mendadak habis atau tidak cukup.");
                }

                // 3. Simpan detail transaksi
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $item['id'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga_satuan'], 
                    'subtotal' => $item['subtotal'],
                ]);

                // 4. ✨ LOGIKA KURANGI STOK ✨
                $barang->stok -= $item['qty'];
                $barang->save();
            }

            // C. Kurangi Saldo User
            $user->saldo -= $totalBelanja;
            $user->save();

            DB::commit();
            $request->session()->forget(['keranjang']);

            return response()->json([
                'success' => true, 
                'message' => 'Pembayaran berhasil!',
                'transaksi_id' => $transaksi->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Halaman riwayat transaksi
     */
    public function transaksi(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth();

        $transaksis = Transaksi::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTransaksi = $transaksis->count();
        $totalUang = $transaksis->sum('total');

        return view(
            'kantin.transaksi',
            compact('transaksis', 'startDate', 'endDate', 'totalTransaksi', 'totalUang')
        );
    }

    /**
     * Detail transaksi tertentu
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['user', 'details' => function($query) {
            // Eager loading barang dari detail transaksi
            $query->with('barang'); 
        }])->findOrFail($id);
        
        return view('kantin.detail-transaksi', compact('transaksi'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $barangs = \App\Models\Barang::where('nama_barang', 'LIKE', "%{$query}%")
                    ->orWhere('kode', 'LIKE', "%{$query}%")
                    ->limit(5)
                    ->get(['id', 'nama_barang', 'kode', 'harga', 'stok']); // Tambahkan 'id'

        return response()->json($barangs);
    }
}