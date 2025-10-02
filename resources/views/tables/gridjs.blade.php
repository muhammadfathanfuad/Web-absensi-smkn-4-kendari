@extends('layouts.vertical', ['subtitle' => 'Grid JS'])

@section('css')
@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@endsection

@section('content')
@include('layouts.partials.page-title', ['title' => 'Tables', 'subtitle' => 'Grid JS'])

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Basic</h5>
        <p class="card-subtitle">The most basic list group is an unordered list with list items and the
            proper classes. Build upon it with the options that follow, or with your own CSS as needed.
        </p>
    </div>
    <div class="card-body">
        <div>
            <div id="table-gridjs"></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Pagination</h5>
        <p class="text-muted mb-0">Pagination can be enabled by setting <code>pagination: true</code>:
        </p>
    </div>

    <div class="card-body">
        <div id="table-pagination"></div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <h5 class="card-title">Search</h5>
        <p class="text-muted mb-0">Grid.js supports global search on all rows and columns. Set
            <code>search: true</code> to enable the search plugin:
        </p>
    </div>
    <div class="card-body">
        <div id="table-search"></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Sorting</h5>
        <p class="text-muted mb-0">To enable sorting, simply add <code>sort: true</code> to your config:
        </p>
    </div>

    <div class="card-body">
        <div id="table-sorting"></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Loading State</h5>
        <p class="text-muted mb-0">Grid.js renders a loading bar automatically while it waits for the
            data to
            be fetched. Here we are using an async
            function to demonstrate this behaviour (e.g. an async function can be a XHR call to a server
            backend)</p>
    </div>
    <div class="card-body">
        <div id="table-loading-state"></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Fixed Header</h5>
        <p class="text-muted mb-0">The most basic list group is an unordered list with list items and
            the
            proper classes. Build upon it with the options that follow, or with your own CSS as needed.
        </p>
    </div>

    <div class="card-body">
        <div id="table-fixed-header"></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Hidden Columns</h5>
        <p class="text-muted mb-0">Add <code>hidden: true</code> to the columns definition to hide them.
        </p>
    </div>
    <div class="card-body">
        <div class="py-3">
            <div id="table-hidden-column"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@vite(['resources/js/pages/table-gridjs.js'])
@endsection