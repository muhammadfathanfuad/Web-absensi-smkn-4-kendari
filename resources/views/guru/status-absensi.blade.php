@extends('layouts.vertical-guru', ['subtitle' => 'Status Absensi'])

@section('css')
    @vite(['resources/css/guru/status-absensi.css'])
@endsection

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Status Absensi'])

    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Rekap Kehadiran Siswa</h4>

            {{-- Tabs --}}
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $viewType == 'summary' ? 'active' : '' }}" id="summary-tab" data-bs-toggle="tab" 
                            data-bs-target="#summary" type="button" role="tab" onclick="switchViewType('summary')">
                        <i class="bx bx-list-ul me-1"></i> Ringkasan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $viewType == 'detail' ? 'active' : '' }}" id="detail-tab" data-bs-toggle="tab" 
                            data-bs-target="#detail" type="button" role="tab" onclick="switchViewType('detail')">
                        <i class="bx bx-detail me-1"></i> Detail
                    </button>
                </li>
            </ul>

            {{-- Form Filter --}}
            <form action="{{ route('guru.status-absensi') }}" method="GET" id="filterForm">
                <input type="hidden" name="view_type" id="view_type" value="{{ $viewType }}">
                
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label for="period_preset" class="form-label">Periode</label>
                        <select name="period_preset" id="period_preset" class="form-select" onchange="handlePeriodPreset()">
                            <option value="custom" {{ $periodPreset == 'custom' ? 'selected' : '' }}>Custom</option>
                            <option value="semester_ganjil" {{ $periodPreset == 'semester_ganjil' ? 'selected' : '' }}>Semester Ganjil (Jul-Des)</option>
                            <option value="semester_genap" {{ $periodPreset == 'semester_genap' ? 'selected' : '' }}>Semester Genap (Jan-Jun)</option>
                            <option value="bulan_ini" {{ $periodPreset == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        </select>
                    </div>
                    <div class="col-md-2" id="date_from_wrapper">
                        <label for="date_from" class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-2" id="date_to_wrapper">
                        <label for="date_to" class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $dateTo }}">
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label for="classroom_id" class="form-label">Pilih Kelas</label>
                        <select name="classroom_id" id="classroom_id" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ $selectedClassroomId == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->grade }} - {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-filter me-1"></i> Filter
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetFilter()">
                            <i class="bx bx-refresh me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </form>

            <hr>

            {{-- Tab Content --}}
            <div class="tab-content" id="myTabContent">
                {{-- Tab Ringkasan --}}
                <div class="tab-pane fade {{ $viewType == 'summary' ? 'show active' : '' }}" id="summary" role="tabpanel">
            {{-- Tombol Export --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Ringkasan Absensi Siswa</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" id="exportSummaryDropdownBtn">
                        <i class="bx bx-download"></i> <span>Export</span>
                    </button>
                    <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="exportAbsensiGuru('pdf', 'summary'); return false;">
                                <i class="bx bx-file"></i> Export PDF (.pdf)
                            </a></li>
                    </ul>
                </div>
            </div>

                    {{-- Tabel Ringkasan --}}
                    <div class="table-responsive mt-4" id="printableSummaryTable">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Total Hadir</th>
                                    <th>Total Terlambat</th>
                                    <th>Total Absen</th>
                                    <th>Total Izin</th>
                                    <th>Total Sakit</th>
                                    <th>Total Pertemuan</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($summary ?? [] as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $student['nis'] }}</td>
                                        <td>{{ $student['name'] }}</td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">
                                                {{ $student['class'] }}
                                            </span>
                                        </td>
                                        <td class="text-success fw-bold">{{ $student['total_hadir'] }}</td>
                                        <td class="text-warning fw-bold">{{ $student['total_terlambat'] }}</td>
                                        <td class="text-danger fw-bold">{{ $student['total_absen'] }}</td>
                                        <td class="text-info fw-bold">{{ $student['total_izin'] }}</td>
                                        <td class="text-warning fw-bold">{{ $student['total_sakit'] }}</td>
                                        <td class="fw-bold">{{ $student['total_pertemuan'] }}</td>
                                        <td>
                                            <span class="badge {{ $student['persentase'] >= 80 ? 'bg-success' : ($student['persentase'] >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $student['persentase'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-info-circle me-2"></i>
                                                Tidak ada data absensi untuk filter yang dipilih.
                                                @if($viewType == 'summary')
                                                    <br><small>Pastikan Anda telah memilih periode tanggal dan filter lainnya.</small>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tab Detail --}}
                <div class="tab-pane fade {{ $viewType == 'detail' ? 'show active' : '' }}" id="detail" role="tabpanel">
                    {{-- Tombol Export dan Pencarian --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Data Absensi Siswa (Detail)</h5>
                        <div class="d-flex gap-2 align-items-center">
                            {{-- Kolom Pencarian --}}
                            <div class="input-group" style="max-width: 300px;">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control" id="detailSearchInput" 
                                       placeholder="Cari NIS, Nama, Kelas, Mapel..." 
                                       onkeyup="filterDetailTable()">
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false" id="exportDetailDropdownBtn">
                                    <i class="bx bx-download"></i> <span>Export</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="exportAbsensiGuru('pdf', 'detail'); return false;">
                                            <i class="bx bx-file"></i> Export PDF (.pdf)
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Rekap Absensi --}}
                    <div class="table-responsive mt-4" id="printableTable">
                        <table class="table table-hover table-striped" id="detailTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                            <tbody id="detailTableBody">
                        @forelse ($attendances as $absen)
                                    <tr class="detail-row" 
                                        data-nis="{{ strtolower($absen->student->nis ?? '') }}"
                                        data-nama="{{ strtolower($absen->student->user->full_name ?? '') }}"
                                        data-kelas="{{ strtolower($absen->student->classroom ? ($absen->student->classroom->grade . ' - ' . $absen->student->classroom->name) : '') }}"
                                        data-mapel="{{ strtolower($absen->classSession->timetable->classSubject->subject->name ?? '') }}"
                                        data-tanggal="{{ $absen->classSession->date ?? '' }}">
                                        <td class="detail-no">{{ $loop->iteration }}</td>
                                        <td>
                                            @if($absen->classSession && $absen->classSession->date)
                                                {{ \Carbon\Carbon::parse($absen->classSession->date)->translatedFormat('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                <td>{{ $absen->student->nis ?? 'N/A' }}</td>
                                <td>{{ $absen->student->user->full_name ?? 'N/A' }}</td>
                                <td>
                                    @if($absen->student->classroom)
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $absen->student->classroom->grade }} - {{ $absen->student->classroom->name }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $absen->classSession->timetable->classSubject->subject->name ?? 'N/A' }}</td>
                                <td>{{ $absen->check_in_time ?? '-' }}</td>
                                <td>
                                    @if($absen->status == 'S')
                                        <span class="badge bg-soft-warning text-warning">Sakit</span>
                                    @elseif($absen->status == 'I')
                                        <span class="badge bg-soft-info text-info">Izin</span>
                                    @elseif($absen->status == 'T' || ($absen->notes === 'Terlambat' && $absen->status !== 'H'))
                                        <span class="badge bg-soft-danger text-danger">Terlambat</span>
                                    @elseif($absen->status == 'H')
                                        <span class="badge bg-soft-success text-success">Hadir</span>
                                    @else
                                        <span class="badge bg-soft-secondary text-secondary">{{ $absen->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                                    <tr id="emptyRow">
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-info-circle me-2"></i>
                                                Tidak ada data absensi untuk filter yang dipilih.
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

@push('scripts')
    {{-- Inject routes dan data ke window object untuk digunakan oleh JavaScript --}}
    <script>
        window.statusAbsensiRoutes = {
            export: '{{ route("guru.status-absensi.export") }}'
        };
        window.statusAbsensiData = {
            defaultViewType: '{{ $viewType }}'
        };
    </script>

    @vite(['resources/js/guru/status-absensi.js'])
@endpush
