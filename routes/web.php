<?php

use App\Http\Controllers\RoutingController;
use Illuminate\Support\Facades\Route;

// Import controller yang baru kita buat
use App\Http\Controllers\Guru\DashboardController;
use App\Http\Controllers\Guru\AbsensiController;
use App\Http\Controllers\Guru\PengumumanController;

require __DIR__ . '/auth.php';

// Route '/dashboard' sekarang memanggil DashboardController
Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');

Route::get('/scan-qr', function () {
    return view('guru.scan-qr');
})->name('scan-qr');

Route::get('/jadwal-mengajar', function () {
    return view('guru.jadwal-mengajar');
})->name('jadwal-mengajar');

// Route '/status-absensi' sekarang memanggil AbsensiController
Route::get('/status-absensi', [AbsensiController::class, 'index'])->name('status-absensi');
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('Pengumuman');


Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
