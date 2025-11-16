@extends('layouts.vertical-murid')

@section('title', 'Jadwal Pelajaran')

@section('content')
    @include('components.common.page-title-standard', [
        'title' => 'Siswa',
        'subtitle' => 'Jadwal Pelajaran',
        'breadcrumbParent' => 'Siswa',
        'breadcrumbActive' => 'Jadwal Pelajaran'
    ])

    {{-- Jadwal Pelajaran Hari Ini --}}
    @include('components.jadwal.jadwal-hari-ini', [
        'timetables' => $timetables ?? collect(),
        'title' => 'Jadwal Pelajaran Hari Ini',
        'titleId' => 'jadwalTitle',
        'buttonId' => 'tombolBesok',
        'tbodyId' => 'todayTimetableBody',
        'viewDay' => request()->input('view_day', 'today'),
        'mode' => 'murid',
        'emptyMessage' => 'Tidak ada jadwal untuk hari ini'
    ])

    {{-- Semua Jadwal Pelajaran --}}
    @include('components.jadwal.semua-jadwal', [
        'timetables' => $allTimetables ?? collect(),
        'title' => 'Semua Jadwal Pelajaran',
        'mode' => 'murid',
        'showWeekFilter' => true,
        'weekFilterId' => 'weekFilter',
        'exportButtonId' => 'exportJadwalMuridDropdownBtn',
        'exportFunction' => 'exportJadwalMurid',
        'tableId' => 'allScheduleTable',
        'tbodyId' => 'allScheduleTableBody',
        'printableId' => 'printableJadwalPelajaran',
        'emptyMessage' => 'Tidak ada jadwal pelajaran ditemukan'
    ])
    @endsection

@push('scripts')
    {{-- Inject routes dan data ke window object untuk digunakan oleh JavaScript --}}
    <script>
        // Initialize window variables
        if (typeof window._jadwalVars === 'undefined') {
            window._jadwalVars = {
                currentViewDay: @json(request()->input('view_day', 'today') === 'besok' ? 'besok' : null) // null = hari ini, atau 'besok' untuk besok
            };
        }
        
        window.jadwalMuridRoutes = {
            index: '{{ route("murid.jadwal") }}',
            export: '{{ route("murid.jadwal.export") }}'
        };
    </script>

    @vite(['resources/js/murid/jadwal-pelajaran.js'])
@endpush