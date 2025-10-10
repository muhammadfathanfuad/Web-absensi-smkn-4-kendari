<?php

use App\Http\Controllers\RoutingController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;

// Import controller yang baru kita buat
use App\Http\Controllers\Guru\DashboardController;
use App\Http\Controllers\Guru\AbsensiController;
use App\Http\Controllers\Guru\PengumumanController;
use App\Http\Controllers\Guru\JadwalController;

require __DIR__ . '/auth.php';

// Root route
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->roles()->where('name', 'admin')->exists()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->roles()->where('name', 'teacher')->exists()) {
            return redirect()->route('guru.dashboard');
        } elseif ($user->roles()->where('name', 'student')->exists()) {
            return redirect()->route('student.dashboard');
        }
    }
    return view('auth.signin');
});

// Public routes
Route::get('/auth/signin', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->roles()->where('name', 'admin')->exists()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->roles()->where('name', 'teacher')->exists()) {
            return redirect()->route('guru.dashboard');
        } elseif ($user->roles()->where('name', 'student')->exists()) {
            return redirect()->route('student.dashboard');
        }
    }
    return redirect('/login');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Jadwal Pelajaran
    Route::get('/jadwal-pelajaran', function () {
        return view('admin.jadwal-pelajaran');
    })->name('admin.jadwal-pelajaran');

    // User management
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/table', [UserController::class, 'table'])->name('users.table');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/admin/user/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin/user/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/admin/users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/manage-user', function () {
        $users = \App\Models\User::all();
        return view('admin.manage-user', compact('users'));
    })->name('users.manage');

    // Guru management
    Route::prefix('admin/guru')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('guru.index');
        Route::post('/', [TeacherController::class, 'store'])->name('guru.store');
        Route::put('{id}', [TeacherController::class, 'update'])->name('guru.update');
        Route::delete('{id}', [TeacherController::class, 'destroy'])->name('guru.destroy');
    });

    // Murid management
    Route::prefix('admin/murid')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('murid.index');
        Route::post('/', [StudentController::class, 'store'])->name('murid.store');
        Route::put('{id}', [StudentController::class, 'update'])->name('murid.update');
        Route::delete('{id}', [StudentController::class, 'destroy'])->name('murid.destroy');
    });

    // Classes
    Route::get('/admin/classes', function () {
        return \App\Models\Classroom::all(['id', 'name']);
    })->name('classes.index');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/guru/dashboard', [DashboardController::class, 'index'])->name('guru.dashboard');
    Route::get('/scan-qr/generate', [AbsensiController::class, 'generateQrCode'])->name('guru.absensi.generate-qr');
    Route::get('/scan-qr', [AbsensiController::class, 'showScanner'])->name('guru.absensi.scan');
    Route::post('/scan-qr/process', [AbsensiController::class, 'processScan'])->name('guru.absensi.process');
    Route::get('/scan-qr/results/{timetable_id}', [AbsensiController::class, 'getScanResults'])->name('guru.absensi.results');
    Route::get('/status-absensi', [AbsensiController::class, 'showStatus'])->name('guru.status-absensi');
    Route::get('/jadwal-mengajar', [JadwalController::class, 'index'])->name('guru.jadwal-mengajar');
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('guru.pengumuman');
});

// Student routes
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});

// Catch-all routes
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
