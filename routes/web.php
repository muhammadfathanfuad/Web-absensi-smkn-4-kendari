<?php

use App\Http\Controllers\RoutingController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/status-absensi', function () {
    return view('guru.status-absensi');
})->name('status-absensi');

// route admin
Route::get('/dashboard-admin', function () {
    return view('admin.dashboard');
})->name('home');
Route::get('/jadwal-pelajaran', function () {
    return view('admin.jadwal-pelajaran');
})->name('home');

// routes/web.php
Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
Route::get('/admin/users/table', [UserController::class, 'table'])->name('users.table');

// Menambahkan route untuk menambah user
Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');

// Menambahkan route untuk update user
Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('users.update');

// Menambahkan route untuk menghapus user
Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// Menambahkan route untuk search user
Route::get('/admin/users/search', [UserController::class, 'search'])->name('users.search');

// Route untuk halaman manage-user
Route::get('/manage-user', function () {
    $users = \App\Models\User::all();
    return view('admin.manage-user', compact('users'));
})->name('users.manage');

// Route untuk guru
Route::prefix('admin/guru')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('guru.index');  // Menampilkan data guru
    Route::post('/', [TeacherController::class, 'store'])->name('guru.store');  // Menambahkan guru
    Route::put('{id}', [TeacherController::class, 'update'])->name('guru.update');  // Mengupdate guru
    Route::delete('{id}', [TeacherController::class, 'destroy'])->name('guru.destroy');  // Menghapus guru
});

// Route untuk murid
Route::prefix('admin/murid')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('murid.index');  // Menampilkan data murid
    Route::post('/', [StudentController::class, 'store'])->name('murid.store');  // Menambahkan murid
    Route::put('{id}', [StudentController::class, 'update'])->name('murid.update');  // Mengupdate murid
    Route::delete('{id}', [StudentController::class, 'destroy'])->name('murid.destroy');  // Menghapus murid
});

// Route untuk classes
Route::get('/admin/classes', function () {
    return \App\Models\Classroom::all(['id', 'name']);
})->name('classes.index');

// belom selesai
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
