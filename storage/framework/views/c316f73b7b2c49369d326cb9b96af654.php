<?php $__env->startSection('title', 'Permohonan Izin'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Permohonan Izin</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Guru</li>
                        <li class="breadcrumb-item active">Permohonan Izin</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row align-items-start">
        <?php echo $__env->make('components.permohonan-izin.form-permohonan-izin', [
            'formAction' => route('guru.permohonan-izin.store'),
            'formId' => 'permohonanForm',
            'mode' => 'guru',
            'showTimetables' => true,
            'dateRangeInfoId' => 'date_range_info',
            'dateRangeTextId' => 'date_range_text',
            'timetablesLoadingId' => 'timetables_loading',
            'timetablesListId' => 'timetables_list',
            'timetablesCheckboxesId' => 'timetables_checkboxes',
            'noTimetablesId' => 'no_timetables',
            'timetablesPlaceholderId' => 'timetables_placeholder'
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->make('components.permohonan-izin.sidebar-info-permohonan-izin', [
            'mode' => 'guru',
            'showRecentRequests' => false
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    
    <?php echo $__env->make('components.permohonan-izin.riwayat-permohonan-izin', [
        'leaveRequests' => $leaveRequests ?? collect(),
        'mode' => 'guru',
        'showPagination' => false,
        'detailModalFunction' => 'showDetailModal'
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('components.permohonan-izin.modal-detail-permohonan-izin', [
        'modalId' => 'detailModal',
        'modalLabelId' => 'detailModalLabel',
        'modalBodyId' => 'detailModalBody'
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('components.pengaturan.notification-modal', [
        'modalId' => 'notificationModal',
        'modalLabelId' => 'notificationModalLabel',
        'messageId' => 'notificationMessage',
        'errorsId' => 'notificationErrors',
        'errorsBodyId' => 'notificationErrorsBody',
        'modalSize' => '',
        'showErrorsTable' => false,
        'showIcon' => false,
        'iconId' => 'notificationIcon'
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startPush('scripts'); ?>
    
<script>
        window.permohonanIzinRoutes = {
            getTimetables: '<?php echo e(route("guru.permohonan-izin.get-timetables")); ?>',
            show: '<?php echo e(route("guru.permohonan-izin.show", ":id")); ?>'
        };
    </script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/guru/permohonan-izin.js']); ?>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.vertical-guru', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/permohonan-izin.blade.php ENDPATH**/ ?>