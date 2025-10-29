<?php

namespace App\Http\Controllers;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\UserController;
use Exception;

// Import controller yang baru kita buat
use App\Http\Controllers\Guru\DashboardController;
use App\Http\Controllers\Guru\AbsensiController;
use App\Http\Controllers\Guru\PengumumanController;
use App\Http\Controllers\Guru\JadwalController;
use App\Http\Controllers\JadwalController as AdminJadwalController;
use App\Http\Controllers\XiJadwalController;
use App\Http\Controllers\Murid\DashboardMuridController;
use App\Http\Controllers\Murid\JadwalPelajaranController;
use App\Http\Controllers\Murid\ScanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

require __DIR__ . '/auth.php';

// Universal Time Override Routes (accessible by all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/time-override', [\App\Http\Controllers\TimeOverrideController::class, 'index'])->name('time-override.index');
    Route::post('/time-override/set-time', [\App\Http\Controllers\TimeOverrideController::class, 'setTime'])->name('time-override.set-time');
    Route::post('/time-override/clear-time', [\App\Http\Controllers\TimeOverrideController::class, 'clearTime'])->name('time-override.clear-time');
    Route::get('/time-override/status', [\App\Http\Controllers\TimeOverrideController::class, 'getStatus'])->name('time-override.status');
    Route::get('/time-override/scenarios', [\App\Http\Controllers\TimeOverrideController::class, 'getScenarios'])->name('time-override.scenarios');
    Route::get('/time-override/js-data', [\App\Http\Controllers\TimeOverrideController::class, 'getJSData'])->name('time-override.js-data');
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
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/dashboard/teacher-pagination', [AdminDashboardController::class, 'getTeacherPagination'])->name('admin.dashboard.teacher-pagination');
        Route::get('/admin/dashboard/class-pagination', [AdminDashboardController::class, 'getClassPagination'])->name('admin.dashboard.class-pagination');
        Route::get('/admin/dashboard/activities-pagination', [AdminDashboardController::class, 'getActivitiesPagination'])->name('admin.dashboard.activities-pagination');
    

    // Jadwal Pelajaran
    Route::get('/jadwal-pelajaran', [AdminJadwalController::class, 'jadwalPelajaran'])->name('admin.jadwal-pelajaran');
    Route::get('/admin/jadwal', [AdminJadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/admin/jadwal/import', [AdminJadwalController::class, 'import'])->name('jadwal.import');
    Route::delete('/admin/jadwal/bulk-delete', [AdminJadwalController::class, 'bulkDelete'])->name('jadwal.bulkDelete');
    Route::delete('/admin/jadwal/delete-all', [AdminJadwalController::class, 'deleteAllJadwal'])->name('jadwal.delete-all');
    Route::get('/admin/jadwal/{id}', [AdminJadwalController::class, 'editJadwal'])->name('jadwal.edit');
    Route::put('/admin/jadwal/{id}', [AdminJadwalController::class, 'updateJadwal'])->name('jadwal.update');
    Route::delete('/admin/jadwal/{id}', [AdminJadwalController::class, 'destroy'])->name('jadwal.destroy');
    Route::get('/admin/subjects', [AdminJadwalController::class, 'getSubjects'])->name('subjects.index');
    Route::post('/admin/subjects', [AdminJadwalController::class, 'storeSubject'])->name('subjects.store');
    Route::post('/admin/subjects/upload', [AdminJadwalController::class, 'uploadSubjects'])->name('subjects.upload');
    Route::get('/admin/subjects/by-class', [AdminJadwalController::class, 'getSubjectsByClass'])->name('subjects.by-class');
    Route::delete('/admin/subjects/delete-all', [AdminJadwalController::class, 'deleteAllSubjects'])->name('subjects.delete-all');
    Route::get('/admin/subjects/{id}', [AdminJadwalController::class, 'showSubject'])->name('subjects.show');
    Route::put('/admin/subjects/{id}', [AdminJadwalController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/admin/subjects/{id}', [AdminJadwalController::class, 'destroySubject'])->name('subjects.destroy');
    
    // Manual Class Subject Management
    Route::get('/admin/manual-form-data', [AdminJadwalController::class, 'getManualFormData'])->name('manual.form-data');
    Route::post('/admin/manual-class-subject', [AdminJadwalController::class, 'storeManualClassSubject'])->name('manual.class-subject.store');
    Route::get('/admin/class-subjects', [AdminJadwalController::class, 'getClassSubjects'])->name('class-subjects.index');
    Route::delete('/admin/class-subjects/{id}', [AdminJadwalController::class, 'destroyClassSubject'])->name('class-subjects.destroy');
    
    // Classes routes
    Route::get('/admin/classes', [AdminJadwalController::class, 'getClasses'])->name('classes.index');
    Route::post('/admin/classes', [AdminJadwalController::class, 'storeClass'])->name('classes.store');
    Route::post('/admin/classes/import', [AdminJadwalController::class, 'importClasses'])->name('classes.import');
    Route::delete('/admin/classes/delete-all', [AdminJadwalController::class, 'deleteAllClasses'])->name('classes.delete-all');
    Route::get('/admin/classes/{id}', [AdminJadwalController::class, 'showClass'])->name('classes.show');
    Route::put('/admin/classes/{id}', [AdminJadwalController::class, 'updateClass'])->name('classes.update');
    Route::delete('/admin/classes/{id}', [AdminJadwalController::class, 'destroyClass'])->name('classes.destroy');

    // Terms (Semester) routes
    Route::get('/admin/terms', [\App\Http\Controllers\Admin\TermController::class, 'index'])->name('terms.index');
    Route::get('/admin/terms/data', [\App\Http\Controllers\Admin\TermController::class, 'data'])->name('terms.data');
    Route::post('/admin/terms', [\App\Http\Controllers\Admin\TermController::class, 'store'])->name('terms.store');
    Route::get('/admin/terms/{term}', [\App\Http\Controllers\Admin\TermController::class, 'show'])->name('terms.show');
    Route::put('/admin/terms/{term}', [\App\Http\Controllers\Admin\TermController::class, 'update'])->name('terms.update');
    Route::delete('/admin/terms/{term}', [\App\Http\Controllers\Admin\TermController::class, 'destroy'])->name('terms.destroy');
    Route::delete('/admin/terms/delete-all', [\App\Http\Controllers\Admin\TermController::class, 'deleteAll'])->name('terms.delete-all');
    Route::get('/admin/terms/active', [\App\Http\Controllers\Admin\TermController::class, 'getActive'])->name('terms.active');
    

    // Jadwal Pelajaran Kelas XI
    Route::get('/jadwal-pelajaran-xi', [AdminJadwalController::class, 'jadwalPelajaranXi'])->name('admin.jadwal-pelajaran-xi');
    Route::get('/admin/jadwal-xi', [XiJadwalController::class, 'index'])->name('jadwal-xi.index');
    Route::post('/admin/jadwal-xi/import', [XiJadwalController::class, 'import'])->name('jadwal-xi.import');
    Route::get('/admin/jadwal-xi/filter-options', [XiJadwalController::class, 'getFilterOptions'])->name('jadwal-xi.filter-options');
    Route::get('/admin/jadwal-xi/statistics', [XiJadwalController::class, 'getStatistics'])->name('jadwal-xi.statistics');
    Route::delete('/admin/jadwal-xi/bulk-delete', [XiJadwalController::class, 'bulkDelete'])->name('jadwal-xi.bulkDelete');
    Route::delete('/admin/jadwal-xi/delete-all', [XiJadwalController::class, 'deleteAllJadwalXi'])->name('jadwal-xi.delete-all');
    Route::delete('/admin/jadwal-xi/{id}', [XiJadwalController::class, 'destroy'])->name('jadwal-xi.destroy');

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
    Route::get('/manage-user', [UserController::class, 'index'])->name('users.manage');

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

    // Admin Laporan
    Route::get('/admin.laporan', [\App\Http\Controllers\Admin\AdminReportController::class, 'index'])->name('admin.laporan');
    Route::get('/admin.laporan.export', [\App\Http\Controllers\Admin\AdminReportController::class, 'export'])->name('admin.laporan.export');

    // Admin Pengumuman
    Route::get('/admin/pengumuman', function () {
        return view('admin.pengumuman');
    })->name('admin.pengumuman');
    Route::get('/admin/pengumuman/data', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('admin.pengumuman.data');
    Route::post('/admin/pengumuman', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('admin.pengumuman.store');
    Route::put('/admin/pengumuman/{id}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('admin.pengumuman.update');
    Route::delete('/admin/pengumuman/{id}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('admin.pengumuman.destroy');
    Route::post('/admin/pengumuman/{announcement}/toggle-status', [\App\Http\Controllers\Admin\AnnouncementController::class, 'toggleStatus'])->name('admin.pengumuman.toggle-status');
    

    // Admin Pengaturan
    Route::get('/admin/pengaturan', function () {
        return view('admin.pengaturan');
    })->name('admin.pengaturan');
    
    // Admin Pengaturan Stats
    Route::get('/admin/pengaturan/stats', function () {
        $stats = [
            'users' => \App\Models\User::count(),
            'teachers' => \App\Models\Teacher::count(),
            'students' => \App\Models\Student::count(),
            'announcements' => \App\Models\Announcement::count()
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    })->name('admin.pengaturan.stats');
    Route::post('/admin/pengaturan/system', function () {
        // System settings functionality will be implemented here
        return response()->json(['success' => true, 'message' => 'Pengaturan sistem berhasil disimpan']);
    })->name('admin.pengaturan.system');
    Route::post('/admin/pengaturan/attendance', function () {
        // Attendance settings functionality will be implemented here
        return response()->json(['success' => true, 'message' => 'Pengaturan absensi berhasil disimpan']);
    })->name('admin.pengaturan.attendance');
    Route::post('/admin/pengaturan/notification', function () {
        // Notification settings functionality will be implemented here
        return response()->json(['success' => true, 'message' => 'Pengaturan notifikasi berhasil disimpan']);
    })->name('admin.pengaturan.notification');
    Route::post('/admin/pengaturan/security', function () {
        // Security settings functionality will be implemented here
        return response()->json(['success' => true, 'message' => 'Pengaturan keamanan berhasil disimpan']);
    })->name('admin.pengaturan.security');
    Route::post('/admin/pengaturan/profile', function (\Illuminate\Http\Request $request) {
        $user = Auth::user();
        
        // Validation rules
        $rules = [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'current_password' => 'required|string'
        ];

        // Add password validation if new password is provided
        if ($request->filled('new_password')) {
            $rules['new_password'] = 'required|string|min:8|confirmed';
            $rules['new_password_confirmation'] = 'required|string|min:8';
        }

        $request->validate($rules);

        // Verify current password
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini tidak sesuai'
            ], 422);
        }

        // Prepare update data
        $updateData = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone
        ];

        // Update password if new password is provided
        if ($request->filled('new_password')) {
            $updateData['password_hash'] = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        // Update user profile
        $user->update($updateData);

        $message = 'Profil berhasil diperbarui';
        if ($request->filled('new_password')) {
            $message .= ' dan password berhasil diubah';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    })->name('admin.pengaturan.profile');
    
    // Admin upload photo
    Route::post('/admin/pengaturan/photo', function (\Illuminate\Http\Request $request) {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:200',
        ], [
            'photo.required' => 'Foto harus dipilih.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus jpeg, png, jpg, atau gif.',
            'photo.max' => 'Ukuran foto maksimal 200KB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            $user = \Illuminate\Support\Facades\Auth::user();
            
            // Delete old photo if exists
            if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists('users/' . $user->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('users/' . $user->photo);
            }
            
            // Store new photo
            $fileName = time() . '_' . $user->id . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('users', $fileName, 'public');
            
            // Update user photo
            $user->photo = $fileName;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'photo_url' => \Illuminate\Support\Facades\Storage::url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah foto: ' . $e->getMessage()
            ], 500);
        }
    })->name('admin.pengaturan.photo');
    
    Route::post('/admin/pengaturan/clear-cache', function () {
        // Clear cache functionality will be implemented here
        return response()->json(['success' => true, 'message' => 'Cache berhasil dihapus']);
    })->name('admin.pengaturan.clear-cache');
    Route::post('/admin/pengaturan/optimize-database', function () {
        // Optimize database functionality will be implemented here
        return response()->json(['success' => true, 'message' => 'Database berhasil dioptimalkan']);
    })->name('admin.pengaturan.optimize-database');

    // Admin Bantuan
    Route::get('/admin/bantuan', function () {
        return view('admin.bantuan');
    })->name('admin.bantuan');
    Route::post('/admin/bantuan/test-system', function () {
        // System test functionality will be implemented here
        return response()->json(['success' => true, 'message' => 'Sistem berfungsi dengan baik']);
    })->name('admin.bantuan.test-system');
    Route::get('/admin/bantuan/logs', function () {
        // View logs functionality will be implemented here
        return response()->json(['message' => 'Logs functionality not implemented yet']);
    })->name('admin.bantuan.logs');
    Route::post('/admin/bantuan/clear-cache', function () {
        // Clear cache functionality will be implemented here
        return response()->json(['success' => true, 'message' => 'Cache berhasil dihapus']);
    })->name('admin.bantuan.clear-cache');
    Route::post('/admin/bantuan/restart-services', function () {
        // Restart services functionality will be implemented here
        return response()->json(['success' => true, 'message' => 'Layanan berhasil di-restart']);
    })->name('admin.bantuan.restart-services');
    Route::post('/admin/bantuan/send-message', function () {
        // Send message functionality will be implemented here
        return response()->json(['success' => true, 'message' => 'Pesan berhasil dikirim']);
    })->name('admin.bantuan.send-message');

    // Delegasi routes
    Route::get('/admin/delegasi', [\App\Http\Controllers\Admin\DelegationController::class, 'index'])->name('admin.delegasi');
    Route::post('/admin/delegasi', [\App\Http\Controllers\Admin\DelegationController::class, 'store'])->name('admin.delegasi.store');
    Route::post('/admin/delegasi/check-email', [\App\Http\Controllers\Admin\DelegationController::class, 'checkEmail'])->name('admin.delegasi.check-email');
    Route::put('/admin/delegasi/{id}', [\App\Http\Controllers\Admin\DelegationController::class, 'update'])->name('admin.delegasi.update');
    Route::delete('/admin/delegasi/{id}', [\App\Http\Controllers\Admin\DelegationController::class, 'destroy'])->name('admin.delegasi.destroy');

});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/guru/dashboard', [DashboardController::class, 'index'])->name('guru.dashboard');
    Route::get('/scan-qr', [AbsensiController::class, 'showScanner'])->name('guru.absensi.scan');
    Route::post('/scan-qr/generate-qr', [AbsensiController::class, 'generateQrCode'])->name('guru.absensi.generate-qr');
    Route::get('/test-timetable', function() {
        $timetables = \App\Models\Timetable::with('classSubject.subject', 'classSubject.class')->get();
        return response()->json([
            'count' => $timetables->count(),
            'timetables' => $timetables->take(3)->toArray()
        ]);
    });
    Route::post('/test-generate-qr', function(\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Log::info('Test route called with data:', $request->all());
        return response()->json(['message' => 'Test route working', 'data' => $request->all()]);
    });
    Route::get('/test-timetable-5241', function() {
        $timetable = \App\Models\Timetable::find(5241);
        if ($timetable) {
            return response()->json(['found' => true, 'timetable' => $timetable->toArray()]);
        } else {
            return response()->json(['found' => false]);
        }
    });
    Route::post('/scan-qr/process', [AbsensiController::class, 'processScan'])->name('guru.absensi.process');
    Route::post('/scan-qr/stop-session', [AbsensiController::class, 'stopSession'])->name('guru.absensi.stop-session');
    Route::get('/scan-qr/results/{timetable_id}', [AbsensiController::class, 'getScanResults'])->name('guru.absensi.results');
    Route::get('/status-absensi', [AbsensiController::class, 'showStatus'])->name('guru.status-absensi');
    Route::get('/jadwal-mengajar', [JadwalController::class, 'index'])->name('guru.jadwal-mengajar');
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('guru.pengumuman');
    
    // Permohonan Izin Siswa (API endpoints untuk dashboard) - moved before catch-all routes
    
    // Test endpoint untuk debug
    Route::get('/test-permohonan/{id}', function($id) {
        Log::info('=== TEST ENDPOINT CALLED ===');
        Log::info('ID: ' . $id);
        return response()->json(['success' => true, 'id' => $id]);
    });
    
    // Halaman baru untuk guru
    Route::get('/pengumuman-guru', function () {
        return view('guru.pengumuman-guru');
    })->name('guru.pengumuman-guru');
    
    Route::get('/pengaturan-guru', [\App\Http\Controllers\Guru\PengaturanController::class, 'index'])->name('guru.pengaturan-guru');
    Route::put('/pengaturan-guru/profil', [\App\Http\Controllers\Guru\PengaturanController::class, 'updateProfil'])->name('guru.pengaturan.update-profil');
    Route::post('/pengaturan-guru/password', [\App\Http\Controllers\Guru\PengaturanController::class, 'updatePassword'])->name('guru.pengaturan.update-password');
    Route::post('/pengaturan-guru/photo', [\App\Http\Controllers\Guru\PengaturanController::class, 'updatePhoto'])->name('guru.pengaturan.photo');
    
    Route::get('/bantuan-guru', function () {
        return view('guru.bantuan-guru');
    })->name('guru.bantuan-guru');
    
    // Delegasi routes
    Route::get('/guru/delegasi', [\App\Http\Controllers\Guru\DelegationController::class, 'index'])->name('guru.delegasi');
    Route::get('/guru/delegasi/today-count', [\App\Http\Controllers\Guru\DelegationController::class, 'getTodayCount'])->name('guru.delegasi.today-count');
    
});

