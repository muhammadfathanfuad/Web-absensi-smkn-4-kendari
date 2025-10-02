@extends('layouts.vertical', ['subtitle' => 'Badge'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Badge'])
<!-- start badges -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Heading
                </h5>
                <p class="card-subtitle">
                    Provide contextual feedback messages for typical user actions with the handful of available and
                    flexible alert
                    messages. Alerts are available for any length of text, as well as an optional dismiss button.
                </p>
            </div>
            <div class="card-body">
                <h1>h1.Example heading <span class="badge bg-primary">New</span></h1>
                <h2>h2.Example heading <span class="badge bg-secondary">New</span></h2>
                <h3>h3.Example heading <span class="badge bg-success">New</span></h3>
                <h4>h4.Example heading <span class="badge bg-info">New</span></h4>
                <h5>h5.Example heading <span class="badge bg-warning">New</span></h5>
                <h6 class="mb-0">h6.Example heading <span class="badge bg-danger">New</span></h6>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Outline & Outline Pill Badges
                </h5>
                <p class="card-subtitle">
                    Using the <code>.badge-outline-**</code> to quickly create a bordered badges.
                </p>
            </div>
            <div class="card-body">
                <!-- Outline Badges -->
                <div class="mb-2">
                    <span class="badge badge-outline-primary me-1">Primary</span>
                    <span class="badge badge-outline-secondary me-1">Secondary</span>
                    <span class="badge badge-outline-success me-1">Success</span>
                    <span class="badge badge-outline-info me-1">Info</span>
                    <span class="badge badge-outline-warning me-1">Warning</span>
                    <span class="badge badge-outline-danger me-1">Danger</span>
                    <span class="badge badge-outline-dark me-1">Dark</span>
                    <span class="badge badge-outline-purple me-1">Purple</span>
                    <span class="badge badge-outline-pink me-1">Pink</span>
                    <span class="badge badge-outline-orange me-1">Orange</span>
                </div>
                <!-- Outline Pill Badges -->
                <div>
                    <span class="badge badge-outline-primary rounded-pill me-1">Primary</span>
                    <span class="badge badge-outline-secondary rounded-pill me-1">Secondary</span>
                    <span class="badge badge-outline-success rounded-pill me-1">Success</span>
                    <span class="badge badge-outline-info rounded-pill me-1">Info</span>
                    <span class="badge badge-outline-warning rounded-pill me-1">Warning</span>
                    <span class="badge badge-outline-danger rounded-pill me-1">Danger</span>
                    <span class="badge badge-outline-dark rounded-pill me-1">Dark</span>
                    <span class="badge badge-outline-purple rounded-pill me-1">Purple</span>
                    <span class="badge badge-outline-pink rounded-pill me-1">Pink</span>
                    <span class="badge badge-outline-orange rounded-pill me-1">Orange</span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Soft & Soft Pill Badges
                </h5>
                <p class="card-subtitle">
                    Using the <code>.badge-soft-**</code> modifier class, you can have more soften variation.
                </p>
            </div>
            <div class="card-body">
                <!--  Soft Badges -->
                <div class="mb-2">
                    <span class="badge badge-soft-primary me-1">Primary</span>
                    <span class="badge badge-soft-secondary me-1">Secondary</span>
                    <span class="badge badge-soft-success me-1">Success</span>
                    <span class="badge badge-soft-info me-1">Info</span>
                    <span class="badge badge-soft-warning me-1">Warning</span>
                    <span class="badge badge-soft-danger me-1">Danger</span>
                    <span class="badge badge-soft-dark me-1">Dark</span>
                    <span class="badge badge-soft-purple me-1">Purple</span>
                    <span class="badge badge-soft-pink me-1">Pink</span>
                    <span class="badge badge-soft-orange me-1">Orange</span>
                </div>
                <!--  Soft Pill Badges -->
                <div>
                    <span class="badge badge-soft-primary rounded-pill me-1">Primary</span>
                    <span class="badge badge-soft-secondary rounded-pill me-1">Secondary</span>
                    <span class="badge badge-soft-success rounded-pill me-1">Success</span>
                    <span class="badge badge-soft-info rounded-pill me-1">Info</span>
                    <span class="badge badge-soft-warning rounded-pill me-1">Warning</span>
                    <span class="badge badge-soft-danger rounded-pill me-1">Danger</span>
                    <span class="badge badge-soft-dark rounded-pill me-1">Dark</span>
                    <span class="badge badge-soft-purple rounded-pill me-1">Purple</span>
                    <span class="badge badge-soft-pink rounded-pill me-1">Pink</span>
                    <span class="badge badge-soft-orange rounded-pill me-1">Orange</span>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Default & Pill Badges
                </h5>
                <p class="card-subtitle">
                    Use our background utility classes to quickly change the appearance of a badge.
                    And use the <code>.rounded-pill</code> class to make badges more rounded.
                </p>
            </div>
            <div class="card-body">
                <!-- Default Badges -->
                <div class="mb-2">
                    <span class="badge bg-primary me-1">Primary</span>
                    <span class="badge bg-secondary me-1">Secondary</span>
                    <span class="badge bg-success me-1">Success</span>
                    <span class="badge bg-info me-1">Info</span>
                    <span class="badge bg-warning me-1">Warning</span>
                    <span class="badge bg-danger me-1">Danger</span>
                    <span class="badge bg-dark me-1">Dark</span>
                    <span class="badge bg-purple me-1">Purple</span>
                    <span class="badge bg-pink me-1">Pink</span>
                    <span class="badge bg-orange me-1">Orange</span>
                </div>
                <!-- Pill Badges -->
                <div>
                    <span class="badge bg-primary rounded-pill me-1">Primary</span>
                    <span class="badge bg-secondary rounded-pill me-1">Secondary</span>
                    <span class="badge bg-success rounded-pill me-1">Success</span>
                    <span class="badge bg-info rounded-pill me-1">Info</span>
                    <span class="badge bg-warning rounded-pill me-1">Warning</span>
                    <span class="badge bg-danger rounded-pill me-1">Danger</span>
                    <span class="badge bg-dark rounded-pill me-1">Dark</span>
                    <span class="badge bg-purple rounded-pill me-1">Purple</span>
                    <span class="badge bg-pink rounded-pill me-1">Pink</span>
                    <span class="badge bg-orange rounded-pill me-1">Orange</span>
                </div>
            </div>
        </div>


    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Buttons & Position
                </h5>
                <p class="card-subtitle">
                    Alerts can also contain additional HTML elements like headings, paragraphs and dividers.
                </p>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <button type="button" class="btn btn-primary me-1 mb-1">
                        Notifications <span class="badge bg-danger ms-1">4</span>
                    </button>
                    <button type="button" class="btn btn-outline-primary me-1 mb-1">
                        Notifications <span class="badge bg-primary ms-1">new</span>
                    </button>
                    <button type="button" class="btn btn-soft-primary me-1 mb-1">
                        Notifications <span class="badge bg-primary ms-1">11</span>
                    </button>
                    <a href="javascript:void(0);" class="btn me-1 mb-1">
                        Notifications <span class="badge bg-primary ms-1">90+</span>
                    </a>
                </div>

                <div>
                    <button type="button" class="btn btn-primary position-relative me-3">
                        Inbox
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">99+</span>
                    </button>
                    <button type="button" class="btn btn-primary position-relative">
                        Profile
                        <span
                            class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </button>
                </div>

            </div> <!-- end card body -->
        </div>
    </div>
</div> <!-- end row -->
<!-- end badges -->
@endsection