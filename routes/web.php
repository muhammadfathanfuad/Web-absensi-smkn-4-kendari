<?php

use App\Http\Controllers\RoutingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Import controller yang baru kita buat
use App\Http\Controllers\Guru\DashboardController;
use App\Http\Controllers\Guru\AbsensiController;
use App\Http\Controllers\Guru\PengumumanController;
use App\Http\Controllers\Guru\JadwalController;

require __DIR__ . '/auth.php';

// --- SEMUA ROUTE GURU SEKARANG DAPAT DIAKSES PUBLIK ---
// Route '/dashboard' sekarang memanggil DashboardController
Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');
// route guru
Route::get('/dashboard', function () {
    return view('guru.dashboard');
})->name('home');

// Dashboard (URL: /dashboard)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('guru.dashboard');

// Halaman Scan QR (URL: /scan-qr)
Route::get('/scan-qr', [AbsensiController::class, 'showScanner'])->name('guru.absensi.scan');

// API untuk memproses scan (dipanggil oleh JavaScript)
Route::post('/scan-qr/process', [AbsensiController::class, 'processScan'])->name('guru.absensi.process');

// API untuk checkout otomatis (dipanggil oleh JavaScript)

// API untuk filter hasil pindaian (dipanggil oleh JavaScript)
Route::get('/scan-qr/results/{timetable_id}', [AbsensiController::class, 'getScanResults'])->name('guru.absensi.results');

// Halaman Status Absensi (URL: /status-absensi)
Route::get('/status-absensi', [AbsensiController::class, 'showStatus'])->name('guru.status-absensi');

// Halaman Jadwal Mengajar (URL: /jadwal-mengajar)
Route::get('/jadwal-mengajar', [JadwalController::class, 'index'])->name('guru.jadwal-mengajar');

// Halaman Pengumuman (URL: /pengumuman)
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('guru.pengumuman');


// Arahkan halaman utama langsung ke dashboard guru
Route::get('/', function () {
    return redirect()->route('guru.dashboard');
});
// route admin
Route::get('/dashboard-admin', function () {
    return view('admin.dashboard');
})->name('home');

// routes/web.php
Route::get('/admin/users/table', [UserController::class, 'table'])->name('users.table');

// Menambahkan route untuk menambah user
Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');

// Menambahkan route untuk update user
Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('users.update');

// Menambahkan route untuk menghapus user
Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// Route untuk halaman manage-user
Route::get('/manage-user', function () {
    return view('admin.user.manage-user');
})->name('users.manage');

// belom selesai
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
