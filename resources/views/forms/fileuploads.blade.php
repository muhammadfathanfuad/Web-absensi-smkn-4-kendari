@extends('layouts.vertical', ['subtitle' => 'File Uploads'])


@section('content')
@include('layouts.partials.page-title', ['title' => 'Form', 'subtitle' => 'File Uploads'])

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Dropzone File Upload</h5>
                <p class="card-subtitle">DropzoneJS is an open source library that provides
                    drag’n’drop file uploads
                    with image previews.
                </p>

            </div>



            <div class="card-body">

                <div class="mb-3">

                    <div class="dropzone">
                        <div class="fallback">
                            <input name="file" type="file" multiple="multiple">
                        </div>
                        <div class="dz-message needsclick">
                            <i class="h1 bx bx-cloud-upload"></i>
                            <h3>Drop files here or click to upload.</h3>
                            <span class="text-muted fs-13">
                                (This is just a demo dropzone. Selected files are
                                <strong>not</strong> actually uploaded.)
                            </span>
                        </div>
                    </div>

                    <ul class="list-unstyled mb-0" id="dropzone-preview">
                        <li class="mt-2" id="dropzone-preview-list">
                            <!-- This is used as the file preview template -->
                            <div class="border rounded">
                                <div class="d-flex align-items-center p-2">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm bg-light rounded">
                                            <img data-dz-thumbnail class="img-fluid rounded d-block" src="#"
                                                alt="" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="pt-1">
                                            <h5 class="fs-14 mb-1" data-dz-name>&nbsp;
                                            </h5>
                                            <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                            <strong class="error text-danger" data-dz-errormessage></strong>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 ms-3">
                                        <button data-dz-remove class="btn btn-sm btn-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <!-- end dropzon-preview -->
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('scripts')
@vite(['resources/js/pages/form-fileupload.js'])
@endsection