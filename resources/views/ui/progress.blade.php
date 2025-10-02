@extends('layouts.vertical', ['subtitle' => 'Progress'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Base UI', 'subtitle' => 'Progress'])

<!-- start progress -->
<div class="row">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    How it works
                </h5>
                <p class="card-subtitle">
                    A progress bar can be used to show a user how far along he/she is in a process.
                </p>
            </div>
            <div class="card-body">
                <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: 35%" aria-valuenow="25" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                        aria-valuemax="100">25%</div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Backgrounds Color
                </h5>
                <p class="card-subtitle"> Use background utility classes to change the appearance of individual progress
                    bars.</p>
            </div>
            <div class="card-body">
                <div class="progress mb-2">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 25%" aria-valuenow="25"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: 50%" aria-valuenow="50"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0"
                        aria-valuemax="100"></div>
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: 30%" aria-valuenow="30"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    <div class="progress-bar bg-success" role="progressbar" style="width: 20%" aria-valuenow="20"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Striped Progress Bar
                </h5>
                <p class="card-subtitle">Add <code>.progress-bar-striped</code> to any <code>.progress-bar</code> to
                    apply a stripe via CSS gradient
                    over the progress bar’s background color.</p>
            </div>
            <div class="card-body">
                <div class="progress mb-2">
                    <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" style="width: 25%"
                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar bg-secondary progress-bar-striped" role="progressbar" style="width: 50%"
                        aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: 75%"
                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar"
                        style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar"
                        style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    Height
                </h5>
                <p class="card-subtitle"> We only set a height value on the <code>.progress</code>, so if you change
                    that value the inner
                    <code>.progress-bar</code> will automatically resize accordingly. Use <code>.progress-xs</code>,
                    <code>.progress-sm</code>,
                    <code>.progress-md</code>, <code>.progress-lg</code> or <code>.progress-xl</code> classes.
                </p>
            </div>
            <div class="card-body">
                <div class="progress mb-2 progress-xs">
                    <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress mb-2 progress-sm">
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: 50%;" aria-valuenow="50"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress mb-2 progress-md">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 75%;" aria-valuenow="75"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress mb-2 progress-lg">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 35%;" aria-valuenow="35"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress progress-xl">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 60%;" aria-valuenow="60"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>


            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

</div> <!-- end row -->
<!-- end progress -->
@endsection