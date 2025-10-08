@extends('layouts.vertical-admin', ['subtitle' => 'Jadwal Pelajaran'])

@section('content')

@include('layouts.partials.page-title', ['title' => 'jadwal', 'subtitle' => 'jadwal pelajaran'])

<div class="row">
    
</div>

@endsection

@section('scripts')
@vite(['resources/js/pages/dashboard.js'])
@endsection