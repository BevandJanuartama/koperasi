<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KantinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController; // Import SiswaController

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard umum
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Semua route di bawah hanya untuk user yang sudah login
Route::middleware(['auth'])->group(function () {

    // ============================
    //          ADMIN
    // ============================
    Route::prefix('admin')->group(function () {
        Route::get('index', [AdminController::class, 'index'])->name('admin.index');
        Route::get('topup', [AdminController::class, 'topup'])->name('admin.topup');
        Route::get('data-topup', [AdminController::class, 'dataTopup'])->name('admin.data-topup');
        
        // --- CRUD Barang ---
        Route::get('barang', [AdminController::class, 'barang'])->name('admin.barang');
        Route::post('barang/store', [AdminController::class, 'storeBarang'])->name('admin.barang.store');
        Route::put('barang/update/{id}', [AdminController::class, 'updateBarang'])->name('admin.barang.update');
        Route::delete('barang/destroy/{id}', [AdminController::class, 'destroyBarang'])->name('admin.barang.destroy');

        // --- TOPUP (AJAX) ---
        Route::get('topup/user/{card_id}', [AdminController::class, 'getUserByCard']);
        Route::post('topup/store', [AdminController::class, 'storeTopup'])->name('admin.topup.store');

        // --- Registrasi Kartu ---
        Route::get('registrasi', [AdminController::class, 'registerCard'])->name('admin.registerCard');
        Route::post('registrasi-kartu/store', [AdminController::class, 'storeCard'])->name('admin.storeCard');

        Route::post('registrasi/import', [AdminController::class, 'importExcel'])->name('admin.importExcel');
        Route::post('barang/import', [AdminController::class, 'importBarang'])->name('admin.barang.import');
    });

    // ============================
    //         KASIR KANTIN
    // ============================
    Route::prefix('kantin')->group(function () {

        Route::get('index', [KantinController::class, 'index'])->name('kantin.index');
        // Halaman utama kasir (scan barang & bayar)
        Route::get('kasir', [KantinController::class, 'kasir'])->name('kantin.kasir');

        // Scan barang (tambah ke keranjang)
        Route::post('scan', [KantinController::class, 'scan'])->name('kantin.scan');
        
        // Update quantity barang di keranjang via AJAX
        Route::post('qty', [KantinController::class, 'updateQty'])->name('kantin.qty.update'); 

        // Hapus barang dari keranjang
        Route::delete('hapus/{id}', [KantinController::class, 'hapus'])->name('kantin.hapus');

        // Scan kartu untuk bayar
        Route::post('bayar', [KantinController::class, 'bayar'])->name('kantin.bayar');

        // Riwayat transaksi
        Route::get('transaksi', [KantinController::class, 'transaksi'])->name('kantin.transaksi');

        // Detail transaksi
        Route::get('detail/{id}', [KantinController::class, 'show'])->name('kantin.detail'); 

        Route::get('/kantin/search', [KantinController::class, 'search'])->name('kantin.search');
    });

    // ============================
    //          USER / SISWA
    // ============================
    Route::prefix('siswa')->middleware('auth')->group(function () {

        // Halaman utama siswa (menampilkan nama, saldo, dan riwayat transaksi)
        Route::get('index', [SiswaController::class, 'index'])->name('siswa.index');

        // Detail transaksi tertentu
        Route::get('transaksi/{id}', [SiswaController::class, 'detailTransaksi'])->name('siswa.transaksi.show');

        Route::get('profile', [SiswaController::class, 'profile'])->name('siswa.profile');

        Route::get('/riwayat', [SiswaController::class, 'riwayatSeluruhnya'])->name('siswa.riwayat.seluruhnya');

        Route::post('/password-check', [ProfileController::class, 'checkCurrentPassword'])->name('password.check');
        // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });
});

// Auth routes
require __DIR__.'/auth.php';
