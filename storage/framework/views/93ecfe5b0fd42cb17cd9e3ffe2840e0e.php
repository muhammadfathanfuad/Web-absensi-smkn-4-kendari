<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', [
        'title' => 'Laporan',
        'subtitle' => 'Laporan',
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
        $reportType = request('report_type', 'overview');
        $dateFrom = request('date_from', date('Y-m-01'));
        $dateTo = request('date_to', date('Y-m-d'));

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
                                                ->whereBetween('created_at', [$dateFrom, $dateTo])
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
                                                ->whereBetween('created_at', [$dateFrom, $dateTo])
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
                                                ->whereBetween('created_at', [$dateFrom, $dateTo])
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
                        <h4 class="card-title mb-4">Laporan Per Guru</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Guru</th>
                                        <th>NIP</th>
                                        <th>Total Record</th>
                                        <th>Hadir</th>
                                        <th>Terlambat</th>
                                        <th>Absen</th>
                                        <th>Persentase Hadir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = \App\Models\Teacher::with('user')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $teacherAttendance = \App\Models\Attendance::whereHas(
                                                'classSession.timetable.classSubject',
                                                function ($q) use ($teacher) {
                                                    $q->where('teacher_id', $teacher->user_id);
                                                },
                                            )
                                                ->whereBetween('created_at', [$dateFrom, $dateTo])
                                                ->get();

                                            $teacherPresent = $teacherAttendance->where('status', 'H')->count();
                                            $teacherLate = $teacherAttendance->where('status', 'T')->count();
                                            $teacherAbsent = $teacherAttendance->where('status', 'A')->count();
                                            $teacherTotal = $teacherAttendance->count();
                                            $teacherPercentage =
                                                $teacherTotal > 0
                                                    ? round(($teacherPresent / $teacherTotal) * 100, 2)
                                                    : 0;
                                        ?>
                                        <tr>
                                            <td><?php echo e($teacher->user->full_name); ?></td>
                                            <td><?php echo e($teacher->nip); ?></td>
                                            <td><?php echo e(number_format($teacherTotal)); ?></td>
                                            <td class="text-success"><?php echo e(number_format($teacherPresent)); ?></td>
                                            <td class="text-warning"><?php echo e(number_format($teacherLate)); ?></td>
                                            <td class="text-danger"><?php echo e(number_format($teacherAbsent)); ?></td>
                                            <td>
                                                <span
                                                    class="badge <?php echo e($teacherPercentage >= 80 ? 'bg-success' : ($teacherPercentage >= 60 ? 'bg-warning' : 'bg-danger')); ?>">
                                                    <?php echo e($teacherPercentage); ?>%
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
        function exportReport(format = 'xlsx') {
            try {
                // Get current report type from checked radio button
                var checkedRadio = document.querySelector('input[name="report_type"]:checked');
                if (!checkedRadio) {
                    showAlert('Pilih jenis laporan terlebih dahulu', 'danger');
                    return;
                }

                // Build clean export URL with only necessary parameters
                var exportUrl = '<?php echo e(route('admin.laporan.export')); ?>?export=1&format=' + format + '&report_type=' + checkedRadio.value;

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

                // Build clean export URL
                var exportUrl = '<?php echo e(route('admin.laporan.export')); ?>?export=1&format=' + format + '&report_type=' + checkedRadio.value;
                
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
                    // Build clean URL with only report_type parameter
                    var targetUrl = '<?php echo e(route('admin.laporan')); ?>?report_type=' + this.value;

                    // Navigate to the correct URL
                    window.location.href = targetUrl;
                }
            });
        });

        // Ensure form action is always correct on page load
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('filterForm');
            if (!form) {
                console.error('Filter form not found on page load');
                return;
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'Laporan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/laporan.blade.php ENDPATH**/ ?>