<?php $__env->startSection('css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/guru/pengumuman-guru.css']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Pengumuman'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('components.pengumuman.pengumuman', [
        'mode' => 'guru',
        'showFilters' => false,
        'showSidebar' => true,
        'announcementsContainerId' => 'announcementsContainer',
        'timelineContainerId' => 'timelineContainer'
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/guru/pengumuman-guru.js']); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Pengumuman'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/pengumuman-guru.blade.php ENDPATH**/ ?>