@extends('layouts.vertical', ['subtitle' => 'Google Maps'])

@section('content')

@include('layouts.partials.page-title', ['title' => 'Maps', 'subtitle' => 'Google Maps'])

<div class="row row-cols-lg-2 gx-3">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Basic Example</h5>
                <p class="card-subtitle">Give textual form controls like
                    <code>&lt;input&gt;</code>s and
                    <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus
                    states,
                    and more.
                </p>
            </div>

            <div class="card-body">
                <div>
                    <div class="mb-3">
                        <div id="gmaps-basic" class="gmaps"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Markers Google Map</h5>
                <p class="card-subtitle">Give textual form controls like
                    <code>&lt;input&gt;</code>s and
                    <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus
                    states,
                    and more.
                </p>
            </div>

            <div class="card-body">
                <div>
                    <div class="mb-3">
                        <div id="gmaps-markers" class="gmaps"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Street View Panoramas Google Map</h5>
                <p class="card-subtitle">Give textual form controls like
                    <code>&lt;input&gt;</code>s and
                    <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus
                    states,
                    and more.
                </p>
            </div>

            <div class="card-body">
                <div>
                    <div id="panorama" class="gmaps"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Google Map Types</h5>
                <p class="card-subtitle">Give textual form controls like
                    <code>&lt;input&gt;</code>s and
                    <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus
                    states,
                    and more.
                </p>
            </div>

            <div class="card-body">
                <div>
                    <div id="gmaps-types" class="gmaps"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Ultra Light With Labels</h5>
                <p class="card-subtitle">Give textual form controls like
                    <code>&lt;input&gt;</code>s and
                    <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus
                    states,
                    and more.
                </p>
            </div>

            <div class="card-body">
                <div>
                    <div id="ultra-light" class="gmaps"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Dark</h5>
                <p class="card-subtitle">Give textual form controls like
                    <code>&lt;input&gt;</code>s and
                    <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus
                    states,
                    and more.
                </p>
            </div>

            <div class="card-body">
                <div>
                    <div id="dark" class="gmaps"></div>
                </div>
            </div> <!-- end card body -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Google Maps API -->
<script src="http://maps.google.com/maps/api/js"></script>
@vite(['resources/js/pages/maps-google.js'])
@endsection