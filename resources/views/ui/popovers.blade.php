@extends('layouts.vertical', ['subtitle' => 'Popovers'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Popovers'])

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Live demo
                </h5>
                <p class="card-subtitle">We use JavaScript similar to the snippet above to render the
                    following live popover. Titles are set via <code>data-bs-title</code> and body
                    content is set via <code>data-bs-content</code>.</p>
            </div>
            <div class="card-body">
                <div>
                    <button type="button" class="btn btn-danger" data-bs-toggle="popover" title="Popover title"
                        data-bs-content="And here's some amazing content. It's very engaging. Right?">Click
                        to toggle popover</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Popover Directions
                </h5>
                <p class="card-subtitle"> Four options are available: top, right, bottom, and left
                    aligned.</p>
            </div>
            <div class="card-body">

                <div class="d-flex flex-wrap gap-2">
                    <!-- Top Position -->
                    <button type="button" class="btn btn-primary" data-bs-container="body" data-bs-toggle="popover"
                        data-bs-placement="top"
                        data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
                        Popover on top
                    </button>

                    <!-- Bottom Position -->
                    <button type="button" class="btn btn-primary" data-bs-container="body" data-bs-toggle="popover"
                        data-bs-placement="bottom"
                        data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
                        Popover on bottom
                    </button>

                    <!-- Left Position -->
                    <button type="button" class="btn btn-primary" data-bs-container="body" data-bs-toggle="popover"
                        data-bs-placement="left"
                        data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."
                        title="Popover title">
                        Popover on left
                    </button>

                    <!-- Right Position -->
                    <button type="button" class="btn btn-primary" data-bs-container="body" data-bs-toggle="popover"
                        data-bs-placement="right"
                        data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
                        Popover on right
                    </button>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Dismiss on Next Click
                </h5>
                <p class="card-subtitle">Use the <code>focus</code> trigger to dismiss popovers
                    on the user’s next click of a different element than the toggle element.</p>
            </div>
            <div class="card-body">
                <button type="button" tabindex="0" class="btn btn-success" data-bs-toggle="popover"
                    data-bs-trigger="focus"
                    data-bs-content="And here's some amazing content. It's very engaging. Right?"
                    title="Dismissible popover">
                    Dismissible popover
                </button>

            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Hover
                </h5>
                <p class="card-subtitle">Use the <code>data-bs-trigger="hover"</code> trigger Hover to
                    show popover.</p>
            </div>
            <div class="card-body">
                <button type="button" tabindex="0" class="btn btn-dark" data-bs-toggle="popover"
                    data-bs-trigger="hover"
                    data-bs-content="And here's some amazing content. It's very engaging. Right?" title="Ohh Wow !">
                    Please Hover Me
                </button>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Custom Popovers
                </h5>
                <p class="card-subtitle"> ou can customize the appearance of popovers using CSS
                    variables. We set a custom class with
                    <code>data-bs-custom-class="primary-popover"</code> to scope our custom appearance
                    and use it to override some of the
                    local CSS variables.
                </p>
            </div>
            <div class="card-body">
                <div class="button-list">
                    <button type="button" class="btn btn-primary" data-bs-toggle="popover" data-bs-placement="top"
                        data-bs-custom-class="primary-popover" data-bs-title="Primary popover"
                        data-bs-content="This popover is themed via CSS variables.">
                        Primary popover
                    </button>

                    <button type="button" class="btn btn-success" data-bs-toggle="popover" data-bs-placement="top"
                        data-bs-custom-class="success-popover" data-bs-title="Success popover"
                        data-bs-content="This popover is themed via CSS variables.">
                        Success popover
                    </button>

                    <button type="button" class="btn btn-danger" data-bs-toggle="popover" data-bs-placement="top"
                        data-bs-custom-class="danger-popover" data-bs-title="Danger popover"
                        data-bs-content="This popover is themed via CSS variables.">
                        Danger popover
                    </button>

                    <button type="button" class="btn btn-info" data-bs-toggle="popover" data-bs-placement="top"
                        data-bs-custom-class="info-popover" data-bs-title="Info popover"
                        data-bs-content="This popover is themed via CSS variables.">
                        Info popover
                    </button>
                </div>

            </div>
        </div>

    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Disabled Elements
                </h5>
                <p class="card-subtitle">Elements with the <code>disabled</code> attribute
                    aren’t interactive, meaning users cannot hover or click them to trigger a
                    popover (or tooltip). As a workaround, you’ll want to trigger the popover from a
                    wrapper <code>&lt;div&gt;</code> or <code>&lt;span&gt;</code> and override the
                    <code>pointer-events</code> on the disabled element.
                </p>
            </div>
            <div class="card-body">


                <span class="d-inline-block" data-bs-toggle="popover" data-bs-trigger="hover"
                    data-bs-content="Disabled popover">
                    <button class="btn btn-primary" style="pointer-events: none;" type="button" disabled>Disabled
                        button</button>
                </span>

            </div>

        </div> <!-- end card body -->
    </div> <!-- end card -->

</div> <!-- end row -->
@endsection