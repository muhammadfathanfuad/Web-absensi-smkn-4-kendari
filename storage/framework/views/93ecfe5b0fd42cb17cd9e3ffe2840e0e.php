<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', [
        'title' => 'Admin',
        'subtitle' => 'Laporan Kehadiran',
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('admin.laporan')); ?>" id="filterForm">
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
                                            <?php echo e(request('report_type', 'teacher') == 'teacher' ? 'checked' : ''); ?>>
                                        <label class="btn btn-outline-primary" for="report_teacher">Per Guru</label>

                                        <input type="radio" class="btn-check" name="report_type" id="report_student" value="student" 
                                            <?php echo e(request('report_type') == 'student' ? 'checked' : ''); ?>>
                                        <label class="btn btn-outline-primary" for="report_student">Per Siswa</label>

                                        <input type="radio" class="btn-check" name="report_type" id="report_class" value="class" 
                                            <?php echo e(request('report_type') == 'class' ? 'checked' : ''); ?>>
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
                                                value="<?php echo e(request('date_from', \App\Services\TimeOverrideService::now()->format('Y-m-d'))); ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="date_to" class="form-label">Sampai Tanggal</label>
                                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                                value="<?php echo e(request('date_to', \App\Services\TimeOverrideService::now()->format('Y-m-d'))); ?>">
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
                                    <div class="row mt-3" id="classFilterSection" style="display: <?php echo e(request('report_type') == 'student' ? 'block' : 'none'); ?>;">
                                        <div class="col-md-6">
                                            <label for="class_id" class="form-label">
                                                <i class="bx bx-group me-2"></i>Filter Kelas
                                            </label>
                                            <select class="form-select" id="class_id" name="class_id">
                                                <option value="">Semua Kelas</option>
                                                <?php if(isset($classes) && $classes->count() > 0): ?>
                                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                                                            <?php echo e($class->grade); ?> - <?php echo e($class->name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
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

    <?php
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
    ?>

    <!-- Period Information -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bx bx-info-circle me-2"></i>
                <strong>Periode Laporan:</strong> 
                <?php echo e(\Carbon\Carbon::parse($dateFrom)->format('d M Y')); ?> - <?php echo e(\Carbon\Carbon::parse($dateTo)->format('d M Y')); ?>

                <span class="badge bg-primary ms-2"><?php echo e($reportLabels[$reportType] ?? 'Laporan'); ?></span>
            </div>
        </div>
    </div>

    <?php if($reportType == 'teacher'): ?>
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
                                    <?php
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
                                    ?>
                                    <script>
                                        // Store all teachers data for client-side processing
                                        window.allTeachersData = <?php echo json_encode($teachersWithStats, 15, 512) ?>;
                                        window.currentPage = <?php echo e($currentPage); ?>;
                                        window.perPage = <?php echo e($perPage); ?>;
                                        window.filteredTeachers = window.allTeachersData;
                                    </script>
                                    
                                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $teacherData = $teachersWithStats[$index] ?? null;
                                            if (!$teacherData) continue;
                                        ?>
                                        <tr data-teacher-id="<?php echo e($teacherData['id']); ?>">
                                            <td><?php echo e($teacherData['nama']); ?></td>
                                            <td><?php echo e($teacherData['nip']); ?></td>
                                            <td>
                                                <?php if(!empty($teacherData['status_kehadiran']) && $teacherData['status_kehadiran'] !== '-'): ?>
                                                    <span class="badge bg-<?php echo e($teacherData['status_kehadiran_badge'] ?? 'secondary'); ?>"><?php echo e($teacherData['status_kehadiran']); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong><?php echo e(number_format($teacherData['total_pertemuan'])); ?></strong></td>
                                            <td class="text-primary"><strong><?php echo e(number_format($teacherData['total_record'])); ?></strong></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info btn-detail-teacher" 
                                                        data-teacher-id="<?php echo e($teacherData['id']); ?>"
                                                        data-teacher-name="<?php echo e($teacherData['nama']); ?>"
                                                        data-date-from="<?php echo e($dateFrom); ?>"
                                                        data-date-to="<?php echo e($dateTo); ?>">
                                                    <i class="bx bx-detail me-1"></i>Detail
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    <?php elseif($reportType == 'student'): ?>
        <!-- Student Report -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Laporan Per Siswa</h4>
                        <?php
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
                        ?>
                        <?php if($selectedClassId): ?>
                            <?php
                                $selectedClass = \App\Models\Classroom::find($selectedClassId);
                            ?>
                            <div class="alert alert-info mb-3">
                                <i class="bx bx-info-circle me-2"></i>
                                Menampilkan data siswa dari kelas: <strong><?php echo e($selectedClass ? $selectedClass->grade . ' - ' . $selectedClass->name : 'Kelas tidak ditemukan'); ?></strong>
                            </div>
                        <?php endif; ?>
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
                                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
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
                                        ?>
                                        <tr>
                                            <td><?php echo e($student->user->full_name); ?></td>
                                            <td><?php echo e($student->nis); ?></td>
                                            <td><?php echo e($student->classroom->grade); ?> - <?php echo e($student->classroom->name); ?></td>
                                            <td>
                                                <?php echo $statusKehadiranHtml ?? '<span class="badge bg-secondary">-</span>'; ?>

                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" onclick="showStudentDetail(<?php echo e($student->user_id); ?>, '<?php echo e($dateFromCarbon->format('Y-m-d')); ?>', '<?php echo e($dateToCarbon->format('Y-m-d')); ?>')">
                                                    <i class="bx bx-detail"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    <?php if($selectedClassId): ?>
                                                        Tidak ada siswa ditemukan untuk kelas yang dipilih dalam periode tanggal ini.
                                                    <?php else: ?>
                                                        Tidak ada data siswa ditemukan.
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if($totalPages > 1): ?>
                            <div class="card-footer">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center mb-0">
                                        <?php if($currentPage > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo e(route('admin.laporan', array_merge(request()->all(), ['page' => $currentPage - 1]))); ?>">
                                                    <i class="bx bx-chevron-left"></i> Sebelumnya
                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item disabled">
                                                <span class="page-link"><i class="bx bx-chevron-left"></i> Sebelumnya</span>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <?php
                                            $startPage = max(1, $currentPage - 2);
                                            $endPage = min($totalPages, $currentPage + 2);
                                        ?>
                                        
                                        <?php if($startPage > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo e(route('admin.laporan', array_merge(request()->all(), ['page' => 1]))); ?>">1</a>
                                            </li>
                                            <?php if($startPage > 2): ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <?php for($i = $startPage; $i <= $endPage; $i++): ?>
                                            <li class="page-item <?php echo e($i == $currentPage ? 'active' : ''); ?>">
                                                <a class="page-link" href="<?php echo e(route('admin.laporan', array_merge(request()->all(), ['page' => $i]))); ?>"><?php echo e($i); ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        
                                        <?php if($endPage < $totalPages): ?>
                                            <?php if($endPage < $totalPages - 1): ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            <?php endif; ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo e(route('admin.laporan', array_merge(request()->all(), ['page' => $totalPages]))); ?>"><?php echo e($totalPages); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <?php if($currentPage < $totalPages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo e(route('admin.laporan', array_merge(request()->all(), ['page' => $currentPage + 1]))); ?>">
                                                    Selanjutnya <i class="bx bx-chevron-right"></i>
                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">Selanjutnya <i class="bx bx-chevron-right"></i></span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        Menampilkan <?php echo e((($currentPage - 1) * $perPage) + 1); ?> - <?php echo e(min($currentPage * $perPage, $totalStudents)); ?> dari <?php echo e(number_format($totalStudents)); ?> siswa
                                    </small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif($reportType == 'class'): ?>
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
                                    <?php $__currentLoopData = \App\Models\Classroom::with('students')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
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
                                        ?>
                                        <tr>
                                            <td><?php echo e($class->grade); ?> - <?php echo e($class->name); ?></td>
                                            <td><?php echo e($class->students->count()); ?></td>
                                            <td><?php echo e(number_format($classTotal)); ?></td>
                                            <td class="text-success"><?php echo e(number_format($classPresent)); ?></td>
                                            <td class="text-warning"><?php echo e(number_format($classLate)); ?></td>
                                            <td class="text-danger"><?php echo e(number_format($classAbsent)); ?></td>
                                            <td>
                                                <span
                                                    class="badge <?php echo e($classPercentage >= 80 ? 'bg-success' : ($classPercentage >= 60 ? 'bg-warning' : 'bg-danger')); ?>">
                                                    <?php echo e($classPercentage); ?>%
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        // Export function with simplified approach
        function exportReport(format = 'pdf') {
            try {
                // Get current report type from checked radio button
                var checkedRadio = document.querySelector('input[name="report_type"]:checked');
                if (!checkedRadio) {
                    showAlert('Pilih jenis laporan terlebih dahulu', 'danger');
                    return;
                }

                // Get current date filters
                var dateFrom = document.getElementById('date_from').value;
                var dateTo = document.getElementById('date_to').value;
                
                // Get class filter value if exists
                var classId = document.getElementById('class_id') ? document.getElementById('class_id').value : '';
                
                // Build clean export URL with all necessary parameters
                var exportUrl = '<?php echo e(route('admin.laporan.export')); ?>?export=1&format=' + format + '&report_type=' + checkedRadio.value;
                if (dateFrom) exportUrl += '&date_from=' + dateFrom;
                if (dateTo) exportUrl += '&date_to=' + dateTo;
                if (classId && checkedRadio.value === 'student') exportUrl += '&class_id=' + classId;

                // Show loading indicator
                showExportLoading(format);

                // Create a hidden iframe to handle the download
                var iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = exportUrl;
                document.body.appendChild(iframe);

                // Clean up after download starts
                setTimeout(function() {
                    document.body.removeChild(iframe);
                    hideExportLoading();
                    showAlert('File export dimulai. Silakan tunggu beberapa saat.', 'info');
                }, 1000);

                // Fallback if iframe doesn't work
                setTimeout(function() {
                    console.log('Iframe method may have failed, trying fallback...');
                    tryFallbackExport(format);
                }, 3000);

            } catch (error) {
                console.error('Export error:', error);
                hideExportLoading();
                showAlert('Terjadi kesalahan saat export: ' + error.message, 'danger');
            }
        }


        // Make functions globally available
        window.exportReport = exportReport;

        // Fallback export method using direct link
        function tryFallbackExport(format = 'pdf') {
            try {
                // Get current report type from checked radio button
                var checkedRadio = document.querySelector('input[name="report_type"]:checked');
                if (!checkedRadio) {
                    showAlert('Pilih jenis laporan terlebih dahulu', 'danger');
                    return;
                }

                // Get current date filters
                var dateFrom = document.getElementById('date_from').value;
                var dateTo = document.getElementById('date_to').value;

                // Get class filter value if exists
                var classId = document.getElementById('class_id') ? document.getElementById('class_id').value : '';
                
                // Build clean export URL with all necessary parameters
                var exportUrl = '<?php echo e(route('admin.laporan.export')); ?>?export=1&format=' + format + '&report_type=' + checkedRadio.value;
                if (dateFrom) exportUrl += '&date_from=' + dateFrom;
                if (dateTo) exportUrl += '&date_to=' + dateTo;
                if (classId && checkedRadio.value === 'student') exportUrl += '&class_id=' + classId;
                
                // Show loading again
                showExportLoading(format);
                
                // Use window.location as ultimate fallback
                window.location.href = exportUrl;
                
                // Hide loading after a short delay
                setTimeout(function() {
                    hideExportLoading();
                    showAlert('File export dimulai', 'info');
                }, 2000);

            } catch (error) {
                console.error('Fallback export error:', error);
                hideExportLoading();
                showAlert('Gagal mengexport data. Silakan coba lagi atau hubungi administrator.', 'danger');
            }
        }


        // Show loading indicator
        function showExportLoading(format) {
            var formatText = format === 'pdf' ? 'PDF' : 'File';
            var loadingHtml = `
            <div id="exportLoading" class="alert alert-info alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div>
                        <strong>Sedang memproses export ${formatText}...</strong>
                        <br>
                        <small>File akan segera diunduh</small>
                    </div>
                </div>
            </div>
        `;

            // Remove existing loading if any
            var existingLoading = document.getElementById('exportLoading');
            if (existingLoading) {
                existingLoading.remove();
            }

            // Add new loading indicator
            document.body.insertAdjacentHTML('beforeend', loadingHtml);
        }

        // Hide loading indicator
        function hideExportLoading() {
            var loadingElement = document.getElementById('exportLoading');
            if (loadingElement) {
                loadingElement.classList.remove('show');
                setTimeout(function() {
                    loadingElement.remove();
                }, 150);
            }
        }

        // Show alert message
        function showAlert(message, type = 'info') {
            var alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
            document.body.insertAdjacentHTML('beforeend', alertHtml);

            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    if (alert.classList.contains('show')) {
                        alert.classList.remove('show');
                        setTimeout(function() {
                            alert.remove();
                        }, 150);
                    }
                });
            }, 5000);
        }


        // Show/hide class filter based on report type
        function toggleClassFilter() {
            var reportType = document.querySelector('input[name="report_type"]:checked');
            var classFilterSection = document.getElementById('classFilterSection');
            
            if (reportType && reportType.value === 'student') {
                if (classFilterSection) {
                    classFilterSection.style.display = 'block';
                }
            } else {
                if (classFilterSection) {
                    classFilterSection.style.display = 'none';
                }
            }
        }

        // Auto-submit form when report type changes (radio buttons)
        document.querySelectorAll('input[name="report_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    // Toggle class filter visibility
                    toggleClassFilter();
                    
                    // Get current date filters
                    var dateFrom = document.getElementById('date_from').value;
                    var dateTo = document.getElementById('date_to').value;
                    
                    // Get class filter value if exists
                    var classId = document.getElementById('class_id') ? document.getElementById('class_id').value : '';
                    
                    // Build URL with report type and date filters
                    var targetUrl = '<?php echo e(route('admin.laporan')); ?>?report_type=' + this.value;
                    if (dateFrom) targetUrl += '&date_from=' + dateFrom;
                    if (dateTo) targetUrl += '&date_to=' + dateTo;
                    if (classId && this.value === 'student') targetUrl += '&class_id=' + classId;

                    // Navigate to the correct URL
                    window.location.href = targetUrl;
                }
            });
        });
        
        // Initialize class filter visibility on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleClassFilter();
        });

        // Date range preset functions
        function setDateRange(range) {
            var today = new Date();
            var dateFrom, dateTo;
            
            switch(range) {
                case 'today':
                    dateFrom = dateTo = today.toISOString().split('T')[0];
                    break;
                case 'week':
                    var startOfWeek = new Date(today);
                    startOfWeek.setDate(today.getDate() - today.getDay() + 1); // Monday
                    dateFrom = startOfWeek.toISOString().split('T')[0];
                    dateTo = today.toISOString().split('T')[0];
                    break;
                case 'month':
                    var startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                    dateFrom = startOfMonth.toISOString().split('T')[0];
                    dateTo = today.toISOString().split('T')[0];
                    break;
            }
            
            document.getElementById('date_from').value = dateFrom;
            document.getElementById('date_to').value = dateTo;
        }

        // Reset date filter
        function resetDateFilter() {
            var today = new Date();
            
            document.getElementById('date_from').value = today.toISOString().split('T')[0];
            document.getElementById('date_to').value = today.toISOString().split('T')[0];
        }

        // Validate date range
        function validateDateRange() {
            var dateFrom = document.getElementById('date_from').value;
            var dateTo = document.getElementById('date_to').value;
            
            if (dateFrom && dateTo && dateFrom > dateTo) {
                alert('Tanggal "Dari" tidak boleh lebih besar dari tanggal "Sampai"');
                return false;
            }
            return true;
        }

        // Add validation to form submission
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            if (!validateDateRange()) {
                e.preventDefault();
                return false;
            }
        });

        // Ensure form action is always correct on page load
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('filterForm');
            if (!form) {
                console.error('Filter form not found on page load');
                return;
            }

            // Teacher search functionality with client-side pagination
            var teacherSearchInput = document.getElementById('teacherSearchInput');
            if (teacherSearchInput && window.allTeachersData) {
                
                // Function to render teachers table
                function renderTeachers(data, page, perPage) {
                    var tbody = document.getElementById('teacherTableBody');
                    tbody.innerHTML = '';
                    
                    var start = (page - 1) * perPage;
                    var end = start + perPage;
                    var pageData = data.slice(start, end);
                    
                    if (pageData.length === 0 && data.length > 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><div class="text-muted">Halaman tidak ditemukan.</div></td></tr>';
                    } else if (pageData.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><div class="text-muted d-flex flex-column align-items-center"><iconify-icon icon="solar:file-search-outline" class="fs-48 mb-2"></iconify-icon>Tidak ada hasil ditemukan.</div></td></tr>';
                    } else {
                        pageData.forEach(function(teacher) {
                            var row = document.createElement('tr');
                            row.setAttribute('data-teacher-id', teacher.id);
                            
                            // Build status badge (single status based on priority)
                            var statusHtml = '';
                            if (teacher.status_kehadiran && teacher.status_kehadiran !== '-') {
                                var badgeClass = teacher.status_kehadiran_badge || 'secondary';
                                statusHtml = `<span class="badge bg-${badgeClass}">${teacher.status_kehadiran}</span>`;
                            } else {
                                statusHtml = '<span class="badge bg-secondary">-</span>';
                            }
                            
                            row.innerHTML = `
                                <td>${teacher.nama}</td>
                                <td>${teacher.nip}</td>
                                <td>${statusHtml}</td>
                                <td><strong>${teacher.total_pertemuan.toLocaleString()}</strong></td>
                                <td class="text-primary"><strong>${teacher.total_record.toLocaleString()}</strong></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info btn-detail-teacher" 
                                            data-teacher-id="${teacher.id}"
                                            data-teacher-name="${teacher.nama}"
                                            data-date-from="<?php echo e($dateFrom); ?>"
                                            data-date-to="<?php echo e($dateTo); ?>">
                                        <i class="bx bx-detail me-1"></i>Detail
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    }
                }
                
                // Function to render pagination
                function renderPagination(data, page, perPage) {
                    var totalPages = Math.ceil(data.length / perPage);
                    var paginationContainer = document.getElementById('teacherPaginationContainer');
                    var paginationUl = document.getElementById('teacherPagination');
                    var paginationInfo = document.getElementById('paginationInfo');
                    
                    if (totalPages <= 1) {
                        paginationContainer.style.display = 'none';
                        return;
                    }
                    
                    paginationContainer.style.display = 'block';
                    paginationUl.innerHTML = '';
                    
                    // Previous button
                    var prevLi = document.createElement('li');
                    prevLi.className = page > 1 ? 'page-item' : 'page-item disabled';
                    prevLi.innerHTML = page > 1 ? 
                        `<a class="page-link" href="#" onclick="goToPage(${page - 1}); return false;"><i class="bx bx-chevron-left"></i> Sebelumnya</a>` :
                        `<span class="page-link"><i class="bx bx-chevron-left"></i> Sebelumnya</span>`;
                    paginationUl.appendChild(prevLi);
                    
                    // Page numbers
                    var startPage = Math.max(1, page - 2);
                    var endPage = Math.min(totalPages, page + 2);
                    
                    if (startPage > 1) {
                        var firstLi = document.createElement('li');
                        firstLi.className = 'page-item';
                        firstLi.innerHTML = `<a class="page-link" href="#" onclick="goToPage(1); return false;">1</a>`;
                        paginationUl.appendChild(firstLi);
                        if (startPage > 2) {
                            var ellipsisLi = document.createElement('li');
                            ellipsisLi.className = 'page-item disabled';
                            ellipsisLi.innerHTML = '<span class="page-link">...</span>';
                            paginationUl.appendChild(ellipsisLi);
                        }
                    }
                    
                    for (var i = startPage; i <= endPage; i++) {
                        var li = document.createElement('li');
                        li.className = 'page-item' + (i === page ? ' active' : '');
                        li.innerHTML = `<a class="page-link" href="#" onclick="goToPage(${i}); return false;">${i}</a>`;
                        paginationUl.appendChild(li);
                    }
                    
                    if (endPage < totalPages) {
                        if (endPage < totalPages - 1) {
                            var ellipsisLi = document.createElement('li');
                            ellipsisLi.className = 'page-item disabled';
                            ellipsisLi.innerHTML = '<span class="page-link">...</span>';
                            paginationUl.appendChild(ellipsisLi);
                        }
                        var lastLi = document.createElement('li');
                        lastLi.className = 'page-item';
                        lastLi.innerHTML = `<a class="page-link" href="#" onclick="goToPage(${totalPages}); return false;">${totalPages}</a>`;
                        paginationUl.appendChild(lastLi);
                    }
                    
                    // Next button
                    var nextLi = document.createElement('li');
                    nextLi.className = page < totalPages ? 'page-item' : 'page-item disabled';
                    nextLi.innerHTML = page < totalPages ? 
                        `<a class="page-link" href="#" onclick="goToPage(${page + 1}); return false;">Selanjutnya <i class="bx bx-chevron-right"></i></a>` :
                        `<span class="page-link">Selanjutnya <i class="bx bx-chevron-right"></i></span>`;
                    paginationUl.appendChild(nextLi);
                    
                    // Info text
                    var start = (page - 1) * perPage + 1;
                    var end = Math.min(page * perPage, data.length);
                    paginationInfo.textContent = `Menampilkan ${start} - ${end} dari ${data.length} guru`;
                }
                
                // Global function to go to page
                window.goToPage = function(page) {
                    renderTeachers(window.filteredTeachers, page, window.perPage);
                    renderPagination(window.filteredTeachers, page, window.perPage);
                };
                
                // Search functionality
                teacherSearchInput.addEventListener('input', function() {
                    var filter = this.value.toLowerCase();
                    
                    if (filter === '') {
                        window.filteredTeachers = window.allTeachersData;
                    } else {
                        window.filteredTeachers = window.allTeachersData.filter(function(teacher) {
                            return teacher.nama.toLowerCase().indexOf(filter) > -1 || 
                                   teacher.nip.toLowerCase().indexOf(filter) > -1;
                        });
                    }
                    
                    // Reset to page 1 after search
                    window.currentPage = 1;
                    renderTeachers(window.filteredTeachers, 1, window.perPage);
                    renderPagination(window.filteredTeachers, 1, window.perPage);
                });
                
                // Initial render
                renderTeachers(window.allTeachersData, window.currentPage, window.perPage);
                renderPagination(window.allTeachersData, window.currentPage, window.perPage);
            }

            // Handle detail button click (delegated event listener)
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-detail-teacher')) {
                    var button = e.target.closest('.btn-detail-teacher');
                    var teacherId = button.getAttribute('data-teacher-id');
                    var teacherName = button.getAttribute('data-teacher-name');
                    var dateFrom = button.getAttribute('data-date-from');
                    var dateTo = button.getAttribute('data-date-to');
                    
                    showTeacherDetail(teacherId, teacherName, dateFrom, dateTo);
                }
            });
        });

        // Function to show teacher detail
        function showTeacherDetail(teacherId, teacherName, dateFrom, dateTo) {
            var modal = new bootstrap.Modal(document.getElementById('teacherDetailModal'));
            modal.show();
            
            // Show loading, hide content
            document.getElementById('teacherDetailLoading').style.display = 'block';
            document.getElementById('teacherDetailContent').style.display = 'none';
            
            // Set teacher name in modal title
            document.getElementById('teacherDetailModalLabel').innerHTML = 
                '<i class="bx bx-detail me-2"></i>Detail Laporan: ' + teacherName;
            
            // Fetch detail data
            fetch('<?php echo e(route("admin.laporan.teacher-detail")); ?>?teacher_id=' + teacherId + '&date_from=' + dateFrom + '&date_to=' + dateTo, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Set basic info
                    document.getElementById('detailTeacherName').textContent = data.teacher.name || 'N/A';
                    document.getElementById('detailTeacherNip').textContent = data.teacher.nip || 'N/A';
                    document.getElementById('detailPeriod').textContent = 
                        new Date(dateFrom).toLocaleDateString('id-ID') + ' - ' + 
                        new Date(dateTo).toLocaleDateString('id-ID');
                    document.getElementById('detailTotalPertemuan').textContent = 
                        data.summary.total_pertemuan.toLocaleString();
                    document.getElementById('detailTotalRecord').textContent = 
                        data.summary.total_record.toLocaleString();
                    
                    // Render classes attended
                    var attendedTbody = document.getElementById('detailClassesAttended');
                    attendedTbody.innerHTML = '';
                    if (data.classes_attended && data.classes_attended.length > 0) {
                        data.classes_attended.forEach(function(item, index) {
                            var row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${item.subject_name || '-'}</td>
                                <td>${item.class_name || '-'}</td>
                                <td><span class="badge bg-info">${item.class_grade || '-'}</span></td>
                                <td>${item.date || '-'}</td>
                                <td>${item.time_range || (item.start_time + ' - ' + item.end_time) || '-'}</td>
                                <td><span class="badge bg-success">${item.total_record || 0}</span></td>
                            `;
                            attendedTbody.appendChild(row);
                        });
                        document.getElementById('detailTotalAttended').textContent = 
                            data.classes_attended.length.toLocaleString();
                    } else {
                        attendedTbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>';
                        document.getElementById('detailTotalAttended').textContent = '0';
                    }
                    
                    // Render classes not attended
                    var notAttendedTbody = document.getElementById('detailClassesNotAttended');
                    notAttendedTbody.innerHTML = '';
                    if (data.classes_not_attended && data.classes_not_attended.length > 0) {
                        data.classes_not_attended.forEach(function(item, index) {
                            var row = document.createElement('tr');
                            var dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${item.subject_name || '-'}</td>
                                <td>${item.class_name || '-'}</td>
                                <td><span class="badge bg-info">${item.class_grade || '-'}</span></td>
                                <td>${item.date || '-'}</td>
                                <td>${item.time_range || (item.start_time + ' - ' + item.end_time) || '-'}</td>
                            `;
                            notAttendedTbody.appendChild(row);
                        });
                        document.getElementById('detailTotalNotAttended').textContent = 
                            data.classes_not_attended.length.toLocaleString();
                    } else {
                        notAttendedTbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Tidak ada data</td></tr>';
                        document.getElementById('detailTotalNotAttended').textContent = '0';
                    }
                    
                    // Hide loading, show content
                    document.getElementById('teacherDetailLoading').style.display = 'none';
                    document.getElementById('teacherDetailContent').style.display = 'block';
                } else {
                    alert('Gagal memuat data detail: ' + (data.message || 'Terjadi kesalahan'));
                    modal.hide();
                }
            })
            .catch(error => {
                console.error('Error fetching teacher detail:', error);
                alert('Terjadi kesalahan saat memuat data detail');
                modal.hide();
            });
        }

        // Function to show student detail
        function showStudentDetail(studentId, dateFrom, dateTo) {
            var modal = new bootstrap.Modal(document.getElementById('studentDetailModal'));
            modal.show();
            
            // Show loading
            document.getElementById('studentDetailContent').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Memuat data detail...</p>
                </div>
            `;
            
            // Fetch detail data
            fetch('<?php echo e(route("admin.laporan.student-detail")); ?>?student_id=' + studentId + '&date_from=' + dateFrom + '&date_to=' + dateTo, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var html = `
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary mb-3">
                                            <i class="bx bx-info-circle me-2"></i>Informasi Siswa
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="mb-2"><strong>Nama:</strong> ${data.student.name || 'N/A'}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-2"><strong>NIS:</strong> ${data.student.nis || 'N/A'}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-2"><strong>Kelas:</strong> ${data.student.class || 'N/A'}</p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <p class="mb-0"><strong>Periode:</strong> ${new Date(data.date_from).toLocaleDateString('id-ID')} - ${new Date(data.date_to).toLocaleDateString('id-ID')}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="bx bx-calendar me-2"></i>Detail Absensi per Hari
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Search and Filter Controls -->
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                                                    <input type="text" id="studentDetailSearch" class="form-control" placeholder="Cari mata pelajaran...">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <select id="studentDetailSubjectFilter" class="form-select">
                                                    <option value="">Semua Mata Pelajaran</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="date" id="studentDetailDateFilter" class="form-control" placeholder="Filter tanggal...">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-secondary w-100" onclick="resetStudentDetailFilters()">
                                                    <i class="bx bx-refresh"></i> Reset
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light sticky-top">
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Total Record</th>
                                                        <th>Mata Pelajaran</th>
                                                        <th>Status Absensi</th>
                                                        <th>Waktu Masuk</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="studentDetailTableBody">
                    `;
                    
                    // Data will be rendered by renderStudentDetailTable function
                    
                    html += `
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Pagination -->
                                        <nav aria-label="Page navigation" class="mt-3">
                                            <ul class="pagination justify-content-center" id="studentDetailPagination">
                                                <!-- Pagination will be generated by JavaScript -->
                                            </ul>
                                        </nav>
                                        
                                        <div class="text-center mt-2">
                                            <small class="text-muted">
                                                Menampilkan <span id="studentDetailShowing">0</span> dari <span id="studentDetailTotal">0</span> data
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.getElementById('studentDetailContent').innerHTML = html;
                    
                    // Store original data for filtering
                    window.studentDetailAllData = data.daily_data;
                    window.studentDetailCurrentPage = 1;
                    window.studentDetailItemsPerPage = 10;
                    
                    // Initialize filters and pagination
                    initializeStudentDetailFilters();
                    renderStudentDetailTable();
                } else {
                    document.getElementById('studentDetailContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bx bx-error-circle me-2"></i>${data.message || 'Terjadi kesalahan saat mengambil data'}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('studentDetailContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bx bx-error-circle me-2"></i>Terjadi kesalahan saat mengambil data detail
                    </div>
                `;
            });
        }

        // Initialize filters for student detail
        function initializeStudentDetailFilters() {
            if (!window.studentDetailAllData) return;
            
            // Get unique subjects
            var subjects = new Set();
            window.studentDetailAllData.forEach(function(day) {
                day.subjects.forEach(function(subject) {
                    subjects.add(subject.subject_name);
                });
            });
            
            // Populate subject filter
            var subjectFilter = document.getElementById('studentDetailSubjectFilter');
            if (subjectFilter) {
                var currentValue = subjectFilter.value;
                subjectFilter.innerHTML = '<option value="">Semua Mata Pelajaran</option>';
                Array.from(subjects).sort().forEach(function(subject) {
                    var option = document.createElement('option');
                    option.value = subject;
                    option.textContent = subject;
                    if (subject === currentValue) {
                        option.selected = true;
                    }
                    subjectFilter.appendChild(option);
                });
            }
            
            // Add event listeners
            var searchInput = document.getElementById('studentDetailSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    window.studentDetailCurrentPage = 1;
                    renderStudentDetailTable();
                });
            }
            
            if (subjectFilter) {
                subjectFilter.addEventListener('change', function() {
                    window.studentDetailCurrentPage = 1;
                    renderStudentDetailTable();
                });
            }
            
            var dateFilter = document.getElementById('studentDetailDateFilter');
            if (dateFilter) {
                dateFilter.addEventListener('change', function() {
                    window.studentDetailCurrentPage = 1;
                    renderStudentDetailTable();
                });
            }
        }

        // Render student detail table with filters and pagination
        function renderStudentDetailTable() {
            if (!window.studentDetailAllData) return;
            
            var searchTerm = document.getElementById('studentDetailSearch')?.value.toLowerCase() || '';
            var subjectFilter = document.getElementById('studentDetailSubjectFilter')?.value || '';
            var dateFilter = document.getElementById('studentDetailDateFilter')?.value || '';
            
            // Flatten and filter data
            var allRows = [];
            window.studentDetailAllData.forEach(function(day) {
                day.subjects.forEach(function(subject, index) {
                    // Apply filters
                    var matchesSearch = !searchTerm || subject.subject_name.toLowerCase().includes(searchTerm);
                    var matchesSubject = !subjectFilter || subject.subject_name === subjectFilter;
                    var matchesDate = !dateFilter || day.date === dateFilter;
                    
                    if (matchesSearch && matchesSubject && matchesDate) {
                        allRows.push({
                            day: day,
                            subject: subject
                        });
                    }
                });
            });
            
            // Calculate pagination
            var totalItems = allRows.length;
            var totalPages = Math.ceil(totalItems / window.studentDetailItemsPerPage);
            var startIndex = (window.studentDetailCurrentPage - 1) * window.studentDetailItemsPerPage;
            var endIndex = Math.min(startIndex + window.studentDetailItemsPerPage, totalItems);
            var currentRows = allRows.slice(startIndex, endIndex);
            
            // Render table
            var tbody = document.getElementById('studentDetailTableBody');
            if (!tbody) return;
            
            tbody.innerHTML = '';
            
            if (currentRows.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bx bx-info-circle me-2"></i>Tidak ada data yang sesuai dengan filter
                        </td>
                    </tr>
                `;
            } else {
                // Calculate rowspan for each day in current page
                var dayRowspanMap = {};
                currentRows.forEach(function(row) {
                    if (!dayRowspanMap[row.day.date]) {
                        dayRowspanMap[row.day.date] = {
                            count: 0,
                            date_display: row.day.date_display,
                            total_record: row.day.total_record
                        };
                    }
                    dayRowspanMap[row.day.date].count++;
                });
                
                var currentDay = null;
                
                currentRows.forEach(function(row) {
                    var html = '<tr>';
                    var dayRowspan = dayRowspanMap[row.day.date].count;
                    
                    // Handle rowspan for date and total record
                    if (row.day.date !== currentDay) {
                        currentDay = row.day.date;
                        
                        html += `<td rowspan="${dayRowspan}" class="align-middle">${dayRowspanMap[row.day.date].date_display}</td>`;
                        html += `<td rowspan="${dayRowspan}" class="align-middle text-center">${dayRowspanMap[row.day.date].total_record}</td>`;
                    }
                    
                    html += `<td>${row.subject.subject_name}</td>`;
                    html += `<td><span class="badge bg-${row.subject.status_badge}">${row.subject.status_text}</span></td>`;
                    html += `<td>${row.subject.check_in_time || '-'}</td>`;
                    html += '</tr>';
                    
                    tbody.innerHTML += html;
                });
            }
            
            // Update pagination info
            var showingSpan = document.getElementById('studentDetailShowing');
            var totalSpan = document.getElementById('studentDetailTotal');
            if (showingSpan) {
                showingSpan.textContent = currentRows.length > 0 ? (startIndex + 1) + '-' + endIndex : '0';
            }
            if (totalSpan) {
                totalSpan.textContent = totalItems;
            }
            
            // Render pagination
            renderStudentDetailPagination(totalPages);
        }

        // Render pagination controls
        function renderStudentDetailPagination(totalPages) {
            var pagination = document.getElementById('studentDetailPagination');
            if (!pagination) return;
            
            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }
            
            var html = '';
            var currentPage = window.studentDetailCurrentPage;
            
            // Previous button
            html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changeStudentDetailPage(${currentPage - 1}); return false;">Previous</a>
            </li>`;
            
            // Page numbers
            var startPage = Math.max(1, currentPage - 2);
            var endPage = Math.min(totalPages, currentPage + 2);
            
            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="changeStudentDetailPage(1); return false;">1</a></li>`;
                if (startPage > 2) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }
            
            for (var i = startPage; i <= endPage; i++) {
                html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changeStudentDetailPage(${i}); return false;">${i}</a>
                </li>`;
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                html += `<li class="page-item"><a class="page-link" href="#" onclick="changeStudentDetailPage(${totalPages}); return false;">${totalPages}</a></li>`;
            }
            
            // Next button
            html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changeStudentDetailPage(${currentPage + 1}); return false;">Next</a>
            </li>`;
            
            pagination.innerHTML = html;
        }

        // Change page
        function changeStudentDetailPage(page) {
            if (!window.studentDetailAllData) return;
            
            var searchTerm = document.getElementById('studentDetailSearch')?.value.toLowerCase() || '';
            var subjectFilter = document.getElementById('studentDetailSubjectFilter')?.value || '';
            var dateFilter = document.getElementById('studentDetailDateFilter')?.value || '';
            
            // Calculate total pages
            var allRows = [];
            window.studentDetailAllData.forEach(function(day) {
                day.subjects.forEach(function(subject) {
                    var matchesSearch = !searchTerm || subject.subject_name.toLowerCase().includes(searchTerm);
                    var matchesSubject = !subjectFilter || subject.subject_name === subjectFilter;
                    var matchesDate = !dateFilter || day.date === dateFilter;
                    
                    if (matchesSearch && matchesSubject && matchesDate) {
                        allRows.push({ day: day, subject: subject });
                    }
                });
            });
            
            var totalPages = Math.ceil(allRows.length / window.studentDetailItemsPerPage);
            
            if (page >= 1 && page <= totalPages) {
                window.studentDetailCurrentPage = page;
                renderStudentDetailTable();
                
                // Scroll to top of table
                var tableContainer = document.querySelector('#studentDetailContent .table-responsive');
                if (tableContainer) {
                    tableContainer.scrollTop = 0;
                }
            }
        }

        // Reset filters
        function resetStudentDetailFilters() {
            var searchInput = document.getElementById('studentDetailSearch');
            var subjectFilter = document.getElementById('studentDetailSubjectFilter');
            var dateFilter = document.getElementById('studentDetailDateFilter');
            
            if (searchInput) searchInput.value = '';
            if (subjectFilter) subjectFilter.value = '';
            if (dateFilter) dateFilter.value = '';
            
            window.studentDetailCurrentPage = 1;
            renderStudentDetailTable();
        }
    </script>

    <style>
        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .search-box {
            width: 300px;
        }

        #teacherSearchInput {
            padding-right: 40px;
        }
        
        /* Horizontal scroll for report tabs */
        .report-tabs-wrapper {
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #dee2e6 #f8f9fa;
            width: 100%;
        }
        
        .report-tabs-wrapper::-webkit-scrollbar {
            height: 6px;
        }
        
        .report-tabs-wrapper::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 3px;
        }
        
        .report-tabs-wrapper::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 3px;
        }
        
        .report-tabs-wrapper::-webkit-scrollbar-thumb:hover {
            background: #adb5bd;
        }
        
        .report-tabs-wrapper .btn-group {
            display: flex;
            flex-wrap: nowrap;
            white-space: nowrap;
        }
        
        .report-tabs-wrapper .btn-group .btn {
            flex-shrink: 0;
            white-space: nowrap;
        }
        
        /* Mobile responsive */
        @media (max-width: 575.98px) {
            .report-header-controls {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }
            
            .report-label {
                width: 100%;
                text-align: center;
                margin-bottom: 0.5rem;
            }
            
            .report-header-controls .btn-group {
                width: 100%;
            }
            
            .report-header-controls .btn {
                width: 100%;
            }
            
            .report-tabs-wrapper .btn-group .btn {
                font-size: 0.875rem;
                padding: 0.5rem 0.75rem;
            }
        }
        
        /* Tablet responsive */
        @media (min-width: 576px) and (max-width: 767.98px) {
            .report-tabs-wrapper .btn-group .btn {
                font-size: 0.875rem;
                padding: 0.5rem 0.875rem;
            }
        }
    </style>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'Laporan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/laporan.blade.php ENDPATH**/ ?>