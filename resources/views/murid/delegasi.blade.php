@extends('layouts.vertical-murid', ['subtitle' => 'Delegasi Saya'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Siswa', 'subtitle' => 'Tugas Absensi'])

@include('components.tugas-absensi.tugas-absensi', [
    'mode' => 'murid',
    'myDelegations' => $myDelegations ?? collect(),
    'today' => $today ?? \Carbon\Carbon::now(),
    'showInfoAlert' => true,
    'qrModalFunction' => 'openQRModal',
    'cardTitle' => 'Tugas Absensi dari Guru'
])

{{-- Modal QR Code untuk Delegasi --}}
@include('components.tugas-absensi.modal-qr-delegasi', [
    'modalId' => 'qrModalDelegasi',
    'modalLabelId' => 'qrModalDelegasiLabel',
    'qrCodeContainerId' => 'qrCodeContainerDelegasi',
    'qrInfoTextId' => 'qrInfoTextDelegasi',
    'stopSessionBtnId' => 'stopSessionBtnDelegasi'
])

{{-- Modal Konfirmasi Stop Session --}}
@include('components.tugas-absensi.modal-stop-session', [
    'modalId' => 'stopSessionModalDelegasi',
    'modalLabelId' => 'stopSessionModalDelegasiLabel',
    'stopSessionTokenId' => 'stopSessionTokenDelegasi',
    'confirmStopSessionBtnId' => 'confirmStopSessionBtnDelegasi'
])

    {{-- Modal Notifikasi --}}
    @include('components.pengaturan.notification-modal', [
        'modalId' => 'notificationModalDelegasi',
        'modalLabelId' => 'notificationModalDelegasiLabel',
        'messageId' => 'notificationMessageDelegasi',
        'errorsId' => 'notificationErrorsDelegasi',
        'errorsBodyId' => 'notificationErrorsBodyDelegasi',
        'modalSize' => '',
        'showErrorsTable' => false,
        'showIcon' => true,
        'iconId' => 'notificationIconDelegasi'
    ])

@endsection

@push('scripts')
    {{-- QR Code Generator Library (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>

    {{-- Inject routes ke window object untuk digunakan oleh JavaScript --}}
    <script>
        window.delegasiMuridRoutes = {
            generateQR: '{{ route("murid.delegasi.generate-qr") }}',
            stopSession: '{{ route("murid.delegasi.stop-session") }}'
        };
    </script>

    @vite(['resources/js/murid/delegasi.js'])
@endpush

