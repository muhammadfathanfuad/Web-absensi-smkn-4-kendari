@extends('layouts.vertical', ['subtitle' => 'Editors'])

@section('css')
@vite(['node_modules/quill/dist/quill.snow.css', 'node_modules/quill/dist/quill.bubble.css'])
@endsection

@section('content')
@include('layouts.partials.page-title', ['title' => 'Form', 'subtitle' => 'Editors'])

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Snow Editor</h5>

        <p class="card-subtitle">Use <code>snow-editor</code> id to set snow editor.</p>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <div id="snow-editor" style="height: 300px;">
                <h3><span class="ql-size-large">Hello World!</span></h3>
                <p><br></p>
                <h3>This is a simple editable area.</h3>
                <p><br></p>
                <ul>
                    <li>Select a text to reveal the toolbar.</li>
                    <li>Edit rich document on-the-fly, so elastic!</li>
                </ul>
                <p><br></p>
                <p>End of simple area</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Bubble Editor</h5>

        <p class="card-subtitle">Use <code>bubble-editor</code> id to set bubble editor.</p>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <!-- Bubble Editors -->
            <div id="bubble-editor" style="height: 300px;">
                <h3><span class="ql-size-large">Hello World!</span></h3>
                <p><br></p>
                <h3>This is a simple editable area.</h3>
                <p><br></p>
                <ul>
                    <li>Select a text to reveal the toolbar.</li>
                    <li>Edit rich document on-the-fly, so elastic!</li>
                </ul>
                <p><br></p>
                <p>End of simple area</p>
            </div>
        </div>
    </div> <!-- end card body -->
</div> <!-- end card -->
@endsection

@section('scripts')
@vite(['resources/js/pages/form-quilljs.js'])
@endsection