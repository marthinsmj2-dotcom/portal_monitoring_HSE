<?php

use App\Http\Controllers\ApdController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\TemuanController;
use App\Http\Middleware\AdminSession;
use Illuminate\Support\Facades\Route;

// ----- Login dan logout -----
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ----- Semua halaman admin: hanya bisa dibuka saat sesi aktif -----
Route::middleware(AdminSession::class)->group(function () {

    Route::get('/', fn () => redirect()->route('dashboard'));

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // /kegiatan, /kegiatan/safety-patrol, /kegiatan/inspeksi-apar, /kegiatan/audit-5r-safety
    Route::get('/kegiatan/{jenis?}', [KegiatanController::class, 'index'])->name('kegiatan.index');

    // /temuan (akumulasi), /temuan/tindakan-perbaikan, /temuan/verifikasi
    Route::get('/temuan/{jenis?}', [TemuanController::class, 'index'])->name('temuan.index');

    // /apd (= Data APD), /apd/transaksi-stok, /apd/monitoring-stok
    Route::get('/apd/{halaman?}', [ApdController::class, 'index'])->name('apd.index');
});