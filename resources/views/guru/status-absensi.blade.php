@extends('layouts.vertical-guru', ['subtitle' => 'Status Absensi'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Status Absensi', 'subtitle' => 'Guru'])

    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Rekap Kehadiran Siswa</h4>

            {{-- Form Filter --}}
            <form action="{{ route('guru.status-absensi') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="subject_id" class="form-label">Pilih Mata Pelajaran</label>
                        <select name="subject_id" id="subject_id" class="form-select">
                            <option value="">Semua Mapel</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="date" class="form-label">Pilih Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $selectedDate }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <hr>

            {{-- Tabel Rekap Absensi --}}
            <div class="table-responsive mt-4">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Mata Pelajaran</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $absen)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $absen->student->nis ?? 'N/A' }}</td>
                                <td>{{ $absen->student->user->full_name ?? 'N/A' }}</td>
                                <td>{{ $absen->classSession->timetable->classSubject->subject->name ?? 'N/A' }}</td>
                                <td>{{ $absen->check_in_time ?? '-' }}</td>
                                <td>
                                    {{-- --- PERUBAHAN LOGIKA STATUS DI SINI --- --}}
                                    @if($absen->status == 'S')
                                        <span class="badge bg-soft-warning text-warning">Sakit</span>
                                    @elseif($absen->status == 'I')
                                        <span class="badge bg-soft-info text-info">Izin</span>
                                    @elseif($absen->status == 'T' || ($absen->notes === 'Terlambat' && $absen->status !== 'H'))
                                        {{-- Prefer explicit status 'T' for terlambat; fallback to notes if status wasn't set correctly --}}
                                        <span class="badge bg-soft-danger text-danger">Terlambat</span>
                                    @elseif($absen->status == 'H')
                                        <span class="badge bg-soft-success text-success">Hadir</span>
                                    @else
                                        <span class="badge bg-soft-secondary text-secondary">{{ $absen->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data absensi untuk filter yang dipilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection