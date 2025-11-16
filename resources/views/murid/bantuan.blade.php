@extends('layouts.vertical-murid')

@section('title', 'Bantuan')

@section('content')
    @include('components.common.page-title-standard', [
        'title' => 'Siswa',
        'subtitle' => 'Bantuan',
        'breadcrumbParent' => 'Dashboard',
        'breadcrumbParentRoute' => 'murid.dashboard',
        'breadcrumbActive' => 'Bantuan'
    ])

    @include('components.bantuan.bantuan', [
        'mode' => 'murid',
        'showVideoGuide' => false,
        'showDocumentation' => false,
        'showSystemStatus' => false,
        'showSearchFAQ' => true,
        'showCategoryHelp' => true,
        'showTipsTricks' => true
    ])
@endsection

@push('scripts')
    @vite(['resources/js/murid/bantuan.js'])
@endpush
