<!DOCTYPE html>
<html lang="en" <?php echo $__env->yieldContent('html-attribute'); ?>>

<head>
    <?php echo $__env->make('layouts.partials.title-meta', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('layouts.partials.head-css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/scss/style.scss', 'resources/js/app.js']); ?>
    </head>

<body>

    <div class="app-wrapper">

        <?php echo $__env->make('layouts.partials.sidebar-guru', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->make('layouts.partials.topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="page-content">

            <div class="container-fluid">

                <?php echo $__env->yieldContent('content'); ?>

            </div>

            <?php echo $__env->make('layouts.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

    </div>
    </body>

</html><?php /**PATH D:\PresenZ\Web-absensi-smkn-4-kendari\resources\views/layouts/vertical-guru.blade.php ENDPATH**/ ?>