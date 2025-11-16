@extends('layouts.vertical-murid')

@section('title', 'Pengaturan')

@section('css')
    @vite(['resources/css/components/pengaturan.css'])
@endsection

@section('content')
    @include('components.common.page-title-standard', [
        'title' => 'Siswa',
        'subtitle' => 'Pengaturan',
        'breadcrumbParent' => 'Siswa',
        'breadcrumbActive' => 'Pengaturan'
    ])

    <div class="row">
        {{-- Profil Akun --}}
        <div class="col-lg-6">
            @include('components.pengaturan.profil-card', [
                'user' => $user,
                'title' => 'Profil Akun',
                'formId' => 'profilForm',
                'photoRoute' => route('murid.pengaturan.photo'),
                'profileRoute' => route('murid.pengaturan.profile'),
                'showNis' => true,
                'readonlyFields' => ['full_name']
            ])
        </div>

        {{-- Keamanan Akun --}}
        <div class="col-lg-6">
            @include('components.pengaturan.keamanan-card', [
                'title' => 'Keamanan Akun',
                'formId' => 'keamananForm',
                'passwordRoute' => route('murid.pengaturan.password'),
                'showCurrentPassword' => true
            ])
        </div>
    </div>

    {{-- Aktivitas Akun --}}
    <div class="row mt-4">
        <div class="col-lg-12">
            @include('components.pengaturan.aktivitas-akun-card', [
                'user' => $user,
                'showLogoutButton' => true
            ])
        </div>
    </div>

    {{-- Modal Notifikasi --}}
    @include('components.pengaturan.notification-modal')
@endsection

@push('scripts')
    {{-- Inject routes dan data ke window object untuk digunakan oleh JavaScript --}}
    <script>
        window.pengaturanMuridRoutes = {
            profile: '{{ route("murid.pengaturan.profile") }}',
            password: '{{ route("murid.pengaturan.password") }}',
            photo: '{{ route("murid.pengaturan.photo") }}'
        };
        
        window.pengaturanMuridData = {
            userActivity: {
                last_login_at: @json($user->last_login_at ?? null),
                created_at: @json($user->created_at ?? null),
                updated_at: @json($user->updated_at ?? null)
            }
        };
    </script>

    @vite(['resources/js/murid/pengaturan.js'])
@endpush
