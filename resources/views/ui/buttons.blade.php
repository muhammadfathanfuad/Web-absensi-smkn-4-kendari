@extends('layouts.vertical', ['subtitle' => 'Buttons'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Buttons'])

<!-- start button -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Default Buttons
                </h5>
                <p class="card-subtitle">
                    Use the button classes on an <code>&lt;a&gt;</code>, <code>&lt;button&gt;</code>
                    or <code>&lt;input&gt;</code> element.
                </p>
            </div>
            <div class="card-body">

                <div class="button-list">
                    <button type="button" class="btn btn-primary">Primary</button>
                    <button type="button" class="btn btn-secondary">Secondary</button>
                    <button type="button" class="btn btn-success">Success</button>
                    <button type="button" class="btn btn-info">Info</button>
                    <button type="button" class="btn btn-warning">Warning</button>
                    <button type="button" class="btn btn-danger">Danger</button>
                    <button type="button" class="btn btn-dark">Dark</button>
                    <button type="button" class="btn btn-light">Light</button>
                    <button type="button" class="btn btn-link">Link</button>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Rounded Buttons
                </h5>
                <p class="card-subtitle">
                    Add <code>.rounded-pill</code> to default button to get rounded corners.
                </p>
            </div>
            <div class="card-body">

                <div class="button-list">
                    <button type="button" class="btn btn-primary rounded-pill">Primary</button>
                    <button type="button" class="btn btn-secondary rounded-pill">Secondary</button>
                    <button type="button" class="btn btn-success rounded-pill">Success</button>
                    <button type="button" class="btn btn-info rounded-pill">Info</button>
                    <button type="button" class="btn btn-warning rounded-pill">Warning</button>
                    <button type="button" class="btn btn-danger rounded-pill">Danger</button>
                    <button type="button" class="btn btn-dark rounded-pill">Dark</button>
                    <button type="button" class="btn btn-light rounded-pill">Light</button>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Outline Buttons
                </h5>
                <p class="card-subtitle">
                    Use a classes <code>.btn-outline-**</code> to quickly create a bordered buttons.
                </p>
            </div>
            <div class="card-body">
                <div class="button-list">
                    <button type="button" class="btn btn-outline-primary">Primary</button>
                    <button type="button" class="btn btn-outline-secondary">Secondary</button>
                    <button type="button" class="btn btn-outline-success">Success</button>
                    <button type="button" class="btn btn-outline-info">Info</button>
                    <button type="button" class="btn btn-outline-warning">Warning</button>
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Outline Rounded Buttons </h5>
                <p class="card-subtitle">
                    Use a classes <code>.btn-outline-**</code> to quickly create a bordered buttons.
                </p>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="button-list">
                        <button type="button" class="btn btn-outline-primary rounded-pill">Primary</button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill">Secondary</button>
                        <button type="button" class="btn btn-outline-success rounded-pill">Success</button>
                        <button type="button" class="btn btn-outline-info rounded-pill">Info</button>
                        <button type="button" class="btn btn-outline-warning rounded-pill">Warning</button>
                    </div>
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Soft Buttons
                </h5>
                <p class="card-subtitle">
                    Use a classes <code>.btn-soft-**</code> to quickly create buttons with soft
                    background.
                </p>
            </div>
            <div class="card-body">

                <div class="button-list">
                    <button type="button" class="btn btn-soft-primary">Primary</button>
                    <button type="button" class="btn btn-soft-secondary">Secondary</button>
                    <button type="button" class="btn btn-soft-success">Success</button>
                    <button type="button" class="btn btn-soft-info">Info</button>
                    <button type="button" class="btn btn-soft-warning">Warning</button>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Soft Rounded Buttons
                </h5>
                <p class="card-subtitle">
                    Use a classes <code>.rounded-pill**</code> with <code>.btn-soft-**</code> to
                    quickly create a Outline Soft buttons.
                </p>
            </div>
            <div class="card-body">

                <div class="button-list">
                    <button type="button" class="btn btn-soft-primary rounded-pill">Primary</button>
                    <button type="button" class="btn btn-soft-secondary rounded-pill">Secondary</button>
                    <button type="button" class="btn btn-soft-success rounded-pill">Success</button>
                    <button type="button" class="btn btn-soft-info rounded-pill">Info</button>
                    <button type="button" class="btn btn-soft-warning rounded-pill">Warning</button>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Button Width
                </h5>
                <p class="card-subtitle">
                    Create buttons with minimum width by adding add <code>.width-xs</code>,
                    <code>.width-sm</code>,
                    <code>.width-md</code>, <code>.width-lg</code> or <code>.width-xl</code>.
                </p>
            </div>
            <div class="card-body">
                <div class="button-list">
                    <button type="button" class="btn btn-primary width-xl">Extra Large</button>
                    <button type="button" class="btn btn-secondary width-lg">Large</button>
                    <button type="button" class="btn btn-success width-md">Middle</button>
                    <button type="button" class="btn btn-info width-sm">Small</button>
                    <button type="button" class="btn btn-warning width-xs">Xs</button>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Button Sizes</h5>
                <p class="card-subtitle">
                    Add <code>.btn-lg</code>, <code>.btn-sm</code> for additional sizes.
                </p>
            </div>
            <div class="card-body">

                <div class="button-list">
                    <button type="button" class="btn btn-primary btn-lg">Large</button>
                    <button type="button" class="btn btn-secondary">Normal</button>
                    <button type="button" class="btn btn-success btn-sm">Small</button>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Disabled Button
                </h5>
                <p class="card-subtitle">
                    Add <code>disabled</code> attribute to buttons.
                </p>
            </div>
            <div class="card-body">

                <div class="button-list">
                    <button type="button" class="btn btn-primary" disabled>Primary</button>
                    <button type="button" class="btn btn-secondary" disabled>Secondary</button>
                    <button type="button" class="btn btn-success" disabled>Success</button>
                    <button type="button" class="btn btn-info" disabled>Info</button>
                    <button type="button" class="btn btn-warning" disabled>Warning</button>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Icon Button
                </h5>
                <p class="card-subtitle">
                    Icon only Button.
                </p>
            </div>
            <div class="card-body">
                <div class="button-list">
                    <button type="button" class="btn btn-primary"><i class="bx bx-heart"></i></button>
                    <button type="button" class="btn btn-secondary"><i class="bx bx-user-voice"></i></button>
                    <button type="button" class="btn btn-success"><i class="bx bx-check-double"></i></button>
                    <button type="button" class="btn btn-info"><i class="bx bx-cloud me-1"></i>Cloude
                        Hosting</button>
                    <button type="button" class="btn btn-warning"><i
                            class="bx bx-info-circle me-1"></i>Warning</button>
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Button Group
                </h5>
                <p class="card-subtitle">
                    Wrap a series of buttons with <code>.btn</code> in <code>.btn-group</code>.
                </p>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <!-- btn-group horizontal -->
                        <div class="btn-group mb-1 me-1">
                            <button type="button" class="btn btn-light">Left</button>
                            <button type="button" class="btn btn-light">Middle</button>
                            <button type="button" class="btn btn-light">Right</button>
                        </div>
                        <div class="btn-group mb-1 me-1">
                            <button type="button" class="btn btn-light">1</button>
                            <button type="button" class="btn btn-light">2</button>
                            <button type="button" class="btn btn-secondary">3</button>
                            <button type="button" class="btn btn-light">4</button>
                        </div>
                        <div class="btn-group mb-1 me-1">
                            <button type="button" class="btn btn-light">5</button>
                            <button type="button" class="btn btn-secondary">6</button>
                            <button type="button" class="btn btn-light">7</button>
                            <button id="dropdown" type="button" class="btn btn-light dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown">
                                <li><a class="dropdown-item" href="javascript:void(0);">Dropdown
                                        link</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Dropdown
                                        link</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- btn-group vertical -->
                        <div class="btn-group-vertical me-4">
                            <button type="button" class="btn btn-light">Top</button>
                            <button type="button" class="btn btn-light">Middle</button>
                            <button type="button" class="btn btn-light">Bottom</button>
                        </div>
                        <div class="btn-group-vertical">
                            <button type="button" class="btn btn-light">Button 1</button>
                            <button type="button" class="btn btn-light">Button 2</button>
                            <button id="verticalDropdown" type="button" class="btn btn-light dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Button 3
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="verticalDropdown">
                                <li><a class="dropdown-item" href="javascript:void(0);">Dropdown
                                        link</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Dropdown
                                        link</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Block Button
                </h5>
                <p class="card-subtitle">
                    Create block level buttons by adding class <code>.d-grid</code> to parent div.
                </p>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg">Block Button</button>
                    <button type="button" class="btn btn-secondary">Block Button</button>
                    <button type="button" class="btn btn-light btn-sm">Block Button</button>
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

</div> <!-- end row -->
<!-- end button -->
@endsection