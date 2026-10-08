<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\TemuanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// /kegiatan, /kegiatan/safety-patrol, /kegiatan/inspeksi-apar, /kegiatan/audit-5r-safety
Route::get('/kegiatan/{jenis?}', [KegiatanController::class, 'index'])->name('kegiatan.index');

// /temuan (akumulasi), /temuan/tindakan-perbaikan, /temuan/verifikasi
Route::get('/temuan/{jenis?}', [TemuanController::class, 'index'])->name('temuan.index');