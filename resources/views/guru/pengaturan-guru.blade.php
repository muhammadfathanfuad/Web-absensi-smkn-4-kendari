@extends('layouts.vertical-guru', ['subtitle' => 'Pengaturan'])

@section('css')
    @vite(['resources/css/components/pengaturan.css'])
@endsection

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Pengaturan'])

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-all me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-block-helper me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Profil Guru --}}
            @include('components.pengaturan.profil-card', [
                'user' => Auth::user(),
                'title' => 'Profil Guru',
                'formId' => 'profilForm',
                'photoRoute' => route('guru.pengaturan.photo'),
                'profileRoute' => route('guru.pengaturan.update-profil'),
                'showNip' => true,
                'readonlyFields' => []
            ])

            {{-- Keamanan Akun --}}
            @include('components.pengaturan.keamanan-card', [
                'title' => 'Keamanan Akun',
                'formId' => 'passwordForm',
                'passwordRoute' => route('guru.pengaturan.update-password'),
                'showCurrentPassword' => true
            ])
        </div>

        <div class="col-lg-4">
            {{-- Aktivitas Akun --}}
            @include('components.pengaturan.aktivitas-akun-card', [
                'user' => Auth::user(),
                'showLogoutButton' => false
            ])
        </div>
    </div>

    {{-- Modal Notifikasi --}}
    @include('components.pengaturan.notification-modal')
@endsection

@push('scripts')
    {{-- Inject routes dan data ke window object untuk digunakan oleh JavaScript --}}
    <script>
        window.pengaturanGuruRoutes = {
            photo: '{{ route("guru.pengaturan.photo") }}'
        };
        
        window.pengaturanGuruData = {
            userActivity: {
                last_login_at: @json(Auth::user()->last_login_at ?? null),
                created_at: @json(Auth::user()->created_at ?? null),
                updated_at: @json(Auth::user()->updated_at ?? null)
            }
        };
    </script>

    @vite(['resources/js/guru/pengaturan-guru.js'])
@endpush
