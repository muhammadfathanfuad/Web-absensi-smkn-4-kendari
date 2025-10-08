

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Status Absensi', 'subtitle' => 'Guru'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Rekap Kehadiran Siswa</h4>

            
            <form action="<?php echo e(route('guru.status-absensi')); ?>" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="subject_id" class="form-label">Pilih Mata Pelajaran</label>
                        <select name="subject_id" id="subject_id" class="form-select">
                            <option value="">Semua Mapel</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e($selectedSubjectId == $subject->id ? 'selected' : ''); ?>>
                                    <?php echo e($subject->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="date" class="form-label">Pilih Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?php echo e($selectedDate); ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <hr>

            
            <div class="table-responsive mt-4">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Mata Pelajaran</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($absen->student->nis ?? 'N/A'); ?></td>
                                <td><?php echo e($absen->student->user->full_name ?? 'N/A'); ?></td>
                                <td><?php echo e($absen->classSession->timetable->subject->name ?? 'N/A'); ?></td>
                                <td><?php echo e($absen->check_in_time ?? '-'); ?></td>
                                <td>
                                    
                                    <?php if($absen->status == 'S'): ?>
                                        <span class="badge bg-soft-warning text-warning">Sakit</span>
                                    <?php elseif($absen->status == 'I'): ?>
                                        <span class="badge bg-soft-info text-info">Izin</span>
                                    <?php elseif($absen->status == 'T' || ($absen->notes === 'Terlambat' && $absen->status !== 'H')): ?>
                                        
                                        <span class="badge bg-soft-danger text-danger">Terlambat</span>
                                    <?php elseif($absen->status == 'H'): ?>
                                        <span class="badge bg-soft-success text-success">Hadir</span>
                                    <?php else: ?>
                                        <span class="badge bg-soft-secondary text-secondary"><?php echo e($absen->status); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data absensi untuk filter yang dipilih.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Status Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\PresenZ\Web-absensi-smkn-4-kendari\resources\views/guru/status-absensi.blade.php ENDPATH**/ ?>