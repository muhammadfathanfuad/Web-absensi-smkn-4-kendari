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
                <div class="mt-5">
                    <!-- Desktop: 4 kolom dalam 1 baris -->
                    <div class="row text-center d-none d-md-flex">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h5 class="mb-1 text-primary" id="totalActiveTeachers">{{ $teacherPagination['total'] ?? 0 }}</h5>
                                <p class="text-muted mb-0">Total Guru Aktif</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h5 class="mb-1 text-success" id="totalActualHours">{{ round(collect($teacherPagination['data'] ?? [])->sum('actual_hours'), 1) }}</h5>
                                <p class="text-muted mb-0">Jam Aktual (Halaman Ini)</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h5 class="mb-1 text-danger" id="totalScheduledHours">{{ round(collect($teacherPagination['data'] ?? [])->sum('scheduled_hours'), 1) }}</h5>
                                <p class="text-muted mb-0">Jam Terjadwal (Halaman Ini)</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h5 class="mb-1 text-warning" id="avgCompliance">{{ count($teacherPagination['data'] ?? []) > 0 ? round(collect($teacherPagination['data'] ?? [])->avg('compliance_rate'), 1) : 0 }}%</h5>
                            <p class="text-muted mb-0">Rata-rata Compliance</p>
                        </div>
                    </div>
                    
                    <!-- Mobile: 2 kolom dalam 2 baris -->
                    <div class="row text-center d-md-none">
                        <!-- Baris 1: Total Guru Aktif dan Jam Aktual -->
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h5 class="mb-1 text-primary" id="totalActiveTeachersMobile">{{ $teacherPagination['total'] ?? 0 }}</h5>
                                <p class="text-muted mb-0">Total Guru Aktif</p>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h5 class="mb-1 text-success" id="totalActualHoursMobile">{{ round(collect($teacherPagination['data'] ?? [])->sum('actual_hours'), 1) }}</h5>
                                <p class="text-muted mb-0">Jam Aktual (Halaman Ini)</p>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center d-md-none">
                        <!-- Baris 2: Jam Terjadwal dan Rata-rata Compliance -->
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h5 class="mb-1 text-danger" id="totalScheduledHoursMobile">{{ round(collect($teacherPagination['data'] ?? [])->sum('scheduled_hours'), 1) }}</h5>
                                <p class="text-muted mb-0">Jam Terjadwal (Halaman Ini)</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h5 class="mb-1 text-warning" id="avgComplianceMobile">{{ count($teacherPagination['data'] ?? []) > 0 ? round(collect($teacherPagination['data'] ?? [])->avg('compliance_rate'), 1) : 0 }}%</h5>
                                <p class="text-muted mb-0">Rata-rata Compliance</p>
                            </div>
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
                                    <td colspan="6" class="text-center py-4">
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

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Pass data from Laravel to JavaScript
    window.dashboardData = {
        attendanceTrends: @json($attendanceTrends ?? []),
        teacherPerformance: @json($teacherPagination['data'] ?? []),
        routes: {
            teacherPagination: '{{ route("admin.dashboard.teacher-pagination") }}',
            classPagination: '{{ route("admin.dashboard.class-pagination") }}',
            activitiesPagination: '{{ route("admin.dashboard.activities-pagination") }}'
        }
    };
</script>
@vite(['resources/js/admin/dashboard.js'])
@endsection
