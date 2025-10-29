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
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0">Jenis Laporan</label>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-expanded="false" id="exportDropdownBtn">
                                            <i class="bx bx-download"></i> Export
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="exportReport('xlsx'); return false;">
                                                    <i class="bx bx-file"></i> Export Excel (.xlsx)
                                                </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="exportReport('csv'); return false;">
                                                    <i class="bx bx-file-blank"></i> Export CSV (.csv)
                                                </a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="btn-group w-100" role="group" aria-label="Report type selection">
                                    <input type="radio" class="btn-check" name="report_type" id="report_overview" value="overview" 
                                        <?php echo e(request('report_type', 'overview') == 'overview' ? 'checked' : ''); ?>>
                                    <label class="btn btn-outline-primary" for="report_overview">Ringkasan</label>

                                    <input type="radio" class="btn-check" name="report_type" id="report_class" value="class" 
                                        <?php echo e(request('report_type') == 'class' ? 'checked' : ''); ?>>
                                    <label class="btn btn-outline-primary" for="report_class">Per Kelas</label>

                                    <input type="radio" class="btn-check" name="report_type" id="report_student" value="student" 
                                        <?php echo e(request('report_type') == 'student' ? 'checked' : ''); ?>>
                                    <label class="btn btn-outline-primary" for="report_student">Per Siswa</label>

                                    <input type="radio" class="btn-check" name="report_type" id="report_subject" value="subject" 
                                        <?php echo e(request('report_type') == 'subject' ? 'checked' : ''); ?>>
                                    <label class="btn btn-outline-primary" for="report_subject">Per Mata Pelajaran</label>

                                    <input type="radio" class="btn-check" name="report_type" id="report_teacher" value="teacher" 
                                        <?php echo e(request('report_type') == 'teacher' ? 'checked' : ''); ?>>
                                    <label class="btn btn-outline-primary" for="report_teacher">Per Guru</label>
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
                                                value="<?php echo e(request('date_from', \App\Services\TimeOverrideService::now()->startOfMonth()->format('Y-m-d'))); ?>">
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
        $reportType = request('report_type', 'overview');
        $dateFrom = request('date_from', \App\Services\TimeOverrideService::now()->startOfMonth()->format('Y-m-d'));
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
            'overview' => 'Ringkasan Umum',
            'class' => 'Laporan Per Kelas',
            'student' => 'Laporan Per Siswa',
            'subject' => 'Laporan Per Mata Pelajaran',
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

    <?php if($reportType == 'overview'): ?>
        <!-- Overview Report -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Ringkasan Kehadiran</h4>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Record:</strong></td>
                                    <td class="text-end"><?php echo e(number_format($totalRecords)); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Hadir:</strong></td>
                                    <td class="text-end text-success"><?php echo e(number_format($presentCount)); ?>

                                        (<?php echo e($presentPercentage); ?>%)</td>
                                </tr>
                                <tr>
                                    <td><strong>Terlambat:</strong></td>
                                    <td class="text-end text-warning"><?php echo e(number_format($lateCount)); ?>

                                        (<?php echo e($latePercentage); ?>%)</td>
                                </tr>
                                <tr>
                                    <td><strong>Absen:</strong></td>
                                    <td class="text-end text-danger"><?php echo e(number_format($absentCount)); ?>

                                        (<?php echo e($absentPercentage); ?>%)</td>
                                </tr>
                            </table>
                        </div>
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
    <?php elseif($reportType == 'student'): ?>
        <!-- Student Report -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Laporan Per Siswa</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>NIS</th>
                                        <th>Kelas</th>
                                        <th>Total Record</th>
                                        <th>Hadir</th>
                                        <th>Terlambat</th>
                                        <th>Absen</th>
                                        <th>Persentase Hadir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = \App\Models\Student::with(['user', 'classroom'])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                        ?>
                                        <tr>
                                            <td><?php echo e($student->user->full_name); ?></td>
                                            <td><?php echo e($student->nis); ?></td>
                                            <td><?php echo e($student->classroom->grade); ?> - <?php echo e($student->classroom->name); ?></td>
                                            <td><?php echo e(number_format($studentTotal)); ?></td>
                                            <td class="text-success"><?php echo e(number_format($studentPresent)); ?></td>
                                            <td class="text-warning"><?php echo e(number_format($studentLate)); ?></td>
                                            <td class="text-danger"><?php echo e(number_format($studentAbsent)); ?></td>
                                            <td>
                                                <span
                                                    class="badge <?php echo e($studentPercentage >= 80 ? 'bg-success' : ($studentPercentage >= 60 ? 'bg-warning' : 'bg-danger')); ?>">
                                                    <?php echo e($studentPercentage); ?>%
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
    <?php elseif($reportType == 'subject'): ?>
        <!-- Subject Report -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Laporan Per Mata Pelajaran</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <th>Kode</th>
                                        <th>Total Record</th>
                                        <th>Hadir</th>
                                        <th>Terlambat</th>
                                        <th>Absen</th>
                                        <th>Persentase Hadir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = \App\Models\Subject::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $subjectAttendance = \App\Models\Attendance::whereHas(
                                                'classSession.timetable.classSubject',
                                                function ($q) use ($subject) {
                                                    $q->where('subject_id', $subject->id);
                                                },
                                            )
                                                ->whereBetween('created_at', [$dateFromCarbon, $dateToCarbon])
                                                ->get();

                                            $subjectPresent = $subjectAttendance->where('status', 'H')->count();
                                            $subjectLate = $subjectAttendance->where('status', 'T')->count();
                                            $subjectAbsent = $subjectAttendance->where('status', 'A')->count();
                                            $subjectTotal = $subjectAttendance->count();
                                            $subjectPercentage =
                                                $subjectTotal > 0
                                                    ? round(($subjectPresent / $subjectTotal) * 100, 2)
                                                    : 0;
                                        ?>
                                        <tr>
                                            <td><?php echo e($subject->name); ?></td>
                                            <td><?php echo e($subject->code); ?></td>
                                            <td><?php echo e(number_format($subjectTotal)); ?></td>
                                            <td class="text-success"><?php echo e(number_format($subjectPresent)); ?></td>
                                            <td class="text-warning"><?php echo e(number_format($subjectLate)); ?></td>
                                            <td class="text-danger"><?php echo e(number_format($subjectAbsent)); ?></td>
                                            <td>
                                                <span
                                                    class="badge <?php echo e($subjectPercentage >= 80 ? 'bg-success' : ($subjectPercentage >= 60 ? 'bg-warning' : 'bg-danger')); ?>">
                                                    <?php echo e($subjectPercentage); ?>%
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
    <?php elseif($reportType == 'teacher'): ?>
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
                                        <th>Total Pertemuan</th>
                                        <th>Total Record</th>
                                        <th>Persentase Absensi</th>
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
                                            // Hitung total pertemuan
                                            $totalPertemuan = 0;
                                            $timetables = \App\Models\Timetable::whereHas('classSubject.teacher', function($q) use ($teacher) {
                                                $q->where('teacher_id', $teacher->user_id);
                                            })->get();
                                            
                                            $startDate = \Carbon\Carbon::parse($dateFrom);
                                            $endDate = \Carbon\Carbon::parse($dateTo);
                                            
                                            while ($startDate->lte($endDate)) {
                                                $dayOfWeek = $startDate->dayOfWeek;
                                                $pertemuanHariIni = $timetables->filter(function($t) use ($dayOfWeek) {
                                                    return $t->day_of_week == $dayOfWeek;
                                                })->count();
                                                $totalPertemuan += $pertemuanHariIni;
                                                $startDate->addDay();
                                            }
                                            
                                            // Hitung total record
                                            $totalRecord = \App\Models\AttendanceSession::whereHas('timetable.classSubject.teacher', function($q) use ($teacher) {
                                                $q->where('teacher_id', $teacher->user_id);
                                            })
                                            ->whereBetween('created_at', [$dateFromCarbon, $dateToCarbon])
                                            ->where('is_active', false)
                                            ->count();
                                            
                                            // Hitung persentase
                                            $percentage = $totalPertemuan > 0 ? round(($totalRecord / $totalPertemuan) * 100, 2) : 0;
                                            
                                            return [
                                                'id' => $teacher->user_id,
                                                'nama' => $teacher->user->full_name ?? 'N/A',
                                                'nip' => $teacher->nip ?? 'N/A',
                                                'total_pertemuan' => $totalPertemuan,
                                                'total_record' => $totalRecord,
                                                'persentase' => $percentage,
                                                'status_badge' => $percentage >= 90 ? 'bg-success' : ($percentage >= 70 ? 'bg-warning' : 'bg-danger')
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
                                            <td><strong><?php echo e(number_format($teacherData['total_pertemuan'])); ?></strong></td>
                                            <td class="text-primary"><strong><?php echo e(number_format($teacherData['total_record'])); ?></strong></td>
                                            <td>
                                                <span class="badge <?php echo e($teacherData['status_badge']); ?>">
                                                    <?php echo e($teacherData['persentase']); ?>%
                                                </span>
                                                <small class="text-muted d-block mt-1">
                                                    <?php echo e(number_format($teacherData['total_record'])); ?> dari <?php echo e(number_format($teacherData['total_pertemuan'])); ?> pertemuan
                                                </small>
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
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        // Export function with simplified approach
        function exportReport(format = 'xlsx') {
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
                
                // Build clean export URL with all necessary parameters
                var exportUrl = '<?php echo e(route('admin.laporan.export')); ?>?export=1&format=' + format + '&report_type=' + checkedRadio.value;
                if (dateFrom) exportUrl += '&date_from=' + dateFrom;
                if (dateTo) exportUrl += '&date_to=' + dateTo;

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
        function tryFallbackExport(format = 'xlsx') {
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

                // Build clean export URL with all necessary parameters
                var exportUrl = '<?php echo e(route('admin.laporan.export')); ?>?export=1&format=' + format + '&report_type=' + checkedRadio.value;
                if (dateFrom) exportUrl += '&date_from=' + dateFrom;
                if (dateTo) exportUrl += '&date_to=' + dateTo;
                
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
            var formatText = format === 'xlsx' ? 'Excel' : 'CSV';
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


        // Auto-submit form when report type changes (radio buttons)
        document.querySelectorAll('input[name="report_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    // Get current date filters
                    var dateFrom = document.getElementById('date_from').value;
                    var dateTo = document.getElementById('date_to').value;
                    
                    // Build URL with report type and date filters
                    var targetUrl = '<?php echo e(route('admin.laporan')); ?>?report_type=' + this.value;
                    if (dateFrom) targetUrl += '&date_from=' + dateFrom;
                    if (dateTo) targetUrl += '&date_to=' + dateTo;

                    // Navigate to the correct URL
                    window.location.href = targetUrl;
                }
            });
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
            var startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            
            document.getElementById('date_from').value = startOfMonth.toISOString().split('T')[0];
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
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="text-muted">Halaman tidak ditemukan.</div></td></tr>';
                    } else if (pageData.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="text-muted d-flex flex-column align-items-center"><iconify-icon icon="solar:file-search-outline" class="fs-48 mb-2"></iconify-icon>Tidak ada hasil ditemukan.</div></td></tr>';
                    } else {
                        pageData.forEach(function(teacher) {
                            var row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${teacher.nama}</td>
                                <td>${teacher.nip}</td>
                                <td><strong>${teacher.total_pertemuan.toLocaleString()}</strong></td>
                                <td class="text-primary"><strong>${teacher.total_record.toLocaleString()}</strong></td>
                                <td>
                                    <span class="badge ${teacher.status_badge}">
                                        ${teacher.persentase}%
                                    </span>
                                    <small class="text-muted d-block mt-1">
                                        ${teacher.total_record.toLocaleString()} dari ${teacher.total_pertemuan.toLocaleString()} pertemuan
                                    </small>
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
        });
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
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'Laporan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/laporan.blade.php ENDPATH**/ ?>