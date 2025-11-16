<?php $__env->startSection('css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/guru/delegasi.css']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Pengganti Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('components.tugas-absensi.tugas-absensi', [
    'mode' => 'guru',
    'myDelegations' => $myDelegations ?? collect(),
    'today' => $today ?? \Carbon\Carbon::now(),
    'showInfoAlert' => false,
    'qrRouteName' => 'guru.absensi.scan',
    'cardTitle' => 'Tugas Pengganti Absensi',
    'emptyMessage' => 'Anda belum memiliki delegasi'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Delegasi Saya'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/delegasi.blade.php ENDPATH**/ ?>