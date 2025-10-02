@extends('layouts.vertical', ['subtitle' => 'Spinners'])


@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Spinners'])

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Border Spinners
                </h5>
                <p class="card-subtitle">
                    Use the border spinners for a lightweight loading indicator.
                </p>
            </div>
            <div class="card-body">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Color Spinners
                </h5>
                <p class="card-subtitle">You can use any of our text color utilities on the standard spinner.</p>
            </div>
            <div class="card-body">
                <div class="spinner-border text-primary me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-border text-secondar me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-border text-success me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-border text-info me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-border text-warning me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-border text-danger me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Growing Spinners
                </h5>
                <p class="card-subtitle">If you don’t fancy a border spinner, switch to the grow spinner. While it
                    doesn’t technically spin, it does repeatedly grow!</p>
            </div>
            <div class="card-body">
                <div class="spinner-grow text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Color Growing Spinners
                </h5>
                <p class="card-subtitle">You can use any of our text color utilities on the standard spinner.</p>
            </div>
            <div class="card-body">
                <div class="spinner-grow text-primary me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-secondary me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-success me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-info me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-warning me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-danger me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Alignment
                </h5>
                <p class="card-subtitle">
                    Use flexbox utilities, float utilities, or text alignment utilities to place spinners exactly
                    where you need them in any situation.
                </p>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center border p-2">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Size
                </h5>
                <p class="card-subtitle">
                    Add <code>.spinner-border-sm</code> and <code>.spinner-border.sm-**</code> to make a smaller spinner
                    that can quickly be used within other components.
                </p>
            </div>
            <div class="card-body">

                <div class="spinner-border text-primary spinner-border-sm me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-primary spinner-grow-sm me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-border text-primary spinner-border me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-primary spinner-grow me-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Placement
                </h5>
                <p class="card-subtitle">
                    Use <code>flexbox utilities</code>, <code>float utilities</code>, or <code>text alignment</code>
                    utilities to place spinners
                    exactly where you need them in any situation.
                </p>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center border p-2">
                    <strong>Loading...</strong>
                    <div class="spinner-border text-primary ms-auto" role="status" aria-hidden="true"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Buttons Spinner
                </h5>
                <p class="card-subtitle">
                    Use spinners within buttons to indicate an action is currently processing or taking place.
                    You may also swap the text out of the spinner element and utilize button text as needed.
                </p>
            </div>
            <div class="card-body">
                <button class="btn btn-primary me-1" type="button" disabled>
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <button class="btn btn-primary" type="button" disabled>
                    <span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div>

</div> <!-- end row -->
@endsection