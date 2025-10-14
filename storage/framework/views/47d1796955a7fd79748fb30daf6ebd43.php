<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <?php echo $__env->make('layouts.partials.title-meta', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('layouts.partials.head-css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body>

    <div class="content-page">
        <div class="content">
            
            <div class="container-fluid">
                <?php echo $__env->yieldContent('content'); ?>
            </div>

        </div> <?php echo $__env->make('layouts.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

    

    
    <?php echo $__env->yieldContent('script'); ?>

</body>
</html><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views\guru\layouts\base-scanner.blade.php ENDPATH**/ ?>