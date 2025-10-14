<?php

namespace App\Http\Controllers;
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
use App\Http\Controllers\JadwalController as AdminJadwalController;
use App\Http\Controllers\XiJadwalController;

require __DIR__ . '/auth.php';

// Universal Time Override Routes (accessible by all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/time-override', [\App\Http\Controllers\TimeOverrideController::class, 'index'])->name('time-override.index');
    Route::post('/time-override/set-time', [\App\Http\Controllers\TimeOverrideController::class, 'setTime'])->name('time-override.set-time');
    Route::post('/time-override/clear-time', [\App\Http\Controllers\TimeOverrideController::class, 'clearTime'])->name('time-override.clear-time');
    Route::get('/time-override/status', [\App\Http\Controllers\TimeOverrideController::class, 'getStatus'])->name('time-override.status');
    Route::get('/time-override/scenarios', [\App\Http\Controllers\TimeOverrideController::class, 'getScenarios'])->name('time-override.scenarios');
});

// Root route
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->roles()->where('name', 'admin')->exists()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->roles()->where('name', 'teacher')->exists()) {
            return redirect()->route('guru.dashboard');
        } elseif ($user->roles()->where('name', 'student')->exists()) {
            return redirect()->route('murid.dashboard');
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
            return redirect()->route('murid.dashboard');
        }
    }
    return redirect('/login');
});

// Admin routes
Route::get('/admin/guru', [TeacherController::class, 'index'])->name('guru.index');

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Jadwal Pelajaran
    Route::get('/jadwal-pelajaran', function () {
        return view('admin.jadwal-pelajaran');
    })->name('admin.jadwal-pelajaran');
    Route::get('/admin/jadwal', [AdminJadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/admin/jadwal/import', [AdminJadwalController::class, 'import'])->name('jadwal.import');
    Route::delete('/admin/jadwal/bulk-delete', [AdminJadwalController::class, 'bulkDelete'])->name('jadwal.bulkDelete');
    Route::put('/admin/jadwal/{id}', [AdminJadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/admin/jadwal/{id}', [AdminJadwalController::class, 'destroy'])->name('jadwal.destroy');
    Route::delete('/admin/jadwal/delete-all', [AdminJadwalController::class, 'deleteAllJadwal'])->name('jadwal.delete-all');
    Route::post('/admin/subjects', [AdminJadwalController::class, 'storeSubject'])->name('subjects.store');
    Route::post('/admin/subjects/upload', [AdminJadwalController::class, 'uploadSubjects'])->name('subjects.upload');
    Route::get('/admin/subjects/by-class', [AdminJadwalController::class, 'getSubjectsByClass'])->name('subjects.by-class');
    Route::get('/admin/subjects/{id}', [AdminJadwalController::class, 'showSubject'])->name('subjects.show');
    Route::put('/admin/subjects/{id}', [AdminJadwalController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/admin/subjects/{id}', [AdminJadwalController::class, 'destroySubject'])->name('subjects.destroy');
    Route::delete('/admin/subjects/delete-all', [AdminJadwalController::class, 'deleteAllSubjects'])->name('subjects.delete-all');
    
    // Classes routes
    Route::get('/admin/classes', [AdminJadwalController::class, 'getClasses'])->name('classes.index');
    Route::get('/admin/classes/{id}', [AdminJadwalController::class, 'showClass'])->name('classes.show');
    Route::put('/admin/classes/{id}', [AdminJadwalController::class, 'updateClass'])->name('classes.update');
    Route::delete('/admin/classes/{id}', [AdminJadwalController::class, 'destroyClass'])->name('classes.destroy');
    Route::post('/admin/classes/import', [AdminJadwalController::class, 'importClasses'])->name('classes.import');
    Route::delete('/admin/classes/delete-all', [AdminJadwalController::class, 'deleteAllClasses'])->name('classes.delete-all');

    // Jadwal Pelajaran Kelas XI
    Route::get('/jadwal-pelajaran-xi', function () {
        return view('admin.jadwal-pelajaran-xi');
    })->name('admin.jadwal-pelajaran-xi');
    Route::get('/admin/jadwal-xi', [XiJadwalController::class, 'index'])->name('jadwal-xi.index');
    Route::post('/admin/jadwal-xi/import', [XiJadwalController::class, 'import'])->name('jadwal-xi.import');
    Route::get('/admin/jadwal-xi/filter-options', [XiJadwalController::class, 'getFilterOptions'])->name('jadwal-xi.filter-options');
    Route::get('/admin/jadwal-xi/statistics', [XiJadwalController::class, 'getStatistics'])->name('jadwal-xi.statistics');
    Route::delete('/admin/jadwal-xi/bulk-delete', [XiJadwalController::class, 'bulkDelete'])->name('jadwal-xi.bulkDelete');
    Route::delete('/admin/jadwal-xi/{id}', [XiJadwalController::class, 'destroy'])->name('jadwal-xi.destroy');
    Route::delete('/admin/jadwal-xi/delete-all', [XiJadwalController::class, 'deleteAllJadwalXi'])->name('jadwal-xi.delete-all');

    // User management
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/table', [UserController::class, 'table'])->name('users.table');
    Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/admin/users/import', [UserController::class, 'import'])->name('users.import');
    Route::put('/admin/user/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin/user/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/admin/users/search', [UserController::class, 'search'])->name('users.search');
    Route::post('/admin/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::post('/admin/users/bulk-status-active', [UserController::class, 'bulkStatusActive'])->name('users.bulk-status-active');
    Route::post('/admin/users/bulk-status-suspended', [UserController::class, 'bulkStatusSuspended'])->name('users.bulk-status-suspended');
    Route::get('/manage-user', function () {
        $users = \App\Models\User::all();
        return view('admin.manage-user', compact('users'));
    })->name('users.manage');

    // Guru management
    Route::prefix('admin/guru')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('guru.index');
        Route::post('/', [TeacherController::class, 'store'])->name('guru.store');
        Route::post('/import', [TeacherController::class, 'import'])->name('guru.import');
        Route::put('{id}', [TeacherController::class, 'update'])->name('guru.update');
        Route::delete('{id}', [TeacherController::class, 'destroy'])->name('guru.destroy');
        Route::post('/bulk-delete', [TeacherController::class, 'bulkDelete'])->name('guru.bulk-delete');
    });



    // Murid management
    Route::prefix('admin/murid')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('murid.index');
        Route::post('/', [StudentController::class, 'store'])->name('murid.store');
        Route::post('/import', [StudentController::class, 'import'])->name('murid.import');
        Route::put('{id}', [StudentController::class, 'update'])->name('murid.update');
        Route::delete('{id}', [StudentController::class, 'destroy'])->name('murid.destroy');
        Route::post('/bulk-delete', [StudentController::class, 'bulkDelete'])->name('murid.bulk-delete');
    });

});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/guru/dashboard', [DashboardController::class, 'index'])->name('guru.dashboard');
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
        return view('murid.dashboard');
    })->name('murid.dashboard');
    Route::get('/student/jadwal', function () {
        return view('murid.jadwal-pelajaran');
    })->name('murid.jadwal');
});

// Catch-all routes
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
