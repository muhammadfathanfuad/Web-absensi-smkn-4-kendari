@extends('layouts.vertical-guru', ['subtitle' => 'Jadwal Mengajar'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Jadwal Mengajar', 'subtitle' => 'Guru'])

    {{-- Jadwal Hari Ini --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Jadwal Hari Ini ({{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }})</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Jam</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwalHariIni as $jadwal)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}</td>
                                <td>{{ $jadwal->subject->name ?? 'N/A' }}</td>
                                <td>{{ $jadwal->classroom->name ?? 'N/A' }}</td>
                                {{-- PERUBAHAN DI SINI: panggil room melalui classroom --}}
                                <td>{{ $jadwal->classroom->room->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada jadwal mengajar hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Jadwal Semester Ini --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Jadwal Semester Ini</h4>
        </div>
        <div class="card-body">
            @foreach ($days as $dayNumber => $dayName)
                @if (isset($semuaJadwal[$dayNumber]) && $semuaJadwal[$dayNumber]->count() > 0)
                    <div class="mb-4">
                        <h5>{{ $dayName }}</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jam</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Ruangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($semuaJadwal[$dayNumber] as $jadwal)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}</td>
                                            <td>{{ $jadwal->subject->name ?? 'N/A' }}</td>
                                            <td>{{ $jadwal->classroom->name ?? 'N/A' }}</td>
                                            {{-- PERUBAHAN DI SINI JUGA --}}
                                            <td>{{ $jadwal->classroom->room->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection