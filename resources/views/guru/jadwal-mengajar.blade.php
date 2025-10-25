@extends('layouts.vertical-guru', ['subtitle' => 'Jadwal Mengajar'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Jadwal Mengajar', 'subtitle' => 'Guru'])

    {{-- Jadwal Hari Ini --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                        <iconify-icon icon="solar:calendar-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h4 class="card-title mb-1">
                        Jadwal Mengajar Hari Ini
                    </h4>
                    <p class="text-muted mb-0">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Jam</th>
                            <th scope="col">Mata Pelajaran</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Jumlah Murid</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwalHariIni as $index => $jadwal)
                            @php
                                $currentTime = \Carbon\Carbon::now();
                                $startTime = \Carbon\Carbon::parse($jadwal->start_time);
                                $endTime = \Carbon\Carbon::parse($jadwal->end_time);
                                $isUpcoming = $startTime->isFuture() && $startTime->diffInMinutes($currentTime) <= 30;
                                $isCurrent = $currentTime->between($startTime, $endTime);
                                $isPast = $endTime->isPast();
                            @endphp
                            <tr class="@if($isUpcoming) table-warning @elseif($isCurrent) table-success @endif">
                                <td>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                <iconify-icon icon="solar:book-outline" class="fs-12"></iconify-icon>
                                            </span>
                                        </div>
                                        {{ $jadwal->classSubject->subject->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary py-1 px-2">{{ $jadwal->classSubject->class->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info py-1 px-2">
                                        <iconify-icon icon="solar:users-group-rounded-outline" class="fs-12 me-1"></iconify-icon>
                                        {{ rand(25, 35) }} Siswa
                                    </span>
                                </td>
                                <td>
                                    @if($isUpcoming)
                                        <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                            <i class="bx bxs-circle text-warning me-1"></i>Segera Dimulai
                                        </span>
                                    @elseif($isCurrent)
                                        <span class="badge bg-success-subtle text-success py-1 px-2">
                                            <i class="bx bxs-circle text-success me-1"></i>Sedang Berlangsung
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                            <i class="bx bxs-circle text-secondary me-1"></i>Selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted text-center">
                                        <iconify-icon icon="solar:calendar-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                        Tidak ada jadwal mengajar hari ini.
                                    </div>
                                </td>
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
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-md bg-success bg-opacity-10 rounded-circle">
                        <iconify-icon icon="solar:calendar-mark-outline" class="fs-32 text-success avatar-title"></iconify-icon>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h4 class="card-title mb-1">
                        Jadwal Mengajar Semester Ini
                    </h4>
                    <p class="text-muted mb-0">Jadwal lengkap untuk semester berjalan</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Hari</th>
                            <th scope="col">Jam</th>
                            <th scope="col">Mata Pelajaran</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Jumlah Murid</th>
                            <th scope="col">Durasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($days as $dayNumber => $dayName)
                            @if (isset($semuaJadwal[$dayNumber]) && $semuaJadwal[$dayNumber]->count() > 0)
                                @foreach ($semuaJadwal[$dayNumber] as $index => $jadwal)
                                    <tr>
                                        <td>
                                            @if($index === 0)
                                                <span class="badge bg-primary-subtle text-primary py-1 px-2">{{ $dayName }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-success-subtle text-success">
                                                        <iconify-icon icon="solar:book-outline" class="fs-12"></iconify-icon>
                                                    </span>
                                                </div>
                                                {{ $jadwal->classSubject->subject->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info py-1 px-2">{{ $jadwal->classSubject->class->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                                <iconify-icon icon="solar:users-group-rounded-outline" class="fs-12 me-1"></iconify-icon>
                                                {{ rand(25, 35) }} Siswa
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $start = \Carbon\Carbon::parse($jadwal->start_time);
                                                $end = \Carbon\Carbon::parse($jadwal->end_time);
                                                $duration = $start->diffInMinutes($end);
                                            @endphp
                                            <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                                <iconify-icon icon="solar:clock-circle-outline" class="fs-12 me-1"></iconify-icon>
                                                {{ $duration }} Menit
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted text-center">
                                        <iconify-icon icon="solar:calendar-mark-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                        Tidak ada jadwal mengajar untuk semester ini.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection