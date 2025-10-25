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
                    {{-- Info Statistik --}}
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bx bx-info-circle me-2"></i>
                                <div>
                                    <strong>Total Data:</strong> {{ $attendances->total() }} absensi
                                    @if($from && $to)
                                        <span class="ms-3"><strong>Filter:</strong> {{ \Carbon\Carbon::parse($from)->format('d M Y') }} - {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</span>
                                    @else
                                        <span class="ms-3"><strong>Menampilkan:</strong> Semua data absensi</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="GET" class="row mb-3" id="filter-form">
                        <div class="col-md-4">
                            <label for="date-range" class="form-label">Filter berdasarkan tanggal (opsional):</label>
                            <input type="text" id="date-range" class="form-control" placeholder="Pilih rentang tanggal..." value="{{ ($from && $to) ? $from.' to '.$to : '' }}">
                            <input type="hidden" name="from" id="date-from" value="{{ $from ?? '' }}">
                            <input type="hidden" name="to" id="date-to" value="{{ $to ?? '' }}">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('murid.absensi') }}" class="btn btn-outline-secondary">Reset</a>
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
                                        <td>{{ optional(optional(optional($att->classSession)->timetable)->classSubject)->subject->name ?? '—' }}</td>
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
                                        <td>
                                            @if($att->check_in_time)
                                                {{ \Carbon\Carbon::parse($att->check_in_time)->format('H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                // Format keterangan berdasarkan status seperti di halaman jadwal pelajaran
                                                $notes = '';
                                                
                                                if ($att->status === 'H') {
                                                    // Hadir - show scan time
                                                    if ($att->check_in_time) {
                                                        $checkInTime = \Carbon\Carbon::parse($att->check_in_time)->format('H:i');
                                                        $notes = "Hadir tepat waktu (Scan: {$checkInTime})";
                                                    } else {
                                                        $notes = 'Hadir tepat waktu';
                                                    }
                                                } elseif ($att->status === 'T') {
                                                    // Terlambat - show late time and scan time
                                                    $lateMinutes = abs(round($att->late_minutes ?? 0));
                                                    
                                                    // Format late time
                                                    if ($lateMinutes === 0) {
                                                        $timeFormat = '0 menit';
                                                    } elseif ($lateMinutes < 60) {
                                                        $timeFormat = "{$lateMinutes} menit";
                                                    } else {
                                                        $hours = floor($lateMinutes / 60);
                                                        $remainingMinutes = $lateMinutes % 60;
                                                        if ($remainingMinutes === 0) {
                                                            $timeFormat = "{$hours} jam";
                                                        } else {
                                                            $timeFormat = "{$hours} jam {$remainingMinutes} menit";
                                                        }
                                                    }
                                                    
                                                    if ($att->check_in_time) {
                                                        $checkInTime = \Carbon\Carbon::parse($att->check_in_time)->format('H:i');
                                                        $notes = "Terlambat {$timeFormat} (Scan: {$checkInTime})";
                                                    } else {
                                                        $notes = "Terlambat {$timeFormat}";
                                                    }
                                                } elseif ($att->status === 'A') {
                                                    $notes = 'Tidak hadir - tidak melakukan scan';
                                                } elseif ($att->status === 'I') {
                                                    $notes = 'Izin';
                                                } elseif ($att->status === 'S') {
                                                    $notes = 'Sakit';
                                                } else {
                                                    $notes = $att->notes ?? '-';
                                                }
                                                
                                                // Add check-out time if available
                                                if ($att->check_out_time) {
                                                    $checkOutTime = \Carbon\Carbon::parse($att->check_out_time)->format('H:i');
                                                    $notes .= " (Keluar: {$checkOutTime})";
                                                }
                                            @endphp
                                            {{ $notes ?: '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            @if($from && $to)
                                                Tidak ada data absensi dalam rentang tanggal yang dipilih.
                                            @else
                                                Belum ada data absensi.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($attendances->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $attendances->firstItem() }} sampai {{ $attendances->lastItem() }} dari {{ $attendances->total() }} data
                            </div>
                            <div>
                                {{ $attendances->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
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