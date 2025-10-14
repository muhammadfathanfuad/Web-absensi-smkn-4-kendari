<?php $__env->startSection('css'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['node_modules/flatpickr/dist/flatpickr.min.css']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Form', 'subtitle' => 'Flatpicker'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row row-cols-lg-2 gx-3">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic</h5>
            </div>

            <div class="card-body">
                <div>
                    <input type="text" id="basic-datepicker" class="form-control" placeholder="Basic datepicker">
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">DateTime</h5>
            </div>
            <div class="card-body">
                <input type="text" id="datetime-datepicker" class="form-control" placeholder="Date and Time">
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Human-friendly Dates</h5>
            </div>
            <div class="card-body">
                <div>
                    <input type="text" id="humanfd-datepicker" class="form-control" placeholder="October 9, 2018">
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">MinDate and MaxDate</h5>
            </div>
            <div class="card-body">
                <div>
                    <input type="text" id="minmax-datepicker" class="form-control" placeholder="mindate - maxdate">
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Disabling dates</h5>
            </div>
            <div class="card-body">
                <div>
                    <input type="text" id="disable-datepicker" class="form-control" placeholder="Disabling dates">
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Selecting multiple dates</h5>
            </div>
            <div class="card-body">
                <div>
                    <input type="text" id="multiple-datepicker" class="form-control" placeholder="Multiple dates">
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Selecting multiple dates - Conjunction</h5>
            </div>
            <div class="card-body">
                <div>
                    <input type="text" id="conjunction-datepicker" class="form-control"
                        placeholder="2018-10-10 :: 2018-10-11">
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Range Calendar</h5>
            </div>
            <div class="card-body">
                <div>
                    <input type="text" id="range-datepicker" class="form-control"
                        placeholder="2018-10-03 to 2018-10-10">
                </div>
            </div>
        </div>
    </div>



    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic Timepicker</h5>
            </div>
            <div class="card-body">
                <input type="text" id="basic-timepicker" class="form-control" placeholder="Basic timepicker">
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">24-hour Time Picker</h5>
            </div>
            <div class="card-body">
                <input type="text" id="24hours-timepicker" class="form-control" placeholder="16:21">
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Time Picker w/ Limits</h5>
            </div>
            <div class="card-body">
                <div>
                    <input type="text" id="minmax-timepicker" class="form-control" placeholder="Limits">
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Preloading Time</h5>
            </div>
            <div class="card-body">
                <div>
                    <input type="text" id="preloading-timepicker" class="form-control" placeholder="Pick a time">
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Inline Calendar</h5>
            </div>
            <div class="card-body">
                <div>
                    <input type="text" id="inline-datepicker" class="form-control" placeholder="Inline calendar">
                </div>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/form-flatepicker.js']); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Flatpicker'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views\forms\flatpicker.blade.php ENDPATH**/ ?>