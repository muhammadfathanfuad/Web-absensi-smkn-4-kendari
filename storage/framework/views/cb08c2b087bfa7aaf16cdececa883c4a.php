<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Breadcrumb'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- start breadcrumbs -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Default Example
                </h5>
                <p class="card-subtitle">
                    Use an ordered or unordered list with linked list items to create a minimally styled
                    breadcrumb.
                    Use our utilities to add additional styles as desired.
                </p>
            </div>
            <div class="card-body">
                <!-- Default Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb py-0">
                        <li class="breadcrumb-item active" aria-current="page">Home</li>
                    </ol>
                </nav>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb py-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Library</li>
                    </ol>
                </nav>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>

            </div>
        </div> <!-- end card -->
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Dividers Breadcrumb</h5>
                <p class="card-subtitle">
                    Optionally you can also specify the icon with your breadcrumb item.
                </p>
            </div>
            <div class="card-body">

                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb py-0">
                        <li class="breadcrumb-item active" aria-current="page">Home</li>
                    </ol>
                </nav>

                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb py-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Library</li>
                    </ol>
                </nav>

                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>

            </div>
        </div> <!-- end card body -->
    </div> <!-- end col -->
</div> <!-- end row -->
<!-- end breadcrumbs -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Breadcrumb'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views\ui\breadcrumb.blade.php ENDPATH**/ ?>