@extends('layouts.vertical-murid')

@section('title', 'Pengumuman')

@section('css')
    @vite(['resources/css/murid/pengumuman.css'])
@endsection

@section('content')
    @include('components.common.page-title-standard', [
        'title' => 'Siswa',
        'subtitle' => 'Pengumuman',
        'breadcrumbParent' => 'Siswa',
        'breadcrumbActive' => 'Pengumuman'
    ])

    @include('components.pengumuman.pengumuman', [
        'mode' => 'murid',
        'showFilters' => true,
        'showSidebar' => false,
        'announcementsContainerId' => 'announcementsContainer',
        'pengumumanListId' => 'pengumumanList',
        'categoryFilterId' => 'categoryFilter',
        'dateFilterId' => 'dateFilter',
        'searchInputId' => 'searchInput'
    ])

    {{-- Modal Detail Pengumuman --}}
    @include('components.pengumuman.modal-detail-pengumuman', [
        'modalId' => 'readMoreModal',
        'modalLabelId' => 'readMoreModalLabel',
        'modalTitleId' => 'modalTitle',
        'modalDateId' => 'modalDate',
        'modalAuthorId' => 'modalAuthor',
        'modalCategoryId' => 'modalCategory',
        'modalContentId' => 'modalContent',
        'modalCreatedAtId' => 'modalCreatedAt',
        'modalExpiresAtId' => 'modalExpiresAt',
        'modalPriorityId' => 'modalPriority',
        'modalIconId' => 'modalIcon',
        'modalIconClassId' => 'modalIconClass',
        'modalMarkReadBtnId' => 'modalMarkReadBtn',
        'markReadFunction' => 'toggleReadStatusFromModal'
    ])
@endsection


@push('scripts')
    @vite(['resources/js/murid/pengumuman.js'])
@endpush
