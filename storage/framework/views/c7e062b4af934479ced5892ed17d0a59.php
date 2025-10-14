<?php $__env->startSection('content'); ?>

<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Maps', 'subtitle' => 'Vector Maps'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="row row-cols-lg-2 gx-3">
     <div class="col">
          <div class="card">
               <div class="card-header">
                    <h5 class="card-title">World Vector Map</h5>
                    <p class="card-subtitle">Give textual form controls like
                         <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade
                         with custom styles, sizing, focus states, and more.
                    </p>
               </div>

               <div class="card-body">
                    <div>
                         <div id="world-map-markers" style="height: 360px"></div>
                    </div>
               </div>
          </div>
     </div>

     <div class="col">
          <div class="card">
               <div class="card-header">
                    <h5 class="card-title">Canada Vector Map</h5>
                    <p class="card-subtitle">Give textual form controls like
                         <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade
                         with custom styles, sizing, focus states, and more.
                    </p>
               </div>

               <div class="card-body">
                    <div>
                         <div id="canada-vector-map" style="height: 360px"></div>
                    </div>
               </div>
          </div>
     </div>

     <div class="col">
          <div class="card">
               <div class="card-header">
                    <h5 class="card-title">Russia Vector Map</h5>
                    <p class="card-subtitle">Give textual form controls like
                         <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade
                         with custom styles, sizing, focus states, and more.
                    </p>
               </div>

               <div class="card-body">
                    <div>
                         <div id="russia-vector-map" style="height: 360px"></div>
                    </div>
               </div>
          </div>
     </div>

     <div class="col">
          <div class="card">
               <div class="card-header">
                    <h5 class="card-title">Iraq Vector Map</h5>
                    <p class="card-subtitle">Give textual form controls like
                         <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade
                         with custom styles, sizing, focus states, and more.
                    </p>
               </div>

               <div class="card-body">
                    <div>
                         <div id="iraq-vector-map" style="height: 360px"></div>
                    </div>
               </div>
          </div>
     </div>

     <div class="col">
          <div class="card">
               <div class="card-header">
                    <h5 class="card-title">Spain Vector Map</h5>
                    <p class="card-subtitle">Give textual form controls like
                         <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade
                         with custom styles, sizing, focus states, and more.
                    </p>
               </div>

               <div class="card-body">
                    <div>
                         <div id="spain-vector-map" style="height: 360px"></div>
                    </div>
               </div>
          </div> <!-- end card body -->
     </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/maps-vector.js',
'resources/js/pages/maps-spain.js',
'resources/js/pages/maps-russia.js',
'resources/js/pages/maps-iraq.js',
'resources/js/pages/maps-canada.js',
]); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Vector Maps'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views\maps\vector.blade.php ENDPATH**/ ?>