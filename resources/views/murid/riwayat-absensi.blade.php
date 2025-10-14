@extends('layouts.vertical-murid')

@section('title', 'Riwayat Absensi')

{{-- Menambahkan CSS untuk Date Picker --}}
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Riwayat Absensi</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('murid.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Absensi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance History Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row mb-3" id="filter-form">
                        <div class="col-md-4">
                            <label for="date-range" class="form-label">Filter berdasarkan tanggal:</label>
                            <input type="text" id="date-range" class="form-control" placeholder="Pilih rentang tanggal..." value="{{ ($from && $to) ? $from.' to '.$to : '' }}">
                            <input type="hidden" name="from" id="date-from" value="{{ $from ?? '' }}">
                            <input type="hidden" name="to" id="date-to" value="{{ $to ?? '' }}">
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances ?? collect() as $att)
                                    <tr>
                                        <td>{{ optional($att->created_at)->format('d F Y') }}</td>
                                        <td>{{ optional(optional($att->classSession)->timetable)->subject->name ?? '—' }}</td>
                                        <td>
                                            @switch($att->status)
                                                @case('H')
                                                    <span class="badge bg-success">Hadir</span>
                                                    @break
                                                @case('I')
                                                    <span class="badge bg-warning text-dark">Izin</span>
                                                    @break
                                                @case('S')
                                                    <span class="badge bg-info">Sakit</span>
                                                    @break
                                                @case('T')
                                                    <span class="badge bg-warning">Terlambat</span>
                                                    @break
                                                @case('A')
                                                    <span class="badge bg-danger">Alpa</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $att->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ optional($att->check_in_time)->format('H:i') ?? '-' }}</td>
                                        <td>{{ $att->notes ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data absensi dalam rentang tanggal yang dipilih.</td>
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

@section('scripts')
    {{-- Menambahkan JS untuk Date Picker --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Inisialisasi Flatpickr untuk filter rentang tanggal
        const fp = flatpickr("#date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    document.getElementById('date-from').value = selectedDates[0].toISOString().slice(0,10);
                    document.getElementById('date-to').value = selectedDates[1].toISOString().slice(0,10);
                }
            }
        });
    </script>
@endsection