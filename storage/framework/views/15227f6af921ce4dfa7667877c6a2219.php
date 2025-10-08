

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('layouts.partials.page-title', ['title' => 'jadwal', 'subtitle' => 'jadwal pelajaran'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row">
    
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/dashboard.js']); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'Jadwal Pelajaran'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/jadwal-pelajaran.blade.php ENDPATH**/ ?>