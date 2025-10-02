@extends('layouts.vertical', ['subtitle' => 'Placeholder'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Placeholder'])

<!-- start progress -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Default
                </h5>
                <p class="card-subtitle">
                    A progress bar can be used to show a user how far along he/she is in a process.
                </p>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card">
                            <svg class="bd-placeholder-img card-img-top" width="100%" height="180"
                                xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder"
                                preserveAspectRatio="xMidYMid slice" focusable="false">
                                <title>Placeholder</title>
                                <rect width="100%" height="100%" fill="#20c997"></rect>
                            </svg>

                            <div class="card-body">
                                <h5 class="card-title mb-2">Card title</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the
                                    bulk of the card's content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card" aria-hidden="true">
                            <svg class="bd-placeholder-img card-img-top" width="100%" height="180"
                                xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder"
                                preserveAspectRatio="xMidYMid slice" focusable="false">
                                <title>Placeholder</title>
                                <rect width="100%" height="100%" fill="#868e96"></rect>
                            </svg>

                            <div class="card-body">
                                <div class="h5 card-title placeholder-glow">
                                    <span class="placeholder col-6"></span>
                                </div>
                                <p class="card-text placeholder-glow">
                                    <span class="placeholder col-7"></span>
                                    <span class="placeholder col-4"></span>
                                    <span class="placeholder col-4"></span>
                                    <span class="placeholder col-6"></span>
                                    <span class="placeholder col-8"></span>
                                </p>
                                <a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-6"></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    How it works
                </h5>
                <p class="card-subtitle">Create placeholders with the <code>.placeholder</code> class and a grid column
                    class (e.g., <code>.col-6</code>) to set the <code>width</code>. They can replace the text inside an
                    element or be added as a modifier class to an existing component.</p>
            </div>
            <div class="card-body">
                <p aria-hidden="true">
                    <span class="placeholder col-6"></span>
                </p>

                <a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-4"
                    aria-hidden="true"></a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Color
                </h5>
                <p class="card-subtitle">By default, the <code>placeholder</code> uses <code>currentColor</code>. This
                    can be overridden with a custom color or utility class.</p>
            </div>

            <div class="card-body">
                <span class="placeholder col-12"></span>
                <span class="placeholder col-12 bg-primary"></span>
                <span class="placeholder col-12 bg-secondary"></span>
                <span class="placeholder col-12 bg-success"></span>
                <span class="placeholder col-12 bg-danger"></span>
                <span class="placeholder col-12 bg-warning"></span>
                <span class="placeholder col-12 bg-info"></span>
                <span class="placeholder col-12 bg-light"></span>
                <span class="placeholder col-12 bg-dark"></span>
            </div>
        </div> <!-- end card body -->
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Width
                </h5>
                <p class="card-subtitle">You can change the <code>width</code> through grid column classes, width
                    utilities, or inline styles.</p>
            </div>
            <div class="card-body">
                <span class="placeholder col-6"></span>
                <span class="placeholder w-75"></span>
                <span class="placeholder" style="width: 25%;"></span>
            </div>
        </div>
    </div>
</div> <!-- end row -->
<!-- end progress -->
@endsection