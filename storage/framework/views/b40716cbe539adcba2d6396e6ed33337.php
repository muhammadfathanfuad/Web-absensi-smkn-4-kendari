<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Jadwal Mengajar', 'subtitle' => 'Guru'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Jadwal Hari Ini (<?php echo e(\Carbon\Carbon::now()->translatedFormat('l, j F Y')); ?>)</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Jam</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(\Carbon\Carbon::parse($jadwal->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($jadwal->end_time)->format('H:i')); ?></td>
                                <td><?php echo e($jadwal->subject->name ?? 'N/A'); ?></td>
                                <td><?php echo e($jadwal->classroom->name ?? 'N/A'); ?></td>
                                
                                <td><?php echo e($jadwal->classroom->room->name ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada jadwal mengajar hari ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Jadwal Semester Ini</h4>
        </div>
        <div class="card-body">
            <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayNumber => $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(isset($semuaJadwal[$dayNumber]) && $semuaJadwal[$dayNumber]->count() > 0): ?>
                    <div class="mb-4">
                        <h5><?php echo e($dayName); ?></h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jam</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Ruangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $semuaJadwal[$dayNumber]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(\Carbon\Carbon::parse($jadwal->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($jadwal->end_time)->format('H:i')); ?></td>
                                            <td><?php echo e($jadwal->subject->name ?? 'N/A'); ?></td>
                                            <td><?php echo e($jadwal->classroom->name ?? 'N/A'); ?></td>
                                            
                                            <td><?php echo e($jadwal->classroom->room->name ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Jadwal Mengajar'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/jadwal-mengajar.blade.php ENDPATH**/ ?>