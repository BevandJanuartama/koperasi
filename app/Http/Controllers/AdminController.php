<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Halaman utama dashboard admin
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Halaman form topup
     */
    public function topup()
    {
        return view('admin.topup');
    }

    /**
     * Data semua barang (dengan pagination)
     */
    public function barang()
    {
        // Menggunakan get() karena DataTables akan mengelola paginasi di sisi klien
        $barang = DB::table('barangs')->orderByDesc('created_at')->get();
        return view('admin.barang', compact('barang'));
    }

    /**
     * Data semua riwayat topup (dengan join ke user)
     */
    public function dataTopup(Request $request)
    {
        // 1. Logika penentuan rentang tanggal (Mengikuti variabel di view: min & max)
        $startDate = $request->min
            ? Carbon::parse($request->min)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->max
            ? Carbon::parse($request->max)->endOfDay()
            : Carbon::now()->endOfMonth();

        // 2. Query data topup dengan filter tanggal
        $query = DB::table('topups')
            ->join('users', 'topups.user_id', '=', 'users.id')
            ->select(
                'topups.*',
                'users.nama as user_name',
                'users.username',
                'users.kelas',
                'users.jurusan',
                'users.saldo',
                'users.card_id'
            )
            ->whereBetween('topups.created_at', [$startDate, $endDate]);

        // 3. Hitung total uang dan jumlah transaksi (sebelum pagination)
        $totalTopupCount = $query->count();
        $totalUangTopup = $query->sum('topups.total'); // Menggunakan kolom 'total' sesuai storeTopup Anda

        // 4. Eksekusi data (Urutan terbaru)
        $topup = $query->orderByDesc('topups.created_at')->paginate(10);

        // 5. Kirim data ke view
        return view('admin.data-topup', compact(
            'topup', 
            'startDate', 
            'endDate', 
            'totalTopupCount', 
            'totalUangTopup'
        ));
    }

    /**
     * Ambil data user berdasarkan card_id (untuk AJAX)
     */
    public function getUserByCard($card_id)
    {
        $user = DB::table('users')->where('card_id', $card_id)->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }

        return response()->json(['success' => false, 'message' => 'User tidak ditemukan!']);
    }

    /**
     * Simpan data topup (update saldo + catat riwayat)
     */
    public function storeTopup(Request $request)
    {
        $request->validate([
            'card_id' => 'required',
            'saldo' => 'required|numeric|min:1'
        ]);

        $user = DB::table('users')->where('card_id', $request->card_id)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan!'
            ]);
        }

        // Tambahkan saldo user
        $newSaldo = $user->saldo + $request->saldo;

        DB::table('users')->where('card_id', $request->card_id)
            ->update([
                'saldo' => $newSaldo,
                'updated_at' => now(),
            ]);

        // Simpan riwayat topup
        DB::table('topups')->insert([
            'user_id' => $user->id,
            'total' => $request->saldo,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Topup berhasil!',
            'user' => [
                'username' => $user->username ?? $user->name,
                'nama' => $user->nama ?? $user->name,
                'kelas' => $user->kelas ?? '-',
                'jurusan' => $user->jurusan ?? '-',
                'saldo' => $newSaldo,
            ]
        ]);
    }

    /**
     * Simpan barang baru
     */
    public function storeBarang(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'kode' => 'required|string|unique:barangs,kode',
        ]);

        DB::table('barangs')->insert([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kode' => $request->kode,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Update barang
     */
    public function updateBarang(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'kode' => [
                'required',
                'string',
                Rule::unique('barangs', 'kode')->ignore($id),
            ],
        ]);

        DB::table('barangs')
            ->where('id', $id)
            ->update([
                'nama_barang' => $request->nama_barang,
                'harga' => $request->harga,
                'stok' => $request->stok,
                'kode' => $request->kode,
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Hapus barang
     */
    public function destroyBarang($id)
    {
        DB::table('barangs')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Halaman registrasi kartu
     */
    public function registerCard()
    {
        return view('admin.register-card');
    }

    /**
     * Simpan user baru (registrasi kartu)
     */
    public function storeCard(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'card_id' => 'required|string|unique:users,card_id',
            'password' => 'required|string|min:6',
            'level' => 'required|string|in:user,admin',
        ]);

        DB::table('users')->insert([
            'username' => $request->username,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'saldo' => 0,
            'level' => $request->level,
            'password' => Hash::make($request->password),
            'card_id' => $request->card_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Kartu berhasil diregistrasi!');
    }
}
