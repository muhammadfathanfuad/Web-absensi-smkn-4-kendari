<?php $__env->startSection('content'); ?>

<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



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
                        <p class="text-muted mb-0">Dashboard Administrasi - <?php echo e(\App\Services\TimeOverrideService::now()->translatedFormat('l, j F Y')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
                        <h3 class="text-dark mt-2 mb-0"><?php echo e($totalTeachers ?? 0); ?></h3>
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
                        <h3 class="text-dark mt-2 mb-0"><?php echo e($totalStudents ?? 0); ?></h3>
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
                        <h3 class="text-dark mt-2 mb-0"><?php echo e($totalSubjects ?? 0); ?></h3>
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
                        <h3 class="text-dark mt-2 mb-0"><?php echo e($totalClasses ?? 0); ?></h3>
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
                        <h3 class="text-dark mt-2 mb-0"><?php echo e($todayAttendance ?? 0); ?></h3>
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
                        <h3 class="text-dark mt-2 mb-0"><?php echo e($todayLeaveRequests ?? 0); ?></h3>
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
                        <h3 class="text-dark mt-2 mb-0"><?php echo e($todayActiveSessions ?? 0); ?></h3>   
                        <p class="text-muted mb-0">Sesi absensi berlangsung</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



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
                                <h5 class="mb-1 text-primary" id="totalActiveTeachers"><?php echo e($teacherPagination['total'] ?? 0); ?></h5>
                                <p class="text-muted mb-0">Total Guru Aktif</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h5 class="mb-1 text-success" id="totalActualHours"><?php echo e(round(collect($teacherPagination['data'] ?? [])->sum('actual_hours'), 1)); ?></h5>
                                <p class="text-muted mb-0">Jam Aktual (Halaman Ini)</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h5 class="mb-1 text-danger" id="totalScheduledHours"><?php echo e(round(collect($teacherPagination['data'] ?? [])->sum('scheduled_hours'), 1)); ?></h5>
                                <p class="text-muted mb-0">Jam Terjadwal (Halaman Ini)</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h5 class="mb-1 text-warning" id="avgCompliance"><?php echo e(count($teacherPagination['data'] ?? []) > 0 ? round(collect($teacherPagination['data'] ?? [])->avg('compliance_rate'), 1) : 0); ?>%</h5>
                            <p class="text-muted mb-0">Rata-rata Compliance</p>
                        </div>
                    </div>
                    
                    <!-- Mobile: 2 kolom dalam 2 baris -->
                    <div class="row text-center d-md-none">
                        <!-- Baris 1: Total Guru Aktif dan Jam Aktual -->
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h5 class="mb-1 text-primary" id="totalActiveTeachersMobile"><?php echo e($teacherPagination['total'] ?? 0); ?></h5>
                                <p class="text-muted mb-0">Total Guru Aktif</p>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h5 class="mb-1 text-success" id="totalActualHoursMobile"><?php echo e(round(collect($teacherPagination['data'] ?? [])->sum('actual_hours'), 1)); ?></h5>
                                <p class="text-muted mb-0">Jam Aktual (Halaman Ini)</p>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center d-md-none">
                        <!-- Baris 2: Jam Terjadwal dan Rata-rata Compliance -->
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h5 class="mb-1 text-danger" id="totalScheduledHoursMobile"><?php echo e(round(collect($teacherPagination['data'] ?? [])->sum('scheduled_hours'), 1)); ?></h5>
                                <p class="text-muted mb-0">Jam Terjadwal (Halaman Ini)</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <h5 class="mb-1 text-warning" id="avgComplianceMobile"><?php echo e(count($teacherPagination['data'] ?? []) > 0 ? round(collect($teacherPagination['data'] ?? [])->avg('compliance_rate'), 1) : 0); ?>%</h5>
                                <p class="text-muted mb-0">Rata-rata Compliance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <!-- Teacher Performance Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Guru dengan Kehadiran Terendah</h4>
                <p class="text-muted mb-0">Halaman <?php echo e($teacherPagination['current_page'] ?? 1); ?> dari <?php echo e($teacherPagination['last_page'] ?? 1); ?></p>
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
                            <?php $__empty_1 = true; $__currentLoopData = ($teacherPagination['data'] ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e((($teacherPagination['current_page'] ?? 1) - 1) * 5 + $index + 1); ?></td>
                                    <td>
                                            <span class="fw-semibold"><?php echo e($teacher['name']); ?></span>
                                    </td>
                                    <td><?php echo e($teacher['nip']); ?></td>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger py-1 px-2">
                                            <?php echo e($teacher['scheduled_hours']); ?> jam
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success py-1 px-2">
                                            <?php echo e($teacher['actual_hours']); ?> jam
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress progress-soft progress-sm me-2" style="width: 60px;">
                                                <div class="progress-bar <?php echo e($teacher['compliance_rate'] >= 80 ? 'bg-success' : ($teacher['compliance_rate'] >= 60 ? 'bg-warning' : 'bg-danger')); ?>" role="progressbar" 
                                                     style="width: <?php echo e($teacher['compliance_rate']); ?>%" 
                                                     aria-valuenow="<?php echo e($teacher['compliance_rate']); ?>" 
                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="fw-semibold"><?php echo e($teacher['compliance_rate']); ?>%</span>
                                        </div>
                                    </td>
                                    <td><?php echo e($teacher['sessions_conducted']); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted d-flex flex-column align-items-center">
                                            <iconify-icon icon="solar:user-outline" class="fs-48 mb-2"></iconify-icon>
                                            Tidak ada data guru.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if(isset($teacherPagination) && $teacherPagination['last_page'] > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted" id="pagination-info">
                        Menampilkan <?php echo e($teacherPagination['from'] ?? 0); ?> - <?php echo e($teacherPagination['to'] ?? 0); ?> dari <?php echo e($teacherPagination['total'] ?? 0); ?> guru
            </div>
                    <nav aria-label="Pagination">
                        <ul id="teacher-pagination" class="pagination pagination-sm mb-0">
                            <!-- Previous Page -->
                            <?php if(($teacherPagination['current_page'] ?? 1) > 1): ?>
                                <li class="page-item">
                                    <a class="page-link pagination-link" href="#" data-page="<?php echo e(($teacherPagination['current_page'] ?? 1) - 1); ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </span>
                                </li>
                            <?php endif; ?>
                            
                            <!-- Page Numbers -->
                            <?php for($i = 1; $i <= ($teacherPagination['last_page'] ?? 1); $i++): ?>
                                <?php if($i == ($teacherPagination['current_page'] ?? 1)): ?>
                                    <li class="page-item active">
                                        <span class="page-link"><?php echo e($i); ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link pagination-link" href="#" data-page="<?php echo e($i); ?>"><?php echo e($i); ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <!-- Next Page -->
                            <?php if(($teacherPagination['current_page'] ?? 1) < ($teacherPagination['last_page'] ?? 1)): ?>
                                <li class="page-item">
                                    <a class="page-link pagination-link" href="#" data-page="<?php echo e(($teacherPagination['current_page'] ?? 1) + 1); ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </div>


<div class="row">
    <!-- Class Statistics Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Statistik Kelas</h4>
                <p class="text-muted mb-0">Halaman <?php echo e($classStatistics['current_page'] ?? 1); ?> dari <?php echo e($classStatistics['last_page'] ?? 1); ?></p>
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
                            <?php $__empty_1 = true; $__currentLoopData = ($classStatistics['data'] ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e((($classStatistics['current_page'] ?? 1) - 1) * 10 + $index + 1); ?></td>
                                    <td>
                                            <span class="fw-semibold"><?php echo e($class['name']); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary py-1 px-2">
                                            Grade <?php echo e($class['grade']); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($class['group'] !== '-'): ?>
                                            <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                                <?php echo e($class['group']); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success py-1 px-2">
                                            <?php echo e($class['students_count']); ?> siswa
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                            <?php echo e($class['subjects_count']); ?> mapel
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted d-flex flex-column align-items-center">
                                            <iconify-icon icon="solar:home-outline" class="fs-48 mb-2"></iconify-icon>
                                            Tidak ada data kelas.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Class Statistics Pagination -->
                <?php if(($classStatistics['last_page'] ?? 1) > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        <span id="class-pagination-info">Menampilkan <?php echo e($classStatistics['from'] ?? 0); ?> - <?php echo e($classStatistics['to'] ?? 0); ?> dari <?php echo e($classStatistics['total'] ?? 0); ?> kelas</span>
            </div>
                    <nav aria-label="Class statistics pagination">
                        <ul id="class-pagination" class="pagination pagination-sm mb-0">
                            <?php if(($classStatistics['current_page'] ?? 1) > 1): ?>
                                <li class="page-item">
                                    <a class="page-link class-pagination-link" href="#" data-page="<?php echo e(($classStatistics['current_page'] ?? 1) - 1); ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </span>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= ($classStatistics['last_page'] ?? 1); $i++): ?>
                                <?php if($i == ($classStatistics['current_page'] ?? 1)): ?>
                                    <li class="page-item active">
                                        <span class="page-link"><?php echo e($i); ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link class-pagination-link" href="#" data-page="<?php echo e($i); ?>"><?php echo e($i); ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if(($classStatistics['current_page'] ?? 1) < ($classStatistics['last_page'] ?? 1)): ?>
                                <li class="page-item">
                                    <a class="page-link class-pagination-link" href="#" data-page="<?php echo e(($classStatistics['current_page'] ?? 1) + 1); ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="row equal-height-cards">

    <!-- Recent Activities -->
    <div class="col-xl-12">
        <div class="card h-100">
            <div class="card-header">
                <h4 class="card-title mb-0">Aktivitas Terbaru</h4>
                <p class="text-muted mb-0">Halaman <?php echo e($recentActivities['current_page'] ?? 1); ?> dari <?php echo e($recentActivities['last_page'] ?? 1); ?></p>
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
                            <?php $__empty_1 = true; $__currentLoopData = ($recentActivities['data'] ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e((($recentActivities['current_page'] ?? 1) - 1) * 5 + $index + 1); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs bg-<?php echo e($activity['color']); ?>-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <iconify-icon icon="solar:<?php echo e($activity['icon']); ?>-outline" class="fs-16 text-<?php echo e($activity['color']); ?>"></iconify-icon>
                            </div>
                                            <span class="fw-semibold"><?php echo e($activity['description']); ?></span>
                            </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark py-1 px-2">
                                            <?php echo e($activity['time']->diffForHumans()); ?>

                                        </span>
                                    </td>
                                </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <div class="text-muted d-flex flex-column align-items-center">
                                <iconify-icon icon="solar:clock-outline" class="fs-48 mb-2"></iconify-icon>
                                Tidak ada aktivitas terbaru.
                            </div>
                                    </td>
                                </tr>
                    <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if(($recentActivities['last_page'] ?? 1) > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        <span id="activities-pagination-info">Menampilkan <?php echo e($recentActivities['from'] ?? 0); ?> - <?php echo e($recentActivities['to'] ?? 0); ?> dari <?php echo e($recentActivities['total'] ?? 0); ?> aktivitas</span>
            </div>
                    <nav aria-label="Activities pagination">
                        <ul id="activities-pagination" class="pagination pagination-sm mb-0">
                            <?php if(($recentActivities['current_page'] ?? 1) > 1): ?>
                                <li class="page-item">
                                    <a class="page-link activities-pagination-link" href="#" data-page="<?php echo e(($recentActivities['current_page'] ?? 1) - 1); ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </span>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= ($recentActivities['last_page'] ?? 1); $i++): ?>
                                <?php if($i == ($recentActivities['current_page'] ?? 1)): ?>
                                    <li class="page-item active">
                                        <span class="page-link"><?php echo e($i); ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link activities-pagination-link" href="#" data-page="<?php echo e($i); ?>"><?php echo e($i); ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if(($recentActivities['current_page'] ?? 1) < ($recentActivities['last_page'] ?? 1)): ?>
                                <li class="page-item">
                                    <a class="page-link activities-pagination-link" href="#" data-page="<?php echo e(($recentActivities['current_page'] ?? 1) + 1); ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin/dashboard.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Pass data from Laravel to JavaScript
    window.dashboardData = {
        attendanceTrends: <?php echo json_encode($attendanceTrends ?? [], 15, 512) ?>,
        teacherPerformance: <?php echo json_encode($teacherPagination['data'] ?? [], 15, 512) ?>,
        routes: {
            teacherPagination: '<?php echo e(route("admin.dashboard.teacher-pagination")); ?>',
            classPagination: '<?php echo e(route("admin.dashboard.class-pagination")); ?>',
            activitiesPagination: '<?php echo e(route("admin.dashboard.activities-pagination")); ?>'
        }
    };
</script>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin/dashboard.js']); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>