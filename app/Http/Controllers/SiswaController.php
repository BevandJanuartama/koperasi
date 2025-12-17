<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    /**
     * Menampilkan halaman utama siswa berisi nama, saldo, dan riwayat transaksi
     */
    public function index()
    {
        $user = Auth::user();
        $today = now()->startOfDay();

        // 1. Ambil transaksi pembayaran HARI INI
        $purchases = DB::table('transaksis')
            ->select(
                'id',
                'total AS amount',
                DB::raw('"pembayaran" AS type'),
                'created_at'
            )
            ->where('user_id', $user->id)
            ->whereDate('created_at', $today);

        // 2. Ambil topup HARI INI
        $topups = DB::table('topups')
            ->select(
                'id',
                'total AS amount',
                DB::raw('"topup" AS type'),
                'created_at'
            )
            ->where('user_id', $user->id)
            ->whereDate('created_at', $today);

        // 3. Gabungkan hasilnya (Hanya untuk Hari Ini)
        $historyToday = DB::query()
            ->fromSub($purchases->union($topups), 'histories')
            ->orderBy('created_at', 'desc')
            ->get();

        $currentBalance = $user->saldo ?? 0;

        // 4. Hitung total masuk & keluar HARI INI saja
        $totalMasuk = $historyToday->where('type', 'topup')->sum('amount');
        $totalKeluar = $historyToday->where('type', 'pembayaran')->sum('amount');

        return view('siswa.index', [ // Pastikan nama view sesuai
            'user' => $user,
            'currentBalance' => $currentBalance,
            'transaksis' => $historyToday,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
        ]);
    }


    /**
     * Menampilkan detail transaksi tertentu (hanya pembayaran/transaksis)
     */
    public function detailTransaksi($id)
    {
        $userId = Auth::id();

        try {
            $transaksi = Transaksi::where('id', $id)
                ->where('user_id', $userId)
                ->with(['details' => function ($query) {
                    $query->with('barang');
                }])
                ->firstOrFail();

            return view('siswa.detail-transaksi', compact('transaksi'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('siswa.index')->with('error', 'Detail transaksi tidak ditemukan atau Anda tidak memiliki akses.');
        }
    }

    public function profile()
    {
        $user = Auth::user();

        return view('siswa.profile', compact('user'));
    }

    public function riwayatSeluruhnya(Request $request)
    {
        $user = Auth::user();
        
        // Ambil input filter jika ada
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query Pembayaran (Pengeluaran)
        $purchases = DB::table('transaksis')
            ->select('id', 'total AS amount', DB::raw('"pembayaran" AS type'), 'created_at')
            ->where('user_id', $user->id);

        // Query Topup (Pemasukan)
        $topups = DB::table('topups')
            ->select('id', 'total AS amount', DB::raw('"topup" AS type'), 'created_at')
            ->where('user_id', $user->id);

        // Terapkan filter tanggal jika diisi
        if ($startDate && $endDate) {
            $purchases->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
            $topups->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }

        // Ambil data untuk kalkulasi total (tanpa pagination)
        $allDataForTotal = DB::query()
            ->fromSub($purchases->union($topups), 'totals')
            ->get();

        $totalMasuk = $allDataForTotal->where('type', 'topup')->sum('amount');
        $totalKeluar = $allDataForTotal->where('type', 'pembayaran')->sum('amount');

        // Ambil data dengan pagination untuk riwayat
        $allHistory = DB::query()
            ->fromSub($purchases->union($topups), 'histories')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString(); // Agar filter tidak hilang saat pindah halaman

        return view('siswa.riwayat', compact('allHistory', 'totalMasuk', 'totalKeluar', 'startDate', 'endDate'));
    }
}
