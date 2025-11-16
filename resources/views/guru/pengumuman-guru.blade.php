@extends('layouts.vertical-guru', ['subtitle' => 'Pengumuman'])

@section('css')
    @vite(['resources/css/guru/pengumuman-guru.css'])
@endsection

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Pengumuman'])

    @include('components.pengumuman.pengumuman', [
        'mode' => 'guru',
        'showFilters' => false,
        'showSidebar' => true,
        'announcementsContainerId' => 'announcementsContainer',
        'timelineContainerId' => 'timelineContainer'
    ])
@endsection

@push('scripts')
    @vite(['resources/js/guru/pengumuman-guru.js'])
@endpush
