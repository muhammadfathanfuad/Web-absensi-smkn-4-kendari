<?php $__env->startSection('title', 'Riwayat Absensi'); ?>


<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Riwayat Absensi</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('murid.dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Absensi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row mb-3" id="filter-form">
                        <div class="col-md-4">
                            <label for="date-range" class="form-label">Filter berdasarkan tanggal:</label>
                            <input type="text" id="date-range" class="form-control" placeholder="Pilih rentang tanggal..." value="<?php echo e(($from && $to) ? $from.' to '.$to : ''); ?>">
                            <input type="hidden" name="from" id="date-from" value="<?php echo e($from ?? ''); ?>">
                            <input type="hidden" name="to" id="date-to" value="<?php echo e($to ?? ''); ?>">
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $attendances ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e(optional($att->created_at)->format('d F Y')); ?></td>
                                        <td><?php echo e(optional(optional($att->classSession)->timetable)->subject->name ?? '—'); ?></td>
                                        <td>
                                            <?php switch($att->status):
                                                case ('H'): ?>
                                                    <span class="badge bg-success">Hadir</span>
                                                    <?php break; ?>
                                                <?php case ('I'): ?>
                                                    <span class="badge bg-warning text-dark">Izin</span>
                                                    <?php break; ?>
                                                <?php case ('S'): ?>
                                                    <span class="badge bg-info">Sakit</span>
                                                    <?php break; ?>
                                                <?php case ('T'): ?>
                                                    <span class="badge bg-warning">Terlambat</span>
                                                    <?php break; ?>
                                                <?php case ('A'): ?>
                                                    <span class="badge bg-danger">Alpa</span>
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    <span class="badge bg-secondary"><?php echo e($att->status); ?></span>
                                            <?php endswitch; ?>
                                        </td>
                                        <td><?php echo e(optional($att->check_in_time)->format('H:i') ?? '-'); ?></td>
                                        <td><?php echo e($att->notes ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data absensi dalam rentang tanggal yang dipilih.</td>
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

<?php $__env->startSection('scripts'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Inisialisasi Flatpickr untuk filter rentang tanggal
        const fp = flatpickr("#date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    document.getElementById('date-from').value = selectedDates[0].toISOString().slice(0,10);
                    document.getElementById('date-to').value = selectedDates[1].toISOString().slice(0,10);
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-murid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\PresenZ\Web-absensi-smkn-4-kendari\resources\views/murid/riwayat-absensi.blade.php ENDPATH**/ ?>