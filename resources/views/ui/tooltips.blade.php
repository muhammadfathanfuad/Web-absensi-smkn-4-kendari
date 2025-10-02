@extends('layouts.vertical', ['subtitle' => 'Tooltips'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Tooltips'])

<!-- start tooltips-->
<div class="row">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Tooltip Direction
                </h5>
                <p class="card-subtitle">
                    Four options are available: top, right, bottom, and left aligned.
                </p>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="Tooltip on top">
                        Tooltip on top
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-title="Tooltip on right">
                        Tooltip on right
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        data-bs-title="Tooltip on bottom">
                        Tooltip on bottom
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="left"
                        data-bs-title="Tooltip on left">
                        Tooltip on left
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Color Tooltip
                </h5>
                <p class="card-subtitle">We set a custom class with ex.
                    <code>data-bs-custom-class="primary-tooltip"</code> to scope our background-color
                    primary appearance and use it to override a local CSS variable.
                </p>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-custom-class="primary-tooltip"
                        data-bs-title="This top tooltip is themed via CSS variables.">
                        Primary tooltip
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-custom-class="danger-tooltip"
                        data-bs-title="This top tooltip is themed via CSS variables.">
                        Danger tooltip
                    </button>
                    <button type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-custom-class="info-tooltip"
                        data-bs-title="This top tooltip is themed via CSS variables.">
                        Info tooltip
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-custom-class="success-tooltip"
                        data-bs-title="This top tooltip is themed via CSS variables.">
                        Success tooltip
                    </button>
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Tooltips on links
                </h5>
                <p class="card-subtitle">
                    Hover over the links below to see tooltips:
                </p>
            </div>
            <div class="card-body">
                <p class="muted">Placeholder text to demonstrate some <a href="#" class="link-danger"
                        data-bs-toggle="tooltip" data-bs-title="Default tooltip">inline links</a> with
                    tooltips. This is now just filler, no killer. Content placed here just to mimic the
                    presence of <a href="#" class="link-danger" data-bs-toggle="tooltip"
                        data-bs-title="Another tooltip">real text</a>. And all that just to give you an
                    idea of how tooltips would look when used in real-world situations. So hopefully
                    you've now seen how <a href="#" class="link-danger" data-bs-toggle="tooltip"
                        data-bs-title="Another one here too">these tooltips on links</a> can work in
                    practice, once you use them on <a href="#" class="link-danger" data-bs-toggle="tooltip"
                        data-bs-title="The last tip!">your own</a> site or
                    project.</p>
            </div>
        </div>
    </div>
</div> <!-- end row -->
<!-- start tooltips-->
@endsection