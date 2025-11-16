@extends('layouts.vertical-guru')

@section('title', 'Permohonan Izin')

@section('content')
    @include('components.common.page-title-standard', [
        'title' => 'Guru',
        'subtitle' => 'Permohonan Izin',
        'breadcrumbParent' => 'Guru',
        'breadcrumbActive' => 'Permohonan Izin'
    ])

    {{-- Form Permohonan Izin --}}
    <div class="row align-items-start">
        @include('components.permohonan-izin.form-permohonan-izin', [
            'formAction' => route('guru.permohonan-izin.store'),
            'formId' => 'permohonanForm',
            'mode' => 'guru',
            'showTimetables' => true,
            'dateRangeInfoId' => 'date_range_info',
            'dateRangeTextId' => 'date_range_text',
            'timetablesLoadingId' => 'timetables_loading',
            'timetablesListId' => 'timetables_list',
            'timetablesCheckboxesId' => 'timetables_checkboxes',
            'noTimetablesId' => 'no_timetables',
            'timetablesPlaceholderId' => 'timetables_placeholder'
        ])

        @include('components.permohonan-izin.sidebar-info-permohonan-izin', [
            'mode' => 'guru',
            'showRecentRequests' => false
        ])
    </div>

    {{-- Riwayat Permohonan --}}
    @include('components.permohonan-izin.riwayat-permohonan-izin', [
        'leaveRequests' => $leaveRequests ?? collect(),
        'mode' => 'guru',
        'showPagination' => false,
        'detailModalFunction' => 'showDetailModal'
    ])

    {{-- Modal Detail Permohonan --}}
    @include('components.permohonan-izin.modal-detail-permohonan-izin', [
        'modalId' => 'detailModal',
        'modalLabelId' => 'detailModalLabel',
        'modalBodyId' => 'detailModalBody'
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

@push('scripts')
    {{-- Inject routes ke window object untuk digunakan oleh JavaScript --}}
<script>
        window.permohonanIzinRoutes = {
            getTimetables: '{{ route("guru.permohonan-izin.get-timetables") }}',
            show: '{{ route("guru.permohonan-izin.show", ":id") }}'
        };
    </script>

    @vite(['resources/js/guru/permohonan-izin.js'])
@endpush
@endsection

