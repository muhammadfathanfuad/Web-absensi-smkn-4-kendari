

<?php $__env->startSection('content'); ?>
<?php $__env->startSection('css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['node_modules/gridjs/dist/theme/mermaid.min.css']); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.partials.page-title', ['title' => 'absensi', 'subtitle' => 'Test'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row">
    <div class="card">
        <div class="card-header ">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Pagination</h5>
                
            </div>
            <p class="text-muted mb-0">
                Pagination can be enabled by setting <code>pagination: true</code>:
            </p>
        </div>

        <div class="card-body">
            <div id="table-search"></div>
        </div>

        <div class="card-body">
            <div id="table-search"></div>
        </div>
    </div>
    
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/dashboard.js']); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin/tabel.js']); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'manage-user'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/manage-user.blade.php ENDPATH**/ ?>