<!DOCTYPE html>
<html <?php echo $__env->yieldContent('html-attribute'); ?>>

<head>
    <?php echo $__env->make('layouts.partials/title-meta', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php echo $__env->make('layouts.partials/head-css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body <?php echo $__env->yieldContent('body-attribuet'); ?>>

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo $__env->make('layouts.partials/vendor-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>

</html><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/layouts/base.blade.php ENDPATH**/ ?>