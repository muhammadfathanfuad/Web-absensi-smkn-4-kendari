<!DOCTYPE html>
<html lang="en" <?php echo $__env->yieldContent('html-attribute'); ?>>

<head>
    <?php echo $__env->make('layouts.partials/title-meta', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('layouts.partials/head-css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body>

    <div class="app-wrapper">

        <?php echo $__env->make('layouts.partials/sidebar-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->make('layouts.partials/topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="page-content">

            <div class="container-fluid">

                <?php echo $__env->yieldContent('content'); ?>

            </div>

            <?php echo $__env->make('layouts.partials/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

    </div>

    <?php echo $__env->make('layouts.partials/vendor-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


</body>

</html><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/layouts/vertical.blade.php ENDPATH**/ ?>