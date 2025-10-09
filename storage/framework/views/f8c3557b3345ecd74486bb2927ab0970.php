<?php $__env->startSection('content'); ?>

<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Pengumuman', 'subtitle' => 'Informasi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <?php $__empty_1 = true; $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted fs-13"><?php echo e($item['waktu']); ?></span>
                        <span class="text-muted fs-13"><?php echo e($item['tanggal']); ?></span>
                    </div>
                    
                    <p class="card-text">
                        <?php echo e($item['isi']); ?>

                    </p>

                    <div class="text-end">
                        <small class="text-muted fst-italic">- <?php echo e($item['penulis']); ?></small>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card">
                <div class="card-body text-center">
                    <p class="text-muted">Belum ada pengumuman untuk saat ini.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Pengumuman'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Coding\Web-absensi-smkn-4-kendari\resources\views/guru/pengumuman.blade.php ENDPATH**/ ?>