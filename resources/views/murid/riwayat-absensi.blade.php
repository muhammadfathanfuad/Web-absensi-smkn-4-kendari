@extends('layouts.vertical-murid')

@section('title', 'Riwayat Absensi')

{{-- Menambahkan CSS untuk Date Picker --}}
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    @include('components.common.page-title-standard', [
        'title' => 'Siswa',
        'subtitle' => 'Riwayat Absensi',
        'breadcrumbParent' => 'Siswa',
        'breadcrumbActive' => 'Riwayat Absensi'
    ])

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
                        <div class="col-md-4">
                            <label for="subject_id" class="form-label">Filter berdasarkan mata pelajaran (opsional):</label>
                            <select name="subject_id" id="subject_id" class="form-select">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach($subjects ?? [] as $subject)
                                    <option value="{{ $subject->id }}" {{ (isset($subjectId) && $subjectId == $subject->id) ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 align-self-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('murid.absensi') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>

                    {{-- Tombol Export untuk Riwayat Absensi --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Riwayat Absensi Saya</h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" id="exportAbsensiMuridDropdownBtn">
                                <i class="bx bx-download"></i> <span>Export</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="exportAbsensiMurid('pdf'); return false;">
                                        <i class="bx bx-file"></i> Export PDF (.pdf)
                                    </a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive" id="printableRiwayatAbsensi">
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
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div class="text-muted">
                                Menampilkan {{ $attendances->firstItem() }} sampai {{ $attendances->lastItem() }} dari {{ $attendances->total() }} data
                            </div>
                            <div class="d-flex">
                                {{ $attendances->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Menambahkan JS untuk Date Picker (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- Inject routes ke window object untuk digunakan oleh JavaScript --}}
    <script>
        window.riwayatAbsensiRoutes = {
            export: '{{ route("murid.absensi.export") }}'
        };
    </script>

    @vite(['resources/js/murid/riwayat-absensi.js'])
@endpush