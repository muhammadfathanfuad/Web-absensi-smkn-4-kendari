

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Pengganti Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📋 Tugas Pengganti Absensi </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Jam</th>
                                <th>Guru Asli</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $myDelegations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delegasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <?php
                                    $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                                    $dayName = $dayNames[$delegasi->timetable->day_of_week] ?? 'N/A';
                                ?>
                                <td><?php echo e($dayName); ?></td>
                                <td><?php echo e($delegasi->timetable->classSubject->subject->name ?? 'N/A'); ?></td>
                                <td><?php echo e($delegasi->timetable->classSubject->class->name ?? 'N/A'); ?></td>
                                <td><?php echo e(Carbon\Carbon::parse($delegasi->timetable->start_time)->format('H:i')); ?> - <?php echo e(Carbon\Carbon::parse($delegasi->timetable->end_time)->format('H:i')); ?></td>
                                <td><?php echo e($delegasi->originalTeacher->user->full_name ?? 'N/A'); ?></td>
                                <td>
                                    <?php if($delegasi->type == 'permanent'): ?>
                                        <span class="badge bg-info">Permanent</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Temporary</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        // day_of_week adalah integer (1=Senin, 2=Selasa, ..., 7=Minggu)
                                        // dayOfWeekIso juga mengembalikan 1=Senin, 2=Selasa, ..., 7=Minggu
                                        $delegationDayNumber = $delegasi->timetable->day_of_week;
                                        $todayDayNumber = $today->dayOfWeekIso;
                                        $isToday = ($todayDayNumber === $delegationDayNumber);
                                        
                                        $isWithinTemporaryPeriod = true;
                                        if ($delegasi->type === 'temporary') {
                                            $validFrom = \Carbon\Carbon::parse($delegasi->valid_from)->startOfDay();
                                            $validUntil = \Carbon\Carbon::parse($delegasi->valid_until)->endOfDay();
                                            $todayDate = $today->startOfDay();
                                            // Gunakan isBetween dengan inclusive untuk memastikan tanggal boundary termasuk
                                            $isWithinTemporaryPeriod = $todayDate->isBetween($validFrom, $validUntil, true);
                                        }
                                    ?>
                                    <?php if($isToday && $isWithinTemporaryPeriod): ?>
                                    <a href="<?php echo e(route('guru.absensi.scan', ['timetable_id' => $delegasi->timetable->id])); ?>" class="btn btn-sm btn-primary">
                                        <i class="bx bx-qr-scan"></i> Buka QR
                                    </a>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">Belum waktunya</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bx bx-info-circle fs-32"></i>
                                        <p class="mb-0 mt-2">Anda belum memiliki delegasi</p>
                                    </div>
                                </td>
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


<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Delegasi Saya'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/delegasi.blade.php ENDPATH**/ ?>