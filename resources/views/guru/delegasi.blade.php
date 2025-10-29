@extends('layouts.vertical-guru', ['subtitle' => 'Delegasi Saya'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Pengganti Absensi'])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📋 Tugas Pengganti Absensi </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Jam</th>
                                <th>Guru Asli</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myDelegations as $delegasi)
                            <tr>
                                @php
                                    $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                                    $dayName = $dayNames[$delegasi->timetable->day_of_week] ?? 'N/A';
                                @endphp
                                <td>{{ $dayName }}</td>
                                <td>{{ $delegasi->timetable->classSubject->subject->name ?? 'N/A' }}</td>
                                <td>{{ $delegasi->timetable->classSubject->class->name ?? 'N/A' }}</td>
                                <td>{{ Carbon\Carbon::parse($delegasi->timetable->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($delegasi->timetable->end_time)->format('H:i') }}</td>
                                <td>{{ $delegasi->originalTeacher->user->full_name ?? 'N/A' }}</td>
                                <td>
                                    @if($delegasi->type == 'permanent')
                                        <span class="badge bg-info">Permanent</span>
                                    @else
                                        <span class="badge bg-warning">Temporary</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        // day_of_week adalah integer (1=Senin, 2=Selasa, ..., 7=Minggu)
                                        // dayOfWeekIso juga mengembalikan 1=Senin, 2=Selasa, ..., 7=Minggu
                                        $delegationDayNumber = $delegasi->timetable->day_of_week;
                                        $todayDayNumber = $today->dayOfWeekIso;
                                        $isToday = ($todayDayNumber === $delegationDayNumber);
                                        
                                        $isWithinTemporaryPeriod = true;
                                        if ($delegasi->type === 'temporary') {
                                            $validFrom = \Carbon\Carbon::parse($delegasi->valid_from)->startOfDay();
                                            $validUntil = \Carbon\Carbon::parse($delegasi->valid_until)->endOfDay();
                                            $todayDate = $today->startOfDay();
                                            // Gunakan isBetween dengan inclusive untuk memastikan tanggal boundary termasuk
                                            $isWithinTemporaryPeriod = $todayDate->isBetween($validFrom, $validUntil, true);
                                        }
                                    @endphp
                                    @if($isToday && $isWithinTemporaryPeriod)
                                    <a href="{{ route('guru.absensi.scan', ['timetable_id' => $delegasi->timetable->id]) }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-qr-scan"></i> Buka QR
                                    </a>
                                    @else
                                    <span class="badge bg-secondary">Belum waktunya</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bx bx-info-circle fs-32"></i>
                                        <p class="mb-0 mt-2">Anda belum memiliki delegasi</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

