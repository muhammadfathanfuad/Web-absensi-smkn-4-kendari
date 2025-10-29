@extends('layouts.vertical-admin', ['subtitle' => 'Dashboard'])

@section('content')

@include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Dashboard'])


{{-- Welcome Card --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:shield-user-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-1">Selamat Datang, Admin!</h5>
                        <p class="text-muted mb-0">Dashboard Administrasi - {{ \App\Services\TimeOverrideService::now()->translatedFormat('l, j F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="row">
    <!-- Total Teachers -->
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:users-group-rounded-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Total Guru</p>
                        <h3 class="text-dark mt-2 mb-0">{{ $totalTeachers ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> Aktif</span>
                        <span class="text-muted ms-1 fs-12">Semua guru</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Students -->
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-success bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:user-outline" class="fs-32 text-success avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Total Siswa</p>
                        <h3 class="text-dark mt-2 mb-0">{{ $totalStudents ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-info"> <i class="bx bxs-info-circle fs-12"></i> Terdaftar</span>
                        <span class="text-muted ms-1 fs-12">Semua siswa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Subjects -->
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-warning bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:book-outline" class="fs-32 text-warning avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Mata Pelajaran</p>
                        <h3 class="text-dark mt-2 mb-0">{{ $totalSubjects ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-warning"> <i class="bx bxs-book fs-12"></i> Tersedia</span>
                        <span class="text-muted ms-1 fs-12">Semua mapel</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Classes -->
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-md bg-info bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:home-outline" class="fs-32 text-info avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <p class="text-muted mb-0 text-truncate">Total Kelas</p>
                        <h3 class="text-dark mt-2 mb-0">{{ $totalClasses ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-info"> <i class="bx bxs-home fs-12"></i> Aktif</span>
                        <span class="text-muted ms-1 fs-12">Semua kelas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Today's Statistics --}}
<div class="row">
    <!-- Today's Attendance -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-md bg-success bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:check-circle-outline" class="fs-32 text-success avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-1">Kehadiran Hari Ini</h5>
                        <h3 class="text-dark mt-2 mb-0">{{ $todayAttendance ?? 0 }}</h3>
                        <p class="text-muted mb-0">Total absensi hari ini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Leave Requests -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-md bg-warning bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:file-text-outline" class="fs-32 text-warning avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-1">Permohonan Izin</h5>
                        <h3 class="text-dark mt-2 mb-0">{{ $todayLeaveRequests ?? 0 }}</h3>
                        <p class="text-muted mb-0">Pengajuan hari ini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Sessions -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                            <iconify-icon icon="solar:play-circle-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-1">Sesi Aktif</h5>
                        <h3 class="text-dark mt-2 mb-0">{{ $todayActiveSessions ?? 0 }}</h3>   
                        <p class="text-muted mb-0">Sesi absensi berlangsung</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Charts Section --}}
<div class="row">
    <!-- Attendance Trends Chart -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Tren Kehadiran Siswa (30 Hari Terakhir)</h4>
            </div>
            <div class="card-body">
                <div dir="ltr">
                    <div id="attendanceTrendsChart" class="apex-charts" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Teacher Performance --}}
<div class="row">
    <!-- Teacher Performance Chart -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Guru dengan Kehadiran Terendah (7 Hari Terakhir)</h4>
                <p class="text-muted mb-0">
                    Menampilkan guru yang paling jarang masuk dibandingkan dengan jadwal yang ditetapkan.
                </p>
            </div>
            <div class="card-body">
                <div dir="ltr">
                    <div id="teacherPerformanceChart" class="apex-charts" style="height: 350px;"></div>
                </div>
                <div class="mt-3">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h5 class="mb-1 text-primary" id="totalActiveTeachers">{{ $teacherPagination['total'] ?? 0 }}</h5>
                                <p class="text-muted mb-0">Total Guru Aktif</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h5 class="mb-1 text-success" id="totalActualHours">{{ collect($teacherPagination['data'] ?? [])->sum('actual_hours') }}</h5>
                                <p class="text-muted mb-0">Jam Aktual (Halaman Ini)</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h5 class="mb-1 text-danger" id="totalScheduledHours">{{ collect($teacherPagination['data'] ?? [])->sum('scheduled_hours') }}</h5>
                                <p class="text-muted mb-0">Jam Terjadwal (Halaman Ini)</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h5 class="mb-1 text-warning" id="avgCompliance">{{ count($teacherPagination['data'] ?? []) > 0 ? round(collect($teacherPagination['data'] ?? [])->avg('compliance_rate'), 1) : 0 }}%</h5>
                            <p class="text-muted mb-0">Rata-rata Compliance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tables Section --}}
<div class="row">
    <!-- Teacher Performance Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Guru dengan Kehadiran Terendah</h4>
                <p class="text-muted mb-0">Halaman {{ $teacherPagination['current_page'] ?? 1 }} dari {{ $teacherPagination['last_page'] ?? 1 }}</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="teacher-performance-table" class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Guru</th>
                                <th>NIP</th>
                                <th>Jam Terjadwal</th>
                                <th>Jam Aktual</th>
                                <th>Compliance %</th>
                                <th>Sesi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (($teacherPagination['data'] ?? collect()) as $index => $teacher)
                                <tr>
                                    <td>{{ (($teacherPagination['current_page'] ?? 1) - 1) * 5 + $index + 1 }}</td>
                                    <td>
                                            <span class="fw-semibold">{{ $teacher['name'] }}</span>
                                    </td>
                                    <td>{{ $teacher['nip'] }}</td>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger py-1 px-2">
                                            {{ $teacher['scheduled_hours'] }} jam
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success py-1 px-2">
                                            {{ $teacher['actual_hours'] }} jam
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress progress-soft progress-sm me-2" style="width: 60px;">
                                                <div class="progress-bar {{ $teacher['compliance_rate'] >= 80 ? 'bg-success' : ($teacher['compliance_rate'] >= 60 ? 'bg-warning' : 'bg-danger') }}" role="progressbar" 
                                                     style="width: {{ $teacher['compliance_rate'] }}%" 
                                                     aria-valuenow="{{ $teacher['compliance_rate'] }}" 
                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="fw-semibold">{{ $teacher['compliance_rate'] }}%</span>
                                        </div>
                                    </td>
                                    <td>{{ $teacher['sessions_conducted'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted d-flex flex-column align-items-center">
                                            <iconify-icon icon="solar:user-outline" class="fs-48 mb-2"></iconify-icon>
                                            Tidak ada data guru.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if(isset($teacherPagination) && $teacherPagination['last_page'] > 1)
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted" id="pagination-info">
                        Menampilkan {{ $teacherPagination['from'] ?? 0 }} - {{ $teacherPagination['to'] ?? 0 }} dari {{ $teacherPagination['total'] ?? 0 }} guru
            </div>
                    <nav aria-label="Pagination">
                        <ul id="teacher-pagination" class="pagination pagination-sm mb-0">
                            <!-- Previous Page -->
                            @if(($teacherPagination['current_page'] ?? 1) > 1)
                                <li class="page-item">
                                    <a class="page-link pagination-link" href="#" data-page="{{ ($teacherPagination['current_page'] ?? 1) - 1 }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </span>
                                </li>
                            @endif
                            
                            <!-- Page Numbers -->
                            @for($i = 1; $i <= ($teacherPagination['last_page'] ?? 1); $i++)
                                @if($i == ($teacherPagination['current_page'] ?? 1))
                                    <li class="page-item active">
                                        <span class="page-link">{{ $i }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link pagination-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor
                            
                            <!-- Next Page -->
                            @if(($teacherPagination['current_page'] ?? 1) < ($teacherPagination['last_page'] ?? 1))
                                <li class="page-item">
                                    <a class="page-link pagination-link" href="#" data-page="{{ ($teacherPagination['current_page'] ?? 1) + 1 }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
        </div>
    </div>

{{-- Class Statistics Section --}}
<div class="row">
    <!-- Class Statistics Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Statistik Kelas</h4>
                <p class="text-muted mb-0">Halaman {{ $classStatistics['current_page'] ?? 1 }} dari {{ $classStatistics['last_page'] ?? 1 }}</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="class-statistics-table" class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Kelas</th>
                                <th>Grade</th>
                                <th>Kelompok</th>
                                <th>Wali Kelas</th>
                                <th>Jumlah Siswa</th>
                                <th>Mata Pelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (($classStatistics['data'] ?? collect()) as $index => $class)
                                <tr>
                                    <td>{{ (($classStatistics['current_page'] ?? 1) - 1) * 10 + $index + 1 }}</td>
                                    <td>
                                            <span class="fw-semibold">{{ $class['name'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary py-1 px-2">
                                            Grade {{ $class['grade'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($class['group'] !== '-')
                                            <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                                {{ $class['group'] }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $class['homeroom_teacher'] }}</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success py-1 px-2">
                                            {{ $class['students_count'] }} siswa
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                            {{ $class['subjects_count'] }} mapel
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted d-flex flex-column align-items-center">
                                            <iconify-icon icon="solar:home-outline" class="fs-48 mb-2"></iconify-icon>
                                            Tidak ada data kelas.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Class Statistics Pagination -->
                @if(($classStatistics['last_page'] ?? 1) > 1)
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        <span id="class-pagination-info">Menampilkan {{ $classStatistics['from'] ?? 0 }} - {{ $classStatistics['to'] ?? 0 }} dari {{ $classStatistics['total'] ?? 0 }} kelas</span>
            </div>
                    <nav aria-label="Class statistics pagination">
                        <ul id="class-pagination" class="pagination pagination-sm mb-0">
                            @if(($classStatistics['current_page'] ?? 1) > 1)
                                <li class="page-item">
                                    <a class="page-link class-pagination-link" href="#" data-page="{{ ($classStatistics['current_page'] ?? 1) - 1 }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </span>
                                </li>
                            @endif
                            
                            @for($i = 1; $i <= ($classStatistics['last_page'] ?? 1); $i++)
                                @if($i == ($classStatistics['current_page'] ?? 1))
                                    <li class="page-item active">
                                        <span class="page-link">{{ $i }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link class-pagination-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor
                            
                            @if(($classStatistics['current_page'] ?? 1) < ($classStatistics['last_page'] ?? 1))
                                <li class="page-item">
                                    <a class="page-link class-pagination-link" href="#" data-page="{{ ($classStatistics['current_page'] ?? 1) + 1 }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Leave Requests Summary and Recent Activities --}}
<div class="row equal-height-cards">

    <!-- Recent Activities -->
    <div class="col-xl-12">
        <div class="card h-100">
            <div class="card-header">
                <h4 class="card-title mb-0">Aktivitas Terbaru</h4>
                <p class="text-muted mb-0">Halaman {{ $recentActivities['current_page'] ?? 1 }} dari {{ $recentActivities['last_page'] ?? 1 }}</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="activities-table" class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Aktivitas Terbaru</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (($recentActivities['data'] ?? collect()) as $index => $activity)
                                <tr>
                                    <td>{{ (($recentActivities['current_page'] ?? 1) - 1) * 5 + $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs bg-{{ $activity['color'] }}-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <iconify-icon icon="solar:{{ $activity['icon'] }}-outline" class="fs-16 text-{{ $activity['color'] }}"></iconify-icon>
                            </div>
                                            <span class="fw-semibold">{{ $activity['description'] }}</span>
                            </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark py-1 px-2">
                                            {{ $activity['time']->diffForHumans() }}
                                        </span>
                                    </td>
                                </tr>
                    @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <div class="text-muted d-flex flex-column align-items-center">
                                <iconify-icon icon="solar:clock-outline" class="fs-48 mb-2"></iconify-icon>
                                Tidak ada aktivitas terbaru.
                            </div>
                                    </td>
                                </tr>
                    @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(($recentActivities['last_page'] ?? 1) > 1)
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        <span id="activities-pagination-info">Menampilkan {{ $recentActivities['from'] ?? 0 }} - {{ $recentActivities['to'] ?? 0 }} dari {{ $recentActivities['total'] ?? 0 }} aktivitas</span>
            </div>
                    <nav aria-label="Activities pagination">
                        <ul id="activities-pagination" class="pagination pagination-sm mb-0">
                            @if(($recentActivities['current_page'] ?? 1) > 1)
                                <li class="page-item">
                                    <a class="page-link activities-pagination-link" href="#" data-page="{{ ($recentActivities['current_page'] ?? 1) - 1 }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </span>
                                </li>
                            @endif
                            
                            @for($i = 1; $i <= ($recentActivities['last_page'] ?? 1); $i++)
                                @if($i == ($recentActivities['current_page'] ?? 1))
                                    <li class="page-item active">
                                        <span class="page-link">{{ $i }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link activities-pagination-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor
                            
                            @if(($recentActivities['current_page'] ?? 1) < ($recentActivities['last_page'] ?? 1))
                                <li class="page-item">
                                    <a class="page-link activities-pagination-link" href="#" data-page="{{ ($recentActivities['current_page'] ?? 1) + 1 }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        padding-left: 20px;
    }
    
    .timeline-item:before {
        content: '';
        position: absolute;
        left: -8px;
        top: 8px;
        width: 2px;
        height: calc(100% + 10px);
        background: #e9ecef;
    }
    
    .timeline-item:last-child:before {
        display: none;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 8px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 3px solid #5156be;
    }
    
    /* Custom styling for pie chart legend */
    .apexcharts-legend {
        justify-content: flex-start !important;
        text-align: left !important;
    }
    
    .apexcharts-legend-series {
        display: inline-block !important;
        margin-right: 15px !important;
        margin-bottom: 8px !important;
    }
    
    .apexcharts-legend-text {
        font-size: 12px !important;
        font-weight: 500 !important;
    }
    
    /* Responsive legend for smaller screens */
    @media (max-width: 768px) {
        .apexcharts-legend-series {
            margin-right: 10px !important;
            margin-bottom: 5px !important;
        }
    }
</style>
@endsection

@section('styles')
<style>
    .timeline-scroll {
        max-height: 400px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #dee2e6 #f8f9fa;
    }
    
    .timeline-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .timeline-scroll::-webkit-scrollbar-track {
        background: #f8f9fa;
        border-radius: 3px;
    }
    
    .timeline-scroll::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 3px;
    }
    
    .timeline-scroll::-webkit-scrollbar-thumb:hover {
        background: #adb5bd;
    }
    
    .equal-height-cards {
        display: flex;
        flex-wrap: wrap;
    }
    
    .equal-height-cards .card {
        display: flex;
        flex-direction: column;
    }
    
    .equal-height-cards .card-body {
        flex: 1;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<style>
/* Ensure active pagination state is visible */
.pagination .page-item.active .page-link {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: white !important;
}

.pagination .page-item.active {
    background-color: #0d6efd !important;
}

/* Debug: Make sure active state is visible */
.pagination .page-item.active .page-link:hover {
    background-color: #0b5ed7 !important;
    border-color: #0a58ca !important;
}
</style>
<script>
    // Data from controller
    var attendanceTrendsData = @json($attendanceTrends ?? []);
    var teacherPerformanceData = @json($teacherPagination['data'] ?? []);
    
    // Convert to array if it's an object (Laravel Collection)
    if (typeof teacherPerformanceData === 'object' && !Array.isArray(teacherPerformanceData)) {
        teacherPerformanceData = Object.values(teacherPerformanceData);
    }
    
    

    // Attendance Trends Chart
    var attendanceTrendsOptions = {
        series: [{
            name: 'Total Kehadiran',
            data: attendanceTrendsData.length > 0 ? attendanceTrendsData.map(item => item.total || 0) : [0, 0, 0, 0, 0, 0, 0]
        }, {
            name: 'Hadir',
            data: attendanceTrendsData.length > 0 ? attendanceTrendsData.map(item => item.present || 0) : [0, 0, 0, 0, 0, 0, 0]
        }, {
            name: 'Tidak Hadir',
            data: attendanceTrendsData.length > 0 ? attendanceTrendsData.map(item => item.absent || 0) : [0, 0, 0, 0, 0, 0, 0]
        }],
        chart: {
            type: 'area',
            height: 350,
            fontFamily: 'inherit',
            toolbar: {
                show: true
            }
        },
        colors: ['#5156be', '#34c38f', '#f46a6a'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            categories: attendanceTrendsData.length > 0 ? attendanceTrendsData.map(item => item.date || '') : ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            labels: {
                style: {
                    fontSize: '12px',
                    fontFamily: 'inherit'
                }
            }
        },
        yaxis: {
            title: {
                text: '',
                style: {
                    fontSize: '12px',
                    fontFamily: 'inherit'
                }
            },
            labels: {
                style: {
                    fontSize: '12px',
                    fontFamily: 'inherit'
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            fontSize: '12px',
            fontFamily: 'inherit',
            markers: {
                width: 8,
                height: 8,
                radius: 2
            },
            itemMargin: {
                horizontal: 15,
                vertical: 8
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        tooltip: {
            style: {
                fontSize: '12px',
                fontFamily: 'inherit'
            }
        }
    };


    // Teacher Performance Chart - Comparison between Scheduled vs Actual
    var teacherPerformanceOptions = {
        series: [{
            name: 'Jam Terjadwal',
            data: teacherPerformanceData && teacherPerformanceData.length > 0 ? teacherPerformanceData.map(item => item.scheduled_hours || 0) : [0, 0, 0, 0, 0]
        }, {
            name: 'Jam Aktual',
            data: teacherPerformanceData && teacherPerformanceData.length > 0 ? teacherPerformanceData.map(item => item.actual_hours || 0) : [0, 0, 0, 0, 0]
        }],
        chart: {
            type: 'bar',
            height: 350,
            fontFamily: 'inherit',
            toolbar: {
                show: true
            }
        },
        colors: ['#f46a6a', '#34c38f'], // Red for scheduled, Green for actual
        plotOptions: {
            bar: {
                horizontal: true,
                columnWidth: '55%',
                borderRadius: 4
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: teacherPerformanceData && teacherPerformanceData.length > 0 ? teacherPerformanceData.map(item => item.name || '') : ['Guru 1', 'Guru 2', 'Guru 3', 'Guru 4', 'Guru 5'],
            labels: {
                style: {
                    fontSize: '12px',
                    fontFamily: 'inherit'
                }
            }
        },
        yaxis: {
            title: {
                text: '',
                style: {
                    fontSize: '12px',
                    fontFamily: 'inherit'
                }
            },
            labels: {
                style: {
                    fontSize: '12px',
                    fontFamily: 'inherit'
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            fontSize: '12px',
            fontFamily: 'inherit',
            markers: {
                width: 8,
                height: 8,
                radius: 2
            },
            itemMargin: {
                horizontal: 15,
                vertical: 8
            }
        },
        tooltip: {
            style: {
                fontSize: '12px',
                fontFamily: 'inherit'
            },
            y: {
                formatter: function (val) {
                    return val + " jam"
                }
            }
        }
    };


    // Initialize charts when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Check if ApexCharts is available
            if (typeof ApexCharts === 'undefined') {
                return;
            }
            // Initialize Attendance Trends Chart
            if (document.getElementById('attendanceTrendsChart')) {
                var attendanceTrendsChart = new ApexCharts(document.querySelector("#attendanceTrendsChart"), attendanceTrendsOptions);
                attendanceTrendsChart.render();
            }


            // Initialize Teacher Performance Chart
            if (document.getElementById('teacherPerformanceChart')) {
                window.teacherPerformanceChart = new ApexCharts(document.querySelector("#teacherPerformanceChart"), teacherPerformanceOptions);
                window.teacherPerformanceChart.render();
            }

        } catch (error) {
            // Error handling for chart initialization
        }
    });
    
    // AJAX Pagination for Teacher Performance
    document.addEventListener('DOMContentLoaded', function() {
        // Use vanilla JavaScript for pagination (jQuery not available)
        initVanillaPagination();
    });
    
    
    function initVanillaPagination() {
        // Use event delegation to handle clicks on pagination links
        document.addEventListener('click', function(e) {
            // Check if clicked element is a pagination link or inside one
            var paginationLink = e.target.closest('.pagination-link');
            
            if (paginationLink) {
                e.preventDefault();
                
                var page = paginationLink.getAttribute('data-page');
                
                if (!page) {
                    return;
                }
                
                // Show loading state
                var paginationLinks = document.querySelectorAll('.pagination-link');
                paginationLinks.forEach(function(link) {
                    link.classList.add('disabled');
                    link.style.pointerEvents = 'none';
                });
                
                var chartContainer = document.getElementById('teacherPerformanceChart');
                if (chartContainer) {
                    chartContainer.innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height: 350px;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                }
                
                // Make AJAX request using fetch
                fetch('{{ route("admin.dashboard.teacher-pagination") }}?page=' + page)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            updateTeacherData(data.data);
                        } else {
                            alert('Error loading data: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error loading data. Please try again.');
                    })
                    .finally(() => {
                        // Remove loading state
                        paginationLinks.forEach(function(link) {
                            link.classList.remove('disabled');
                            link.style.pointerEvents = 'auto';
                        });
                    });
            }
        });
    }
    
    function updateTeacherData(paginationData) {
        // Update chart data
        var chartData = paginationData.data || [];
        var scheduledHours = chartData.map(item => item.scheduled_hours || 0);
        var actualHours = chartData.map(item => item.actual_hours || 0);
        var categories = chartData.map(item => item.name || '');
        
        // Update chart
        if (window.teacherPerformanceChart) {
            window.teacherPerformanceChart.updateOptions({
                series: [{
                    name: 'Jam Terjadwal',
                    data: scheduledHours
                }, {
                    name: 'Jam Aktual',
                    data: actualHours
                }],
                xaxis: {
                    categories: categories
                }
            });
        }
        
        // Update table
        updateTeacherTable(chartData, paginationData);
        
        // Update pagination
        updatePagination(paginationData);
        
        // Update statistics
        updateStatistics(paginationData);
    }
    
    function updateTeacherTable(data, paginationData) {
        var tbody = document.querySelector('#teacher-performance-table tbody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        if (data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="text-muted d-flex flex-column align-items-center">
                            <iconify-icon icon="solar:user-outline" class="fs-48 mb-2"></iconify-icon>
                            Tidak ada data guru.
                        </div>
                    </td>
                </tr>
            `;
            return;
        }
        
        data.forEach(function(teacher, index) {
            var rowNumber = ((paginationData.current_page - 1) * 5) + index + 1;
            var complianceClass = teacher.compliance_rate >= 80 ? 'bg-success' : (teacher.compliance_rate >= 60 ? 'bg-warning' : 'bg-danger');
            
            var row = `
                <tr>
                    <td>${rowNumber}</td>
                    <td>
                        <span class="fw-semibold">${teacher.name}</span>
                    </td>
                    <td>${teacher.nip}</td>
                    <td>
                        <span class="badge bg-danger-subtle text-danger py-1 px-2">
                            ${teacher.scheduled_hours} jam
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success py-1 px-2">
                            ${teacher.actual_hours} jam
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="progress progress-soft progress-sm me-2" style="width: 60px;">
                                <div class="progress-bar ${complianceClass}" role="progressbar"
                                     style="width: ${teacher.compliance_rate}%"
                                     aria-valuenow="${teacher.compliance_rate}"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="fw-semibold">${teacher.compliance_rate}%</span>
                        </div>
                    </td>
                    <td>${teacher.sessions_conducted}</td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }
    
    function updatePagination(paginationData) {
        var paginationContainer = document.querySelector('#teacher-pagination');
        if (!paginationContainer) {
            return;
        }
        
        paginationContainer.innerHTML = '';
        
        // Previous button
        var currentPage = parseInt(paginationData.current_page);
        if (currentPage > 1) {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item">
                    <a class="page-link pagination-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `);
        } else {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item disabled">
                    <span class="page-link" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </span>
                </li>
            `);
        }
        
        // Page numbers
        for (var i = 1; i <= paginationData.last_page; i++) {
            // Convert current_page to number for comparison
            var currentPage = parseInt(paginationData.current_page);
            if (i === currentPage) {
                var activePageHtml = `
                    <li class="page-item active" style="background-color: #0d6efd !important;">
                        <span class="page-link" style="background-color: #0d6efd !important; color: white !important; border-color: #0d6efd !important;">${i}</span>
                    </li>
                `;
                paginationContainer.insertAdjacentHTML('beforeend', activePageHtml);
            } else {
                var inactivePageHtml = `
                    <li class="page-item">
                        <a class="page-link pagination-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
                paginationContainer.insertAdjacentHTML('beforeend', inactivePageHtml);
            }
        }
        
        // Next button
        if (currentPage < paginationData.last_page) {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item">
                    <a class="page-link pagination-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `);
        } else {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item disabled">
                    <span class="page-link" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </span>
                </li>
            `);
        }
        
    }
    
    function updateStatistics(paginationData) {
        var data = paginationData.data || [];
        var totalActualHours = data.reduce((sum, teacher) => sum + (teacher.actual_hours || 0), 0);
        var totalScheduledHours = data.reduce((sum, teacher) => sum + (teacher.scheduled_hours || 0), 0);
        var avgCompliance = data.length > 0 ? (data.reduce((sum, teacher) => sum + (teacher.compliance_rate || 0), 0) / data.length).toFixed(1) : 0;
        
        // Update statistics using vanilla JavaScript
        var totalActiveTeachersEl = document.getElementById('totalActiveTeachers');
        var totalActualHoursEl = document.getElementById('totalActualHours');
        var totalScheduledHoursEl = document.getElementById('totalScheduledHours');
        var avgComplianceEl = document.getElementById('avgCompliance');
        
        if (totalActiveTeachersEl) totalActiveTeachersEl.textContent = paginationData.total || 0;
        if (totalActualHoursEl) totalActualHoursEl.textContent = totalActualHours;
        if (totalScheduledHoursEl) totalScheduledHoursEl.textContent = totalScheduledHours;
        if (avgComplianceEl) avgComplianceEl.textContent = avgCompliance + '%';
        
        // Update pagination info using specific ID
        var paginationInfoEl = document.getElementById('pagination-info');
        if (paginationInfoEl) {
            var newText = `Menampilkan ${paginationData.from || 0} - ${paginationData.to || 0} dari ${paginationData.total || 0} guru`;
            paginationInfoEl.textContent = newText;
        }
    }
    
    // Class Statistics AJAX Pagination
    document.addEventListener('DOMContentLoaded', function() {
        initClassPagination();
    });
    
    function initClassPagination() {
        document.addEventListener('click', function(e) {
            var classPaginationLink = e.target.closest('.class-pagination-link');
            
            if (classPaginationLink) {
                e.preventDefault();
                
                var page = classPaginationLink.getAttribute('data-page');
                
                if (!page) {
                    return;
                }
                
                // Show loading state
                var classPaginationLinks = document.querySelectorAll('.class-pagination-link');
                classPaginationLinks.forEach(function(link) {
                    link.classList.add('disabled');
                    link.style.pointerEvents = 'none';
                });
                
                // Make AJAX request
                fetch('{{ route("admin.dashboard.class-pagination") }}?page=' + page)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            updateClassData(data.data);
                        } else {
                            alert('Error loading data: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error loading data. Please try again.');
                    })
                    .finally(() => {
                        // Remove loading state
                        classPaginationLinks.forEach(function(link) {
                            link.classList.remove('disabled');
                            link.style.pointerEvents = 'auto';
                        });
                    });
            }
        });
    }
    
    function updateClassData(paginationData) {
        // Update table body - specifically for class statistics table
        var tbody = document.querySelector('#class-statistics-table tbody');
        if (tbody) {
            tbody.innerHTML = '';
            
            if (paginationData.data && paginationData.data.length > 0) {
                paginationData.data.forEach(function(classItem, index) {
                    var rowNumber = (paginationData.current_page - 1) * 10 + index + 1;
                    var row = `
                        <tr>
                            <td>${rowNumber}</td>
                            <td>
                                <span class="fw-semibold">${classItem.name}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary py-1 px-2">
                                    Grade ${classItem.grade}
                                </span>
                            </td>
                            <td>
                                ${classItem.group !== '-' ? 
                                    `<span class="badge bg-warning-subtle text-warning py-1 px-2">${classItem.group}</span>` : 
                                    '<span class="text-muted">-</span>'
                                }
                            </td>
                            <td>${classItem.homeroom_teacher}</td>
                            <td>
                                <span class="badge bg-success-subtle text-success py-1 px-2">
                                    ${classItem.students_count} siswa
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                    ${classItem.subjects_count} mapel
                                </span>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted d-flex flex-column align-items-center">
                                <iconify-icon icon="solar:home-outline" class="fs-48 mb-2"></iconify-icon>
                                Tidak ada data kelas.
                            </div>
                        </td>
                    </tr>
                `;
            }
        }
        
        // Update pagination controls
        updateClassPagination(paginationData);
        
        // Update pagination info
        updateClassPaginationInfo(paginationData);
    }
    
    function updateClassPagination(paginationData) {
        var paginationContainer = document.querySelector('#class-pagination');
        if (!paginationContainer) {
            return;
        }
        
        paginationContainer.innerHTML = '';
        
        // Previous button
        var currentPage = parseInt(paginationData.current_page);
        if (currentPage > 1) {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item">
                    <a class="page-link class-pagination-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `);
        } else {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item disabled">
                    <span class="page-link" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </span>
                </li>
            `);
        }
        
        // Page numbers
        for (var i = 1; i <= paginationData.last_page; i++) {
            if (i === currentPage) {
                var activePageHtml = `
                    <li class="page-item active" style="background-color: #0d6efd !important;">
                        <span class="page-link" style="background-color: #0d6efd !important; color: white !important; border-color: #0d6efd !important;">${i}</span>
                    </li>
                `;
                paginationContainer.insertAdjacentHTML('beforeend', activePageHtml);
            } else {
                var inactivePageHtml = `
                    <li class="page-item">
                        <a class="page-link class-pagination-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
                paginationContainer.insertAdjacentHTML('beforeend', inactivePageHtml);
            }
        }
        
        // Next button
        if (currentPage < paginationData.last_page) {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item">
                    <a class="page-link class-pagination-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `);
        } else {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item disabled">
                    <span class="page-link" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </span>
                </li>
            `);
        }
    }
    
    function updateClassPaginationInfo(paginationData) {
        var paginationInfoEl = document.getElementById('class-pagination-info');
        if (paginationInfoEl) {
            var newText = `Menampilkan ${paginationData.from || 0} - ${paginationData.to || 0} dari ${paginationData.total || 0} kelas`;
            paginationInfoEl.textContent = newText;
        }
    }
    
    // Activities AJAX Pagination
    document.addEventListener('DOMContentLoaded', function() {
        initActivitiesPagination();
    });

    function initActivitiesPagination() {
        document.addEventListener('click', function(e) {
            var activitiesPaginationLink = e.target.closest('.activities-pagination-link');
            
            if (activitiesPaginationLink) {
                e.preventDefault();
                
                var page = activitiesPaginationLink.getAttribute('data-page');
                
                if (!page) {
                    return;
                }
                
                // Show loading state
                var activitiesPaginationLinks = document.querySelectorAll('.activities-pagination-link');
                activitiesPaginationLinks.forEach(function(link) {
                    link.classList.add('disabled');
                    link.style.pointerEvents = 'none';
                });
                
                // Make AJAX request
                fetch('{{ route("admin.dashboard.activities-pagination") }}?page=' + page)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            updateActivitiesData(data.data);
                        } else {
                            alert('Error loading data: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error loading data. Please try again.');
                    })
                    .finally(() => {
                        // Remove loading state
                        activitiesPaginationLinks.forEach(function(link) {
                            link.classList.remove('disabled');
                            link.style.pointerEvents = 'auto';
                        });
                    });
            }
        });
    }
    
    function updateActivitiesData(paginationData) {
        // Update table body
        var tbody = document.querySelector('#activities-table tbody');
        if (tbody) {
            tbody.innerHTML = '';
            
            if (paginationData.data && paginationData.data.length > 0) {
                paginationData.data.forEach(function(activity, index) {
                    var rowNumber = (paginationData.current_page - 1) * 5 + index + 1;
                    var row = `
                        <tr>
                            <td>${rowNumber}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs bg-${activity.color}-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <iconify-icon icon="solar:${activity.icon}-outline" class="fs-16 text-${activity.color}"></iconify-icon>
                                    </div>
                                    <span class="fw-semibold">${activity.description}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark py-1 px-2">
                                    ${activity.time_formatted}
                                </span>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-4">
                            <div class="text-muted d-flex flex-column align-items-center">
                                <iconify-icon icon="solar:clock-outline" class="fs-48 mb-2"></iconify-icon>
                                Tidak ada aktivitas terbaru.
                            </div>
                        </td>
                    </tr>
                `;
            }
        }
        
        // Update pagination controls
        updateActivitiesPagination(paginationData);
        
        // Update pagination info
        updateActivitiesPaginationInfo(paginationData);
    }
    
    function updateActivitiesPagination(paginationData) {
        var paginationContainer = document.querySelector('#activities-pagination');
        if (!paginationContainer) {
            return;
        }
        
        paginationContainer.innerHTML = '';
        
        // Previous button
        var currentPage = parseInt(paginationData.current_page);
        if (currentPage > 1) {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item">
                    <a class="page-link activities-pagination-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `);
        } else {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item disabled">
                    <span class="page-link" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </span>
                </li>
            `);
        }
        
        // Page numbers
        for (var i = 1; i <= paginationData.last_page; i++) {
            if (i === currentPage) {
                var activePageHtml = `
                    <li class="page-item active" style="background-color: #0d6efd !important;">
                        <span class="page-link" style="background-color: #0d6efd !important; color: white !important; border-color: #0d6efd !important;">${i}</span>
                    </li>
                `;
                paginationContainer.insertAdjacentHTML('beforeend', activePageHtml);
            } else {
                var inactivePageHtml = `
                    <li class="page-item">
                        <a class="page-link activities-pagination-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
                paginationContainer.insertAdjacentHTML('beforeend', inactivePageHtml);
            }
        }
        
        // Next button
        if (currentPage < paginationData.last_page) {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item">
                    <a class="page-link activities-pagination-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `);
        } else {
            paginationContainer.insertAdjacentHTML('beforeend', `
                <li class="page-item disabled">
                    <span class="page-link" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </span>
                </li>
            `);
        }
    }
    
    function updateActivitiesPaginationInfo(paginationData) {
        var paginationInfoEl = document.getElementById('activities-pagination-info');
        if (paginationInfoEl) {
            var newText = `Menampilkan ${paginationData.from || 0} - ${paginationData.to || 0} dari ${paginationData.total || 0} aktivitas`;
            paginationInfoEl.textContent = newText;
        }
    }
</script>
@endsection