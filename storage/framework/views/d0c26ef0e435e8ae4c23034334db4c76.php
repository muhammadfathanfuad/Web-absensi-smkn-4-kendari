    

    <?php $__env->startSection('title', 'Jadwal Pelajaran'); ?>

    
    <?php $__env->startSection('css'); ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('content'); ?>
        
        <div class="row calendar-adjust">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Jadwal</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
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
                                        <th scope="col">Kelas</th>
                                        <th scope="col">Guru</th>
                                        <th scope="col">Jam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $timetables ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <th scope="row"><?php echo e($i + 1); ?></th>
                                            <td><?php echo e(optional($tt->subject)->name ?? '—'); ?></td>
                                            <td><?php echo e(optional($tt->classroom)->name ?? optional($tt->classroom)->class_code ?? '—'); ?></td>
                                            <td><?php echo e(optional(optional($tt->teacher)->user)->name ?? '—'); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($tt->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($tt->end_time)->format('H:i')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada jadwal untuk hari ini.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card calender-card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Kalender</h4>
                        
                        <div class="flatpickr-calendar-inline"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php $__env->stopSection(); ?>

    
    <?php $__env->startSection('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            // Inisialisasi Flatpickr
            flatpickr('.flatpickr-calendar-inline', {
                inline: true,
            });
        </script>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-murid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\PresenZ\Web-absensi-smkn-4-kendari\resources\views/murid/jadwal-pelajaran.blade.php ENDPATH**/ ?>