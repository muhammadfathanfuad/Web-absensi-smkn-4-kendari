@extends('layouts.vertical-murid')

@section('title', 'Permohonan Izin')

@section('content')
    @include('components.common.page-title-standard', [
        'title' => 'Siswa',
        'subtitle' => 'Permohonan Izin',
        'breadcrumbParent' => 'Siswa',
        'breadcrumbActive' => 'Permohonan Izin'
    ])

    {{-- Form Permohonan Izin --}}
    <div class="row align-items-start">
        @include('components.permohonan-izin.form-permohonan-izin', [
            'formAction' => route('murid.permohonan-izin.store'),
            'formId' => 'permohonanForm',
            'mode' => 'murid',
            'showTimetables' => false
        ])

        @include('components.permohonan-izin.sidebar-info-permohonan-izin', [
            'mode' => 'murid',
            'recentRequests' => null,
            'showRecentRequests' => false
        ])
    </div>

    {{-- Riwayat Permohonan --}}
    @include('components.permohonan-izin.riwayat-permohonan-izin', [
        'leaveRequests' => $recentRequests ?? collect(),
        'mode' => 'murid',
        'showPagination' => true,
        'detailModalFunction' => 'showDetailModal'
    ])

    {{-- Modal Notifikasi --}}
    @include('components.pengaturan.notification-modal', [
        'modalId' => 'notificationModal',
        'modalLabelId' => 'notificationModalLabel',
        'messageId' => 'notificationMessage',
        'errorsId' => 'notificationErrors',
        'errorsBodyId' => 'notificationErrorsBody',
        'modalSize' => '',
        'showErrorsTable' => false,
        'showIcon' => false,
        'iconId' => 'notificationIcon'
    ])

    {{-- Modal Detail Permohonan --}}
    @include('components.permohonan-izin.modal-detail-permohonan-izin', [
        'modalId' => 'detailModal',
        'modalLabelId' => 'detailModalLabel',
        'modalBodyId' => 'detailModalBody'
    ])
@endsection

@push('scripts')
    @vite(['resources/js/murid/permohonan-izin.js'])
@endpush