// Student routes
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [DashboardMuridController::class, 'index'])->name('murid.dashboard');
    Route::get('/student/jadwal', [JadwalPelajaranController::class, 'index'])->name('murid.jadwal');
    Route::get('/student/qr', [DashboardMuridController::class, 'qr'])->name('murid.qr');
    Route::get('/student/absensi', [DashboardMuridController::class, 'absensi'])->name('murid.absensi');
    Route::post('/student/qr/submit', [ScanController::class, 'submit'])->name('murid.qr.submit');
    Route::post('/student/absensi/scan', [ScanController::class, 'submit'])->name('murid.absensi.scan');
    Route::get('/student/attendance/history', [ScanController::class, 'getAttendanceHistory'])->name('murid.attendance.history');
    
    // New routes for additional pages
    Route::get('/student/pengumuman', function () {
        return view('murid.pengumuman');
    })->name('murid.pengumuman');
    
    // Permohonan Izin
    Route::get('/student/permohonan-izin', [\App\Http\Controllers\Murid\LeaveRequestController::class, 'index'])->name('murid.permohonan-izin');
    Route::post('/student/permohonan-izin', [\App\Http\Controllers\Murid\LeaveRequestController::class, 'store'])->name('murid.permohonan-izin.store');
    Route::get('/student/riwayat-permohonan-izin', [\App\Http\Controllers\Murid\LeaveRequestController::class, 'history'])->name('murid.riwayat-permohonan-izin');
    Route::get('/student/permohonan-izin/{id}', [\App\Http\Controllers\Murid\LeaveRequestController::class, 'show'])->name('murid.permohonan-izin.show');
    
    // Debug route
    Route::get('/debug/leave-requests', function() {
        $requests = \App\Models\LeaveRequest::all();
        return response()->json([
            'count' => $requests->count(),
            'data' => $requests
        ]);
    });
    
    Route::get('/student/pengaturan', function () {
        $user = Auth::user();
        return view('murid.pengaturan', compact('user'));
    })->name('murid.pengaturan');
    Route::post('/student/pengaturan/profile', [\App\Http\Controllers\Murid\PengaturanController::class, 'updateProfile'])->name('murid.pengaturan.profile');
    Route::post('/student/pengaturan/password', [\App\Http\Controllers\Murid\PengaturanController::class, 'updatePassword'])->name('murid.pengaturan.password');
    Route::post('/student/pengaturan/photo', [\App\Http\Controllers\Murid\PengaturanController::class, 'updatePhoto'])->name('murid.pengaturan.photo');
    
    // Delegasi routes untuk murid
    Route::get('/student/delegasi', [\App\Http\Controllers\Murid\DelegationController::class, 'index'])->name('murid.delegasi');
    
    // Routes untuk generate QR dan stop session dari delegasi (murid)
    Route::post('/student/delegasi/generate-qr', [\App\Http\Controllers\Guru\AbsensiController::class, 'generateQrCode'])->name('murid.delegasi.generate-qr');
    Route::post('/student/delegasi/stop-session', [\App\Http\Controllers\Guru\AbsensiController::class, 'stopSession'])->name('murid.delegasi.stop-session');
    
    Route::get('/student/bantuan', function () {
        return view('murid.bantuan');
    })->name('murid.bantuan');
});

