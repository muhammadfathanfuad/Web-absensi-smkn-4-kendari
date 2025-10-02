@extends('layouts.vertical', ['subtitle' => '404'])

@section('content')
<!-- Start Container Fluid -->
<div class="container-xxl">

    <!-- Start here.... -->
    <div class="row justify-content-center">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-body px-3 py-5">
                    <div class="p-4">
                        <div class="mx-auto mb-4 text-center">

                            <h1 class="mb-3 fw-bold fs-60">404</h1>
                            <h2 class="fs-22 lh-base">Page Not Found !</h2>
                            <p class="text-muted mt-1 mb-4">The page you're trying to reach seems to have gone <br />
                                missing in the digital wilderness.</p>

                            <div class="text-center">
                                <a href="{{ route('any', 'index') }}" class="btn btn-success">Back to Home</a>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->

        </div> <!-- end col -->
    </div> <!-- end row -->

</div>
@endsection