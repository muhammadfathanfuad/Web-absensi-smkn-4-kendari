@extends('layouts.vertical', ['subtitle' => 'Alerts'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Alerts'])

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Basic Example</h5>
                <p class="card-subtitle">
                    Provide contextual feedback messages for typical user actions with the handful of available and
                    flexible alert
                    messages. Alerts are available for any length of text, as well as an optional dismiss button.
                </p>
            </div>
            <div class="card-body">
                <div class="alert alert-primary" role="alert">
                    A simple primary alert—check it out!
                </div>
                <div class="alert alert-secondary" role="alert">
                    A simple secondary alert—check it out!
                </div>
                <div class="alert alert-success" role="alert">
                    A simple success alert—check it out!
                </div>
                <div class="alert alert-danger" role="alert">
                    A simple danger alert—check it out!
                </div>
                <div class="alert alert-warning" role="alert">
                    A simple warning alert—check it out!
                </div>
                <div class="alert alert-info" role="alert">
                    A simple info alert—check it out!
                </div>
                <div class="alert alert-light" role="alert">
                    A simple light alert—check it out!
                </div>
                <div class="alert alert-dark mb-0" role="alert">
                    A simple dark alert—check it out!
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Dismissible Alerts Example
                </h5>
                <p class="card-subtitle">
                    Add a dismiss button and the <code>.alert-dismissible</code> class, which adds extra padding to the
                    right of the alert and
                    positions the <code>.btn-close</code> button.
                </p>
            </div>
            <div class="card-body">
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    A simple primary alert—check it out!
                </div>
                <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    A simple secondary alert—check it out!
                </div>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    A simple success alert—check it out!
                </div>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    A simple danger alert—check it out!
                </div>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    A simple warning alert—check it out!
                </div>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    A simple info alert—check it out!
                </div>
                <div class="alert alert-light alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    A simple light alert—check it out!
                </div>
                <div class="alert alert-dark alert-dismissible fade show mb-0" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    A simple dark alert—check it out!
                </div>
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Alert Link Example
                </h5>
                <p class="card-subtitle">
                    Use the <code>.alert-link</code> utility class to quickly provide matching colored links within any
                    alert.
                </p>
            </div>
            <div class="card-body">

                <div class="alert alert-primary" role="alert">
                    A simple primary alert with <a href="javascript:void(0);" class="alert-link">an example link</a>.
                    Give it a click if you like.
                </div>
                <div class="alert alert-secondary" role="alert">
                    A simple secondary alert with <a href="javascript:void(0);" class="alert-link">an example link</a>.
                    Give it a click if you like.
                </div>
                <div class="alert alert-success" role="alert">
                    A simple success alert with <a href="javascript:void(0);" class="alert-link">an example link</a>.
                    Give it a click if you like.
                </div>
                <div class="alert alert-danger mb-0" role="alert">
                    A simple danger alert with <a href="javascript:void(0);" class="alert-link">an example link</a>.
                    Give it a click if you like.
                </div>
            </div>
        </div>
    </div><!-- end col -->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Icons Alert Example
                </h5>
                <p class="card-subtitle">
                    You can also include additional elements like icons, heading, etc along side the actual message.
                </p>
            </div>
            <div class="card-body">

                <div class="alert alert-primary alert-icon" role="alert">
                    <div class="d-flex align-items-center">
                        <div
                            class="avatar-sm rounded bg-primary d-flex justify-content-center align-items-center fs-18 me-2 flex-shrink-0">
                            <i class="bx bx-info-circle text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            A simple primary alert—check it out!
                        </div>
                    </div>
                </div>
                <div class="alert alert-secondary alert-icon" role="alert">
                    <div class="d-flex align-items-center">
                        <div
                            class="avatar-sm rounded bg-secondary d-flex justify-content-center align-items-center fs-18 me-2 flex-shrink-0">
                            <i class="bx bx-x-circle text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            A simple secondary alert—check it out!
                        </div>
                    </div>
                </div>
                <div class="alert alert-success alert-icon" role="alert">
                    <div class="d-flex align-items-center">
                        <div
                            class="avatar-sm rounded bg-success d-flex justify-content-center align-items-center fs-18 me-2 flex-shrink-0">
                            <i class="bx bx-check-shield text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            A simple success alert—check it out!
                        </div>
                    </div>
                </div>
                <div class="alert alert-danger alert-icon mb-0" role="alert">
                    <div class="d-flex align-items-center">
                        <div
                            class="avatar-sm rounded bg-danger d-flex justify-content-center align-items-center fs-18 me-2 flex-shrink-0">
                            <i class="bx bx-info-circle text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            A simple danger alert—check it out!
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div><!-- end col -->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Additional Content Alert Example
                </h5>
                <p class="card-subtitle">
                    Alerts can also contain additional HTML elements like headings, paragraphs and dividers.
                </p>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-xl-6">
                        <div class="alert alert-primary mb-3 p-3 mb-xl-0" role="alert">
                            <h4 class="alert-heading">Well done!</h4>
                            <p class="mb-0">Aww yeah, you successfully read this important alert message. This
                                example text is going to run a bit longer so that you can see how spacing within an
                                alert works with this kind of content.</p>
                            <hr>
                            <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice
                                and tidy.</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="alert alert-secondary p-3 mb-0" role="alert">
                            <h4 class="alert-heading">Well done!</h4>
                            <p class="mb-0">Aww yeah, you successfully read this important alert message. This
                                example text is going to run a bit longer so that you can see how spacing within an
                                alert works with this kind of content.</p>
                            <hr>
                            <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice
                                and tidy.</p>
                        </div>
                    </div>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Live example
                </h5>
                <p class="card-subtitle">
                    Click the button below to show an alert (hidden with inline styles to start), then dismiss (and
                    destroy) it with the built-in close button.
                </p>
            </div>
            <div class="card-body">
                <div id="liveAlertPlaceholder"></div>
                <button type="button" class="btn btn-primary" id="liveAlertBtn">Show live alert</button>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection