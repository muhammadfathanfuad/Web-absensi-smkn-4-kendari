@extends('layouts.vertical', ['subtitle' => 'Cards'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Cards'])

<!-- start cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 mb-xl-0">
            <img class="card-img-top img-fluid" src="/images/small/img-1.jpg" alt="img-1">
            <div class="card-body">
                <h5 class="card-title mb-2">Card title</h5>
                <p class="card-text text-muted">
                    Some quick example text to build on the card title and make
                    up the bulk of the card's content. With supporting text below as
                    a natural lead-in to additional content.
                </p>
                <a href="javascript:void(0);" class="btn btn-primary">Button</a>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3">
            <img class="card-img-top img-fluid" src="/images/small/img-2.jpg" alt="img-2">
            <div class="card-body">
                <h5 class="card-title mb-2">Card title</h5>
                <p class="card-text text-muted">Some quick example text to build on the card title.</p>
            </div> <!-- end card body -->
            <ul class="list-group list-group-flush text-muted">
                <li class="list-group-item text-muted">Dapibus ac facilisis in</li>
            </ul>
            <div class="card-body">
                <a href="javascript:void(0);" class="card-link text-primary">Card link</a>
                <a href="javascript:void(0);" class="card-link text-primary">Another link</a>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 mb-xl-0">
            <img class="card-img-top img-fluid" src="/images/small/img-4.jpg" alt="img-4">
            <div class="card-body">
                <p class="card-text text-muted">
                    Some quick example text to build on the card title and make
                    up the bulk of the card's content. With supporting text below as
                    a natural lead-in to additional content.
                </p>
                <a href="javascript:void(0);" class="btn btn-primary">Button</a>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
    <div class="col-xl-3 col-md-6">
        <div class="card mb-3 mb-xl-0">
            <div class="card-body">
                <h5 class="card-title mb-0">Card title</h5>
            </div>
            <img class="img-fluid" src="/images/small/img-5.jpg" alt="img-5">
            <div class="card-body">
                <p class="card-text text-muted">Some quick example text to build on the card title.</p>
                <a href="javascript:void(0);" class="card-link text-primary">Card link</a>
                <a href="javascript:void(0);" class="card-link text-primary">Another link</a>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection