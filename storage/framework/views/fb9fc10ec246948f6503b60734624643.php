<?php $__env->startSection('title', 'Dashboard Murid'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Dashboard Murid</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="<?php echo e(asset(optional($student->user ?? null)->profile_photo ?? 'images/users/avatar-1.jpg')); ?>" alt=""
                                class="avatar-sm rounded-circle">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-size-16 mb-1">Selamat Datang, <?php echo e(optional(optional($student)->user)->name ?? 'Siswa'); ?>!</h5>
                            <p class="text-muted mb-0">Kelas: <?php echo e(optional($student)->classroom->name ?? '—'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle text-success font-size-20">
                                <i class="bx bx-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Hadir</p>
                            <h4 class="mb-0"><?php echo e($hadirCount ?? 0); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle text-warning font-size-20">
                                <i class="bx bx-error-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Izin</p>
                            <h4 class="mb-0"><?php echo e($izinCount ?? 0); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info-subtle text-info font-size-20">
                                <i class="bx bx-first-aid"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Sakit</p>
                            <h4 class="mb-0"><?php echo e($sakitCount ?? 0); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-danger-subtle text-danger font-size-20">
                                <i class="bx bx-x-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Alpa</p>
                            <h4 class="mb-0"><?php echo e($alpaCount ?? 0); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Jadwal Pelajaran Hari Ini</h4>
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Guru</th>
                                    <th scope="col">Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $timetables ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <th scope="row"><?php echo e($i + 1); ?></th>
                                        <td><?php echo e(optional($tt->subject)->name ?? '\u2014'); ?></td>
                                        <td><?php echo e(optional(optional($tt->teacher)->user)->name ?? '—'); ?></td>
                                        <td><?php echo e(\Carbon\Carbon::parse($tt->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($tt->end_time)->format('H:i')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada jadwal untuk hari ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-murid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\PresenZ\Web-absensi-smkn-4-kendari\resources\views/murid/dashboard.blade.php ENDPATH**/ ?>