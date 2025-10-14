    @extends('layouts.vertical-murid')

    @section('title', 'Jadwal Pelajaran')

    {{-- 1. Mengirim CSS Flatpickr dari CDN --}}
    @section('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endsection

    @section('content')
        {{-- Page Title --}}
        <div class="row calendar-adjust">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Jadwal</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Kolom Kiri: Jadwal --}}
            <div class="col-lg-8">
                {{-- Jadwal Pembelajaran --}}
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Jadwal Pelajaran Hari Ini</h4>
                        <div class="table-responsive">
                            <table class="table table-nowrap table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Mata Pelajaran</th>
                                        <th scope="col">Kelas</th>
                                        <th scope="col">Guru</th>
                                        <th scope="col">Jam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($timetables ?? collect() as $i => $tt)
                                        <tr>
                                            <th scope="row">{{ $i + 1 }}</th>
                                            <td>{{ optional($tt->subject)->name ?? '—' }}</td>
                                            <td>{{ optional($tt->classroom)->name ?? optional($tt->classroom)->class_code ?? '—' }}</td>
                                            <td>{{ optional(optional($tt->teacher)->user)->name ?? '—' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tt->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($tt->end_time)->format('H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada jadwal untuk hari ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Kolom Kanan: Kalender --}}
            <div class="col-lg-4">
                <div class="card calender-card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Kalender</h4>
                        {{-- Elemen HTML untuk kalender --}}
                        <div class="flatpickr-calendar-inline"></div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    {{-- 2. Mengirim JS Flatpickr dari CDN dan skrip inisialisasi --}}
    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            // Inisialisasi Flatpickr
            flatpickr('.flatpickr-calendar-inline', {
                inline: true,
            });
        </script>
    @endsection