// Specific API routes that need to be before catch-all routes
Route::middleware(['auth'])->group(function () {
    // Permohonan Izin Siswa (API endpoints untuk dashboard)
    Route::get('/guru/permohonan-izin/{id}', [\App\Http\Controllers\Guru\LeaveRequestController::class, 'show'])->name('guru.permohonan-izin.show');
    Route::post('/guru/permohonan-izin/{id}/approve', [\App\Http\Controllers\Guru\LeaveRequestController::class, 'approve'])->name('guru.permohonan-izin.approve');
    Route::post('/guru/permohonan-izin/{id}/reject', [\App\Http\Controllers\Guru\LeaveRequestController::class, 'reject'])->name('guru.permohonan-izin.reject');
});

// API routes for announcements (accessible by all authenticated users)
Route::group(['middleware' => 'auth'], function () {
    Route::get('/api/announcements/teachers', [\App\Http\Controllers\Admin\AnnouncementController::class, 'getForTeachers'])->name('api.announcements.teachers');
    Route::get('/api/announcements/students', [\App\Http\Controllers\Admin\AnnouncementController::class, 'getForStudents'])->name('api.announcements.students');
    Route::post('/api/announcements/{announcement}/mark-read', [\App\Http\Controllers\Admin\AnnouncementController::class, 'markAsRead'])->name('api.announcements.mark-read');
    Route::post('/api/announcements/{announcement}/mark-unread', [\App\Http\Controllers\Admin\AnnouncementController::class, 'markAsUnread'])->name('api.announcements.mark-unread');
    
    // API routes for notifications
    Route::get('/api/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('api.notifications.unread-count');
    Route::get('/api/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'getRecent'])->name('api.notifications.recent');
    Route::post('/api/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('api.notifications.mark-read');
    Route::post('/api/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('api.notifications.mark-all-read');
});


// Catch-all routes
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
