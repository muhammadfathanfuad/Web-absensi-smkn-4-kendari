@extends('layouts.vertical-guru', ['subtitle' => 'Jadwal Mengajar'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Jadwal Mengajar'])

    {{-- Jadwal Hari Ini --}}
    @include('components.jadwal.jadwal-hari-ini', [
        'timetables' => $jadwalHariIni ?? collect(),
        'title' => 'Jadwal Mengajar',
        'titleId' => 'jadwalTitle',
        'buttonId' => 'tombolBesok',
        'tbodyId' => 'todayTimetableBody',
        'viewDay' => request()->input('view_day', 'today'),
        'mode' => 'guru',
        'emptyMessage' => 'Tidak ada jadwal mengajar hari ini'
    ])

    {{-- Jadwal Semester Ini --}}
    @include('components.jadwal.semua-jadwal', [
        'timetables' => collect(),
        'title' => 'Jadwal Mengajar Semester Ini',
        'mode' => 'guru',
        'showWeekFilter' => false,
        'exportButtonId' => 'exportJadwalGuruDropdownBtn',
        'exportFunction' => 'exportJadwalGuru',
        'tableId' => 'allScheduleTable',
        'tbodyId' => 'allScheduleTableBody',
        'printableId' => 'printableJadwalSemester',
        'emptyMessage' => 'Tidak ada jadwal mengajar untuk semester ini',
        'days' => $days ?? [],
        'semuaJadwal' => $semuaJadwal ?? []
    ])
@endsection

@push('scripts')
    {{-- Inject routes ke window object untuk digunakan oleh JavaScript --}}
    <script>
        window.jadwalMengajarRoutes = {
            index: '{{ route("guru.jadwal-mengajar") }}',
            export: '{{ route("guru.jadwal-mengajar.export") }}'
        };
    </script>

    @vite(['resources/js/guru/jadwal-mengajar.js'])
@endpush
