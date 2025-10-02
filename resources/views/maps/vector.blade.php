@extends('layouts.vertical', ['subtitle' => 'Vector Maps'])

@section('content')

@include('layouts.partials.page-title', ['title' => 'Maps', 'subtitle' => 'Vector Maps'])
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

@endsection

@section('scripts')
@vite(['resources/js/pages/maps-vector.js',
'resources/js/pages/maps-spain.js',
'resources/js/pages/maps-russia.js',
'resources/js/pages/maps-iraq.js',
'resources/js/pages/maps-canada.js',
])
@endsection