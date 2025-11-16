<?php $__env->startSection('title', 'Pengumuman'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/murid/pengumuman.css']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Pengumuman</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Siswa</li>
                        <li class="breadcrumb-item active">Pengumuman</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('components.pengumuman.pengumuman', [
        'mode' => 'murid',
        'showFilters' => true,
        'showSidebar' => false,
        'announcementsContainerId' => 'announcementsContainer',
        'pengumumanListId' => 'pengumumanList',
        'categoryFilterId' => 'categoryFilter',
        'dateFilterId' => 'dateFilter',
        'searchInputId' => 'searchInput'
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('components.pengumuman.modal-detail-pengumuman', [
        'modalId' => 'readMoreModal',
        'modalLabelId' => 'readMoreModalLabel',
        'modalTitleId' => 'modalTitle',
        'modalDateId' => 'modalDate',
        'modalAuthorId' => 'modalAuthor',
        'modalCategoryId' => 'modalCategory',
        'modalContentId' => 'modalContent',
        'modalCreatedAtId' => 'modalCreatedAt',
        'modalExpiresAtId' => 'modalExpiresAt',
        'modalPriorityId' => 'modalPriority',
        'modalIconId' => 'modalIcon',
        'modalIconClassId' => 'modalIconClass',
        'modalMarkReadBtnId' => 'modalMarkReadBtn',
        'markReadFunction' => 'toggleReadStatusFromModal'
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/murid/pengumuman.js']); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.vertical-murid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/murid/pengumuman.blade.php ENDPATH**/ ?>