@extends('layouts.vertical-admin', ['subtitle' => 'Pengaturan'])

@section('css')
    @vite(['resources/css/admin/pengaturan.css', 'resources/css/components/pengaturan.css'])
@endsection

@section('content')

@include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Pengaturan'])

{{-- Profile Settings --}}
<div class="row mb-4">
    <div class="col-12">
        @include('components.pengaturan.profil-card', [
            'user' => auth()->user(),
            'title' => 'Pengaturan Profil Admin',
            'formId' => 'profileForm',
            'photoRoute' => route('admin.pengaturan.photo'),
            'profileRoute' => null,
            'showPasswordChangeSection' => true,
            'showCurrentPasswordField' => true,
            'showReset' => true,
            'readonlyFields' => [],
            'customLayout' => true
        ])
    </div>
</div>

{{-- System Information with Database Statistics --}}
<div class="row">
    <div class="col-12">
        @include('components.pengaturan.informasi-sistem-card', ['showDatabaseStats' => true])
    </div>
</div>

{{-- Modal Notifikasi --}}
@include('components.pengaturan.notification-modal')

@endsection

@section('scripts')
    @vite(['resources/js/admin/pengaturan.js'])
@endsection
