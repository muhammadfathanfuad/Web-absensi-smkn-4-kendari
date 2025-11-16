@extends('layouts.vertical-admin', ['subtitle' => 'Laporan'])

@section('css')
    @vite(['resources/css/admin/laporan.css'])
@endsection

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Admin',
        'subtitle' => 'Laporan Kehadiran',
    ])

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.laporan') }}" id="filterForm">
                        <div class="row">
                            <div class="col-12">
                                <div class="report-header-controls d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0 report-label">Jenis Laporan</label>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-expanded="false" id="exportDropdownBtn">
                                            <i class="bx bx-download"></i> <span class="d-none d-sm-inline">Export</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="exportReport('pdf'); return false;">
                                                    <i class="bx bx-file"></i> Export PDF (.pdf)
                                                </a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="report-tabs-wrapper">
                                    <div class="btn-group" role="group" aria-label="Report type selection">
                                        <input type="radio" class="btn-check" name="report_type" id="report_teacher" value="teacher" 
                                            {{ request('report_type', 'teacher') == 'teacher' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary" for="report_teacher">Per Guru</label>

                                        <input type="radio" class="btn-check" name="report_type" id="report_student" value="student" 
                                            {{ request('report_type') == 'student' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary" for="report_student">Per Siswa</label>

                                        <input type="radio" class="btn-check" name="report_type" id="report_class" value="class" 
                                            {{ request('report_type') == 'class' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary" for="report_class">Per Kelas</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date Filter Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="border-top pt-3">
                                    <h6 class="mb-3">
                                        <i class="bx bx-calendar me-2"></i>Filter Periode Waktu
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="date_from" class="form-label">Dari Tanggal</label>
                                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                                value="{{ request('date_from', \App\Services\TimeOverrideService::now()->format('Y-m-d')) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="date_to" class="form-label">Sampai Tanggal</label>
                                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                                value="{{ request('date_to', \App\Services\TimeOverrideService::now()->format('Y-m-d')) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="setDateRange('today')">
                                                    Hari Ini
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="setDateRange('week')">
                                                    Minggu Ini
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="setDateRange('month')">
                                                    Bulan Ini
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Class Filter (Only for Student Report) -->
                                    <div class="row mt-3" id="classFilterSection" style="display: {{ request('report_type') == 'student' ? 'block' : 'none' }};">
                                        <div class="col-md-6">
                                            <label for="class_id" class="form-label">
                                                <i class="bx bx-group me-2"></i>Filter Kelas
                                            </label>
                                            <select class="form-select" id="class_id" name="class_id">
                                                <option value="">Semua Kelas</option>
                                                @if(isset($classes) && $classes->count() > 0)
                                                    @foreach ($classes as $class)
                                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->grade }} - {{ $class->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bx bx-search me-1"></i>Terapkan Filter
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="resetDateFilter()">
                                                <i class="bx bx-reset me-1"></i>Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @php
        $reportType = request('report_type', 'teacher');
        $dateFrom = request('date_from', \App\Services\TimeOverrideService::now()->format('Y-m-d'));
        $dateTo = request('date_to', \App\Services\TimeOverrideService::now()->format('Y-m-d'));
        
        // Convert date strings to Carbon instances for proper filtering
        $dateFromCarbon = \Carbon\Carbon::parse($dateFrom)->startOfDay();
        $dateToCarbon = \Carbon\Carbon::parse($dateTo)->endOfDay();

        // Get basic statistics
        $totalRecords = \App\Models\Attendance::count();
        $presentCount = \App\Models\Attendance::where('status', 'H')->count();
        $lateCount = \App\Models\Attendance::where('status', 'T')->count();
        $absentCount = \App\Models\Attendance::where('status', 'A')->count();

        $presentPercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 2) : 0;
        $latePercentage = $totalRecords > 0 ? round(($lateCount / $totalRecords) * 100, 2) : 0;
        $absentPercentage = $totalRecords > 0 ? round(($absentCount / $totalRecords) * 100, 2) : 0;

        // Report type labels
        $reportLabels = [
            'class' => 'Laporan Per Kelas',
            'student' => 'Laporan Per Siswa',
            'teacher' => 'Laporan Per Guru',
        ];
    @endphp

    <!-- Period Information -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bx bx-info-circle me-2"></i>
                <strong>Periode Laporan:</strong> 
                {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                <span class="badge bg-primary ms-2">{{ $reportLabels[$reportType] ?? 'Laporan' }}</span>
            </div>
        </div>
    </div>

    @if($reportType == 'teacher')
        <!-- Teacher Report -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Laporan Per Guru</h4>
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="teacherSearchInput" placeholder="Cari nama guru atau NIP...">
                                    <i class="bx bx-search search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="teacherReportTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Guru</th>
                                        <th>NIP</th>
                                        <th>Status Kehadiran</th>
                                        <th>Total Pertemuan</th>
                                        <th>Total Record</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody id="teacherTableBody">
                                    @php
                                        // Load all teachers data to JSON for client-side pagination
                                        $allTeachers = \App\Models\Teacher::with('user')->get();
                                        $perPage = 10;
                                        $currentPage = request()->get('page', 1);
                                        $totalPages = ceil($allTeachers->count() / $perPage);
                                        $teachers = $allTeachers->forPage($currentPage, $perPage);
                                        
                                        // Prepare teacher data with calculated stats
                                        $teachersWithStats = $allTeachers->map(function($teacher) use ($dateFromCarbon, $dateToCarbon, $dateFrom, $dateTo) {
                                            // Hitung total pertemuan (unique by subject, class, and date)
                                            $totalPertemuan = 0;
                                            $pertemuanMap = [];
                                            $timetables = \App\Models\Timetable::whereHas('classSubject.teacher', function($q) use ($teacher) {
                                                $q->where('teacher_id', $teacher->user_id);
                                            })->get();
                                            
                                            $startDate = \Carbon\Carbon::parse($dateFrom);
                                            $endDate = \Carbon\Carbon::parse($dateTo);
                                            
                                            while ($startDate->lte($endDate)) {
                                                $dayOfWeek = $startDate->dayOfWeek;
                                                $dateStr = $startDate->format('Y-m-d');
                                                
                                                // Get timetables for this day
                                                $dayTimetables = $timetables->filter(function($t) use ($dayOfWeek) {
                                                    return $t->day_of_week == $dayOfWeek;
                                                });
                                                
                                                foreach ($dayTimetables as $timetable) {
                                                    if (!$timetable->classSubject) continue;
                                                    
                                                    $subjectName = $timetable->classSubject->subject->name ?? 'N/A';
                                                    $className = $timetable->classSubject->class->name ?? 'N/A';
                                                    
                                                    // Create unique key based on subject, class, and date
                                                    $key = $subjectName . '_' . $className . '_' . $dateStr;
                                                    
                                                    if (!isset($pertemuanMap[$key])) {
                                                        $pertemuanMap[$key] = true;
                                                        $totalPertemuan++;
                                                    }
                                                }
                                                
                                                $startDate->addDay();
                                            }
                                            
                                            // Hitung total record
                                            $totalRecord = \App\Models\AttendanceSession::whereHas('timetable.classSubject.teacher', function($q) use ($teacher) {
                                                $q->where('teacher_id', $teacher->user_id);
                                            })
                                            ->whereBetween('created_at', [$dateFromCarbon, $dateToCarbon])
                                            ->where('is_active', false)
                                            ->count();
                                            
                                            // Hitung status kehadiran dari teacher_presences
                                            $presences = \App\Models\TeacherPresence::where('teacher_id', $teacher->user_id)
                                                ->whereBetween('date', [$dateFromCarbon->toDateString(), $dateToCarbon->toDateString()])
                                                ->get();
                                            
                                            $hadirCount = $presences->where('status', 'H')->count();
                                            $alfaCount = $presences->where('status', 'A')->count();
                                            $izinCount = $presences->where('status', 'I')->count();
                                            $sakitCount = $presences->where('status', 'S')->count();
                                            $totalPresence = $presences->count();
                                            
                                            // Tentukan status dominan berdasarkan prioritas: Alfa > Sakit > Izin > Hadir
                                            $statusKehadiranText = '-';
                                            $statusKehadiranBadge = 'secondary';
                                            
                                            if ($alfaCount > 0) {
                                                $statusKehadiranText = 'Alfa';
                                                $statusKehadiranBadge = 'danger';
                                            } elseif ($sakitCount > 0) {
                                                $statusKehadiranText = 'Sakit';
                                                $statusKehadiranBadge = 'warning';
                                            } elseif ($izinCount > 0) {
                                                $statusKehadiranText = 'Izin';
                                                $statusKehadiranBadge = 'info';
                                            } elseif ($hadirCount > 0) {
                                                $statusKehadiranText = 'Hadir';
                                                $statusKehadiranBadge = 'success';
                                            }
                                            
                                            return [
                                                'id' => $teacher->user_id,
                                                'nama' => $teacher->user->full_name ?? 'N/A',
                                                'nip' => $teacher->nip ?? 'N/A',
                                                'total_pertemuan' => $totalPertemuan,
                                                'total_record' => $totalRecord,
                                                'status_kehadiran' => $statusKehadiranText,
                                                'status_kehadiran_badge' => $statusKehadiranBadge
                                            ];
                                        });
                                    @endphp
                                    
                                    @foreach ($teachers as $index => $teacher)
                                        @php
                                            $teacherData = $teachersWithStats[$index] ?? null;
                                            if (!$teacherData) continue;
                                        @endphp
                                        <tr data-teacher-id="{{ $teacherData['id'] }}">
                                            <td>{{ $teacherData['nama'] }}</td>
                                            <td>{{ $teacherData['nip'] }}</td>
                                            <td>
                                                @if(!empty($teacherData['status_kehadiran']) && $teacherData['status_kehadiran'] !== '-')
                                                    <span class="badge bg-{{ $teacherData['status_kehadiran_badge'] ?? 'secondary' }}">{{ $teacherData['status_kehadiran'] }}</span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ number_format($teacherData['total_pertemuan']) }}</strong></td>
                                            <td class="text-primary"><strong>{{ number_format($teacherData['total_record']) }}</strong></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info btn-detail-teacher" 
                                                        data-teacher-id="{{ $teacherData['id'] }}"
                                                        data-teacher-name="{{ $teacherData['nama'] }}"
                                                        data-date-from="{{ $dateFrom }}"
                                                        data-date-to="{{ $dateTo }}">
                                                    <i class="bx bx-detail me-1"></i>Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div id="teacherPaginationContainer" class="card-footer" style="display: none;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center mb-0" id="teacherPagination">
                                    <!-- Pagination will be generated by JavaScript -->
                                </ul>
                            </nav>
                            <div class="text-center mt-2">
                                <small class="text-muted" id="paginationInfo">
                                    <!-- Info will be updated by JavaScript -->
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($reportType == 'student')
        <!-- Student Report -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Laporan Per Siswa</h4>
                        @php
                            $selectedClassId = request('class_id');
                            $currentPage = (int) request('page', 1);
                            $perPage = 10;
                            
                            $studentsQuery = \App\Models\Student::with(['user', 'classroom']);
                            
                            // Apply class filter if selected
                            if ($selectedClassId) {
                                $studentsQuery->where('class_id', $selectedClassId);
                            }
                            
                            // Get total count for pagination
                            $totalStudents = $studentsQuery->count();
                            $totalPages = ceil($totalStudents / $perPage);
                            
                            // Apply pagination
                            $students = $studentsQuery->skip(($currentPage - 1) * $perPage)->take($perPage)->get();
                        @endphp
                        @if($selectedClassId)
                            @php
                                $selectedClass = \App\Models\Classroom::find($selectedClassId);
                            @endphp
                            <div class="alert alert-info mb-3">
                                <i class="bx bx-info-circle me-2"></i>
                                Menampilkan data siswa dari kelas: <strong>{{ $selectedClass ? $selectedClass->grade . ' - ' . $selectedClass->name : 'Kelas tidak ditemukan' }}</strong>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>NIS</th>
                                        <th>Kelas</th>
                                        <th>Status Kehadiran</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($students as $student)
                                        @php
                                            $studentAttendance = \App\Models\Attendance::where(
                                                'student_id',
                                                $student->user_id,
                                            )
                                                ->whereBetween('created_at', [$dateFromCarbon, $dateToCarbon])
                                                ->get();

                                            $studentPresent = $studentAttendance->where('status', 'H')->count();
                                            $studentLate = $studentAttendance->where('status', 'T')->count();
                                            $studentAbsent = $studentAttendance->where('status', 'A')->count();
                                            $studentTotal = $studentAttendance->count();
                                            $studentPercentage =
                                                $studentTotal > 0
                                                    ? round(($studentPresent / $studentTotal) * 100, 2)
                                                    : 0;
                                            
                                            // Hitung status kehadiran dari student_presences
                                            $presences = \App\Services\StudentPresenceService::getPresenceStatusForRange(
                                                $student->user_id,
                                                $dateFromCarbon->toDateString(),
                                                $dateToCarbon->toDateString()
                                            );
                                            
                                            // Cek apakah ada presences dengan approval_count dan rejection_count (mixed approval)
                                            // Mixed approval terjadi jika:
                                            // 1. Ada presence dengan approval_count > 0 DAN rejection_count > 0 (satu hari dengan mixed)
                                            // 2. Atau ada beberapa presences dengan approval_count > 0 dan beberapa dengan rejection_count > 0
                                            $hasApproval = $presences->filter(function($p) {
                                                return $p->approval_count > 0;
                                            })->count() > 0;
                                            $hasRejection = $presences->filter(function($p) {
                                                return $p->rejection_count > 0;
                                            })->count() > 0;
                                            $hasMixedInSingleDay = $presences->filter(function($p) {
                                                return $p->approval_count > 0 && $p->rejection_count > 0;
                                            })->count() > 0;
                                            
                                            $hasMixedApproval = ($hasApproval && $hasRejection) || $hasMixedInSingleDay;
                                            
                                            // Jika ada mixed approval, tampilkan format "4 | 2"
                                            if ($hasMixedApproval) {
                                                $totalApproval = $presences->sum('approval_count');
                                                $totalRejection = $presences->sum('rejection_count');
                                                $statusKehadiranHtml = '<span class="text-success fw-bold">' . $totalApproval . '</span> | <span class="text-danger fw-bold">' . $totalRejection . '</span>';
                                            } else {
                                                $hadirCount = $presences->where('status', 'H')->count();
                                                $alfaCount = $presences->where('status', 'A')->count();
                                                $izinCount = $presences->where('status', 'I')->count();
                                                $sakitCount = $presences->where('status', 'S')->count();
                                                
                                                // Tentukan status dominan berdasarkan prioritas: Alfa > Sakit > Izin > Hadir
                                                // Note: Jika semua approve, status sudah menjadi 'H' (Hadir) di observer/service
                                                $statusKehadiranText = '-';
                                                $statusKehadiranBadge = 'secondary';
                                                
                                                if ($alfaCount > 0) {
                                                    $statusKehadiranText = 'Alfa';
                                                    $statusKehadiranBadge = 'danger';
                                                } elseif ($sakitCount > 0) {
                                                    $statusKehadiranText = 'Sakit';
                                                    $statusKehadiranBadge = 'warning';
                                                } elseif ($izinCount > 0) {
                                                    $statusKehadiranText = 'Izin';
                                                    $statusKehadiranBadge = 'info';
                                                } elseif ($hadirCount > 0) {
                                                    $statusKehadiranText = 'Hadir';
                                                    $statusKehadiranBadge = 'success';
                                                }
                                                
                                                $statusKehadiranHtml = '<span class="badge bg-' . $statusKehadiranBadge . '">' . $statusKehadiranText . '</span>';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $student->user->full_name }}</td>
                                            <td>{{ $student->nis }}</td>
                                            <td>{{ $student->classroom->grade }} - {{ $student->classroom->name }}</td>
                                            <td>
                                                {!! $statusKehadiranHtml ?? '<span class="badge bg-secondary">-</span>' !!}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" onclick="showStudentDetail({{ $student->user_id }}, '{{ $dateFromCarbon->format('Y-m-d') }}', '{{ $dateToCarbon->format('Y-m-d') }}')">
                                                    <i class="bx bx-detail"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    @if($selectedClassId)
                                                        Tidak ada siswa ditemukan untuk kelas yang dipilih dalam periode tanggal ini.
                                                    @else
                                                        Tidak ada data siswa ditemukan.
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($totalPages > 1)
                            <div class="card-footer">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mb-0">
                                        @if($currentPage > 1)
                                            <li class="page-item">
                                                <a class="page-link" href="{{ route('admin.laporan', array_merge(request()->all(), ['page' => $currentPage - 1])) }}">
                                                    <i class="bx bx-chevron-left"></i> Sebelumnya
                                                </a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link"><i class="bx bx-chevron-left"></i> Sebelumnya</span>
                                            </li>
                                        @endif
                                        
                                        @php
                                            $startPage = max(1, $currentPage - 2);
                                            $endPage = min($totalPages, $currentPage + 2);
                                        @endphp
                                        
                                        @if($startPage > 1)
                                            <li class="page-item">
                                                <a class="page-link" href="{{ route('admin.laporan', array_merge(request()->all(), ['page' => 1])) }}">1</a>
                                            </li>
                                            @if($startPage > 2)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                        @endif
                                        
                                        @for($i = $startPage; $i <= $endPage; $i++)
                                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                                <a class="page-link" href="{{ route('admin.laporan', array_merge(request()->all(), ['page' => $i])) }}">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        
                                        @if($endPage < $totalPages)
                                            @if($endPage < $totalPages - 1)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                            <li class="page-item">
                                                <a class="page-link" href="{{ route('admin.laporan', array_merge(request()->all(), ['page' => $totalPages])) }}">{{ $totalPages }}</a>
                                            </li>
                                        @endif
                                        
                                        @if($currentPage < $totalPages)
                                            <li class="page-item">
                                                <a class="page-link" href="{{ route('admin.laporan', array_merge(request()->all(), ['page' => $currentPage + 1])) }}">
                                                    Selanjutnya <i class="bx bx-chevron-right"></i>
                                                </a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">Selanjutnya <i class="bx bx-chevron-right"></i></span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        Menampilkan {{ (($currentPage - 1) * $perPage) + 1 }} - {{ min($currentPage * $perPage, $totalStudents) }} dari {{ number_format($totalStudents) }} siswa
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @elseif($reportType == 'class')
        <!-- Class Report -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Laporan Per Kelas</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kelas</th>
                                        <th>Total Siswa</th>
                                        <th>Total Record</th>
                                        <th>Hadir</th>
                                        <th>Terlambat</th>
                                        <th>Absen</th>
                                        <th>Persentase Hadir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (\App\Models\Classroom::with('students')->get() as $class)
                                        @php
                                            $classAttendance = \App\Models\Attendance::whereHas(
                                                'classSession.timetable.classSubject',
                                                function ($q) use ($class) {
                                                    $q->where('class_id', $class->id);
                                                },
                                            )
                                                ->whereBetween('created_at', [$dateFromCarbon, $dateToCarbon])
                                                ->get();

                                            $classPresent = $classAttendance->where('status', 'H')->count();
                                            $classLate = $classAttendance->where('status', 'T')->count();
                                            $classAbsent = $classAttendance->where('status', 'A')->count();
                                            $classTotal = $classAttendance->count();
                                            $classPercentage =
                                                $classTotal > 0 ? round(($classPresent / $classTotal) * 100, 2) : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $class->grade }} - {{ $class->name }}</td>
                                            <td>{{ $class->students->count() }}</td>
                                            <td>{{ number_format($classTotal) }}</td>
                                            <td class="text-success">{{ number_format($classPresent) }}</td>
                                            <td class="text-warning">{{ number_format($classLate) }}</td>
                                            <td class="text-danger">{{ number_format($classAbsent) }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $classPercentage >= 80 ? 'bg-success' : ($classPercentage >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $classPercentage }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Detail Guru -->
    <div class="modal fade" id="teacherDetailModal" tabindex="-1" aria-labelledby="teacherDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teacherDetailModalLabel">
                        <i class="bx bx-detail me-2"></i>Detail Laporan Guru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="teacherDetailLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat data detail...</p>
                    </div>
                    <div id="teacherDetailContent" style="display: none;">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary mb-3">
                                            <i class="bx bx-info-circle me-2"></i>Informasi Guru
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-2"><strong>Nama:</strong> <span id="detailTeacherName"></span></p>
                                                <p class="mb-2"><strong>NIP:</strong> <span id="detailTeacherNip"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-2"><strong>Periode:</strong> <span id="detailPeriod"></span></p>
                                                <p class="mb-2"><strong>Total Pertemuan:</strong> <span id="detailTotalPertemuan" class="text-primary fw-bold"></span></p>
                                                <p class="mb-2"><strong>Total Record:</strong> <span id="detailTotalRecord" class="text-success fw-bold"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success-subtle">
                                        <h6 class="card-title mb-0 text-success">
                                            <i class="bx bx-check-circle me-2"></i>Kelas yang Dimasuki
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light sticky-top">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Mata Pelajaran</th>
                                                        <th>Kelas</th>
                                                        <th>Grade</th>
                                                        <th>Tanggal</th>
                                                        <th>Jam</th>
                                                        <th>Total Record</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="detailClassesAttended">
                                                    <!-- Data akan diisi via JavaScript -->
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-3">
                                            <p class="mb-0"><strong>Total:</strong> <span id="detailTotalAttended" class="text-success fw-bold">0</span> kelas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-danger">
                                    <div class="card-header bg-danger-subtle">
                                        <h6 class="card-title mb-0 text-danger">
                                            <i class="bx bx-x-circle me-2"></i>Kelas yang Tidak Dimasuki
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light sticky-top">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Mata Pelajaran</th>
                                                        <th>Kelas</th>
                                                        <th>Grade</th>
                                                        <th>Tanggal</th>
                                                        <th>Jam</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="detailClassesNotAttended">
                                                    <!-- Data akan diisi via JavaScript -->
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-3">
                                            <p class="mb-0"><strong>Total:</strong> <span id="detailTotalNotAttended" class="text-danger fw-bold">0</span> kelas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Siswa -->
    <div class="modal fade" id="studentDetailModal" tabindex="-1" aria-labelledby="studentDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentDetailModalLabel">Detail Absensi Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="studentDetailContent">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // Pass data from Laravel to JavaScript
        window.allTeachersData = @json($teachersWithStats ?? []);
        window.currentPage = {{ $currentPage ?? 1 }};
        window.perPage = {{ $perPage ?? 10 }};
        window.filteredTeachers = window.allTeachersData;
        window.laporanUrl = '{{ route('admin.laporan') }}';
        window.laporanExportUrl = '{{ route('admin.laporan.export') }}';
        window.laporanTeacherDetailUrl = '{{ route('admin.laporan.teacher-detail') }}';
        window.laporanStudentDetailUrl = '{{ route('admin.laporan.student-detail') }}';
        window.dateFrom = '{{ $dateFrom ?? '' }}';
        window.dateTo = '{{ $dateTo ?? '' }}';
    </script>
    @vite(['resources/js/admin/laporan.js'])
@endsection
