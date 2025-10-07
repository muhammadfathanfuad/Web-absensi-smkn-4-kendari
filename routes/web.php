<?php

use App\Http\Controllers\RoutingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Import controller yang baru kita buat
use App\Http\Controllers\Guru\DashboardController;
use App\Http\Controllers\Guru\AbsensiController;
use App\Http\Controllers\Guru\PengumumanController;

require __DIR__ . '/auth.php';

// Route '/dashboard' sekarang memanggil DashboardController
Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');
// route guru
Route::get('/dashboard', function () {
    return view('guru.dashboard');
})->name('home');

Route::get('/scan-qr', function () {
    return view('guru.scan-qr');
})->name('scan-qr');

Route::get('/jadwal-mengajar', function () {
    return view('guru.jadwal-mengajar');
})->name('jadwal-mengajar');

// Route '/status-absensi' sekarang memanggil AbsensiController
Route::get('/status-absensi', [AbsensiController::class, 'index'])->name('status-absensi');
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('Pengumuman');

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
