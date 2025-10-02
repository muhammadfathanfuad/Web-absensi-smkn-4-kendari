@extends('layouts.vertical', ['subtitle' => 'Avatars'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Avatars'])

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Basic Example
                </h5>
                <p class="card-subtitle">
                    Create and group avatars of different sizes and shapes with the css classes.
                    Using Bootstrap's naming convention, you can control size of avatar including standard avatar, or
                    scale it up to different sizes.
                </p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <img src="/images/users/avatar-2.jpg" alt="image" class="img-fluid avatar-xs rounded">
                        <p>
                            <code>.avatar-xs</code>
                        </p>
                        <img src="/images/users/avatar-3.jpg" alt="image" class="img-fluid avatar-sm rounded mt-2">
                        <p class="mb-2 mb-sm-0">
                            <code>.avatar-sm</code>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <img src="/images/users/avatar-4.jpg" alt="image" class="img-fluid avatar-md rounded" />
                        <p>
                            <code>.avatar-md</code>
                        </p>
                    </div>

                    <div class="col-md-3">
                        <img src="/images/users/avatar-5.jpg" alt="image" class="img-fluid avatar-lg rounded" />
                        <p>
                            <code>.avatar-lg</code>
                        </p>
                    </div>

                    <div class="col-md-3">
                        <img src="/images/users/avatar-6.jpg" alt="image" class="img-fluid avatar-xl rounded" />
                        <p class="mb-0">
                            <code>.avatar-xl</code>
                        </p>
                    </div>
                </div> <!-- end row-->
            </div>
        </div>
    </div> <!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Rounded Circle
                </h5>
                <p class="card-subtitle">
                    Using an additional class <code>.rounded-circle</code> in <code>&lt;img&gt;</code> element creates
                    the rounded avatar.
                </p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="/images/users/avatar-7.jpg" alt="image"
                            class="img-fluid avatar-md rounded-circle" />
                        <p class="mt-1">
                            <code>.avatar-md .rounded-circle</code>
                        </p>
                    </div>

                    <div class="col-md-4">
                        <img src="/images/users/avatar-8.jpg" alt="image"
                            class="img-fluid avatar-lg rounded-circle" />
                        <p>
                            <code>.avatar-lg .rounded-circle</code>
                        </p>
                    </div>

                    <div class="col-md-4">
                        <img src="/images/users/avatar-9.jpg" alt="image"
                            class="img-fluid avatar-xl rounded-circle" />
                        <p class="mb-0">
                            <code>.avatar-xl .rounded-circle</code>
                        </p>
                    </div>
                </div> <!-- end row-->
            </div>
        </div>

    </div> <!-- end col -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Images Shapes</h5>
                <p class="card-subtitle"> Avatars with different sizes and shapes.</p>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 align-items-end">
                    <div>
                        <img src="/images/small/img-2.jpg" alt="image" class="img-fluid rounded" width="200" />
                        <p class="mb-0">
                            <code>.rounded</code>
                        </p>
                    </div>

                    <div>
                        <img src="/images/users/avatar-5.jpg" alt="image" class="img-fluid rounded" width="120" />
                        <p class="mb-0">
                            <code>.rounded</code>
                        </p>
                    </div>

                    <div>
                        <img src="/images/users/avatar-7.jpg" alt="image" class="img-fluid rounded-circle"
                            width="120" />
                        <p class="mb-0">
                            <code>.rounded-circle</code>
                        </p>
                    </div>

                    <div>
                        <img src="/images/small/img-3.jpg" alt="image" class="img-fluid img-thumbnail"
                            width="200" />
                        <p class="mb-0">
                            <code>.img-thumbnail</code>
                        </p>
                    </div>

                    <div>
                        <img src="/images/users/avatar-8.jpg" alt="image"
                            class="img-fluid rounded-circle img-thumbnail" width="120" />
                        <p class="mb-0">
                            <code>.rounded-circle .img-thumbnail</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection