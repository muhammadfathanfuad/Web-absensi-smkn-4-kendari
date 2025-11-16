<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Siswa', 'subtitle' => 'Tugas Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('components.tugas-absensi.tugas-absensi', [
    'mode' => 'murid',
    'myDelegations' => $myDelegations ?? collect(),
    'today' => $today ?? \Carbon\Carbon::now(),
    'showInfoAlert' => true,
    'qrModalFunction' => 'openQRModal',
    'cardTitle' => 'Tugas Absensi dari Guru'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php echo $__env->make('components.tugas-absensi.modal-qr-delegasi', [
    'modalId' => 'qrModalDelegasi',
    'modalLabelId' => 'qrModalDelegasiLabel',
    'qrCodeContainerId' => 'qrCodeContainerDelegasi',
    'qrInfoTextId' => 'qrInfoTextDelegasi',
    'stopSessionBtnId' => 'stopSessionBtnDelegasi'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php echo $__env->make('components.tugas-absensi.modal-stop-session', [
    'modalId' => 'stopSessionModalDelegasi',
    'modalLabelId' => 'stopSessionModalDelegasiLabel',
    'stopSessionTokenId' => 'stopSessionTokenDelegasi',
    'confirmStopSessionBtnId' => 'confirmStopSessionBtnDelegasi'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('components.pengaturan.notification-modal', [
        'modalId' => 'notificationModalDelegasi',
        'modalLabelId' => 'notificationModalDelegasiLabel',
        'messageId' => 'notificationMessageDelegasi',
        'errorsId' => 'notificationErrorsDelegasi',
        'errorsBodyId' => 'notificationErrorsBodyDelegasi',
        'modalSize' => '',
        'showErrorsTable' => false,
        'showIcon' => true,
        'iconId' => 'notificationIconDelegasi'
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>

    
    <script>
        window.delegasiMuridRoutes = {
            generateQR: '<?php echo e(route("murid.delegasi.generate-qr")); ?>',
            stopSession: '<?php echo e(route("murid.delegasi.stop-session")); ?>'
        };
    </script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/murid/delegasi.js']); ?>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.vertical-murid', ['subtitle' => 'Delegasi Saya'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/murid/delegasi.blade.php ENDPATH**/ ?>