<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaranaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Pendaftaran mandiri — SELALU membuat akun dengan role "siswa".
    // Akun admin tidak bisa dibuat lewat sini; hanya lewat menu Data Pengguna (khusus admin).
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Fitur Transaksi Utama - Siswa & Admin (lihat sesuai kepemilikan di controller)
    Route::resource('pengaduan', PengaduanController::class)->only(['index', 'create', 'store', 'show']);
    Route::put('/pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus'])->name('pengaduan.status');

    // Admin only: Master Data & Laporan
    Route::middleware('role:admin')->group(function () {
        Route::resource('kategori', KategoriController::class)->except(['create', 'edit', 'show']);
        Route::resource('sarana', SaranaController::class)->except(['create', 'edit', 'show']);
        Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetakSemua'])->name('laporan.cetak');
    });

    Route::get('/pengaduan/{pengaduan}/cetak', [LaporanController::class, 'cetakSatu'])->name('pengaduan.cetak');
});
