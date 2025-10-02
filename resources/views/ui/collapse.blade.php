@extends('layouts.vertical', ['subtitle' => 'Collapse'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Collapse'])

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Default Example
                </h5>
                <p class="card-subtitle">Click the buttons below to show and hide another element via class changes:</p>
            </div>
            <div class="card-body">
                <ul>
                    <li><code>.collapse</code> hides content</li>
                    <li><code>.collapsing</code> is applied during transitions</li>
                    <li><code>.collapse.show</code> shows content</li>
                </ul>
                <p class="text-muted">Generally, we recommend using a button with the <code>data-bs-target</code>
                    attribute. While not recommended from a semantic point of view, you can also use a link with the
                    <code>href</code> attribute (and a <code>role="button"</code>). In both cases, the
                    <code>data-bs-toggle="collapse"</code> is required.
                </p>

                <div class="hstack gap-2">
                    <a class="btn btn-primary mb-2" data-bs-toggle="collapse" href="#collapseExample" role="button"
                        aria-expanded="false" aria-controls="collapseExample">
                        Link with href
                    </a>
                    <button class="btn btn-primary mb-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Button with data-bs-target
                    </button>
                </div>
                <div class="collapse" id="collapseExample">
                    <div class="card mb-0">
                        <div class="card-body">
                            Some placeholder content for the collapse component. This panel is hidden by default but
                            revealed when the
                            user activates the relevant trigger.
                        </div>
                    </div>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->

    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title ">
                    Horizontal
                </h5>
                <p class="card-subtitle">The collapse plugin also supports horizontal collapsing. Add the
                    <code>.collapse-horizontal</code> modifier class to transition the <code>width</code> instead of
                    <code>height</code> and set a <code>width</code> on the immediate child element. Feel free to write
                    your own custom Sass, use inline styles, or use our width utilities.
                </p>
            </div>
            <div class="card-body">

                <button class="btn btn-primary mb-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                    Toggle width collapse
                </button>
                <div style="min-height: 135px;">
                    <div class="collapse collapse-horizontal" id="collapseWidthExample">
                        <div class="card card-body mb-0" style="width: 300px;">
                            This is some placeholder content for a horizontal collapse. It's hidden by default and shown
                            when triggered.
                        </div>
                    </div>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Multiple Targets
                </h5>
                <p class="card-subtitle">
                    A <code>&lt;button&gt;</code> or <code>&lt;a&gt;</code> can show and hide multiple elements by
                    referencing them with a selector in its <code>href</code> or <code>data-bs-target</code> attribute.
                    Multiple <code>&lt;button&gt;</code> or <code>&lt;a&gt;</code> can show and hide an element if they
                    each reference it with their <code>href</code> or <code>data-bs-target</code> attribute</p>
            </div>
            <div class="card-body">

                <div class="hstack gap-2 mb-3">
                    <a class="btn btn-primary" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button"
                        aria-expanded="false" aria-controls="multiCollapseExample1">Toggle first element</a>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#multiCollapseExample2" aria-expanded="false"
                        aria-controls="multiCollapseExample2">Toggle second element</button>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                        data-bs-target=".multi-collapse" aria-expanded="false"
                        aria-controls="multiCollapseExample1 multiCollapseExample2">Toggle both elements</button>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="collapse multi-collapse" id="multiCollapseExample1">
                            <div class="card card-body">
                                Some placeholder content for the first collapse component of this multi-collapse
                                example. This panel
                                is hidden by default but revealed when the user activates the relevant trigger.
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="collapse multi-collapse" id="multiCollapseExample2">
                            <div class="card card-body">
                                Some placeholder content for the second collapse component of this multi-collapse
                                example. This
                                panel is hidden by default but revealed when the user activates the relevant trigger.
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

</div> <!-- end row -->
@endsection