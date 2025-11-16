@props([
    'timetables' => collect(),
    'title' => 'Semua Jadwal Pelajaran',
    'mode' => 'murid', // 'murid' or 'guru'
    'showWeekFilter' => false,
    'weekFilterId' => 'weekFilter',
    'exportButtonId' => 'exportJadwalDropdownBtn',
    'exportFunction' => 'exportJadwal',
    'tableId' => 'allScheduleTable',
    'tbodyId' => 'allScheduleTableBody',
    'printableId' => 'printableJadwal',
    'emptyMessage' => 'Tidak ada jadwal pelajaran ditemukan',
    'days' => null, // For guru mode, pass days array
    'semuaJadwal' => null // For guru mode, pass grouped jadwal by day
])

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0 d-flex align-items-center">
                    <i class="bx bx-calendar me-2"></i>
                    {{ $title }}
                </h4>
            </div>
            <div class="card-body">
                @if($showWeekFilter || $exportButtonId)
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    @if($showWeekFilter)
                    <div class="d-flex align-items-center gap-2">
                        <label for="{{ $weekFilterId }}" class="form-label mb-0">Filter Minggu:</label>
                        <select id="{{ $weekFilterId }}" class="form-select" style="width: auto; min-width: 150px;">
                            <option value="all">Semua Minggu</option>
                            <option value="ganjil">Minggu Ganjil</option>
                            <option value="genap">Minggu Genap</option>
                        </select>
                    </div>
                    @endif
                    @if($exportButtonId)
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false" id="{{ $exportButtonId }}">
                            <i class="bx bx-download"></i> <span>Export</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="{{ $exportFunction }}('pdf'); return false;">
                                    <i class="bx bx-file"></i> Export PDF (.pdf)
                                </a></li>
                        </ul>
                    </div>
                    @endif
                </div>
                @endif

                <div class="table-responsive" id="{{ $printableId }}">
                    <table class="table table-hover table-centered" id="{{ $tableId }}">
                        <thead class="table-light">
                            <tr>
                                @if($mode === 'murid')
                                    <th scope="col">No</th>
                                    <th scope="col">Hari</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Jenis Kelas</th>
                                    <th scope="col">Guru</th>
                                    <th scope="col">Jam</th>
                                @else
                                    <th scope="col">Hari</th>
                                    <th scope="col">Jam</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Jumlah Murid</th>
                                    <th scope="col">Durasi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="{{ $tbodyId }}">
                            @if($mode === 'murid')
                                @forelse ($timetables as $i => $tt)
                                    @php
                                        $days = [
                                            1 => 'Senin',
                                            2 => 'Selasa', 
                                            3 => 'Rabu',
                                            4 => 'Kamis',
                                            5 => 'Jumat',
                                            6 => 'Sabtu',
                                            7 => 'Minggu'
                                        ];
                                        $dayName = $days[$tt->day_of_week] ?? 'Unknown';
                                        $dayClass = [
                                            1 => 'bg-primary',
                                            2 => 'bg-success', 
                                            3 => 'bg-warning',
                                            4 => 'bg-info',
                                            5 => 'bg-danger',
                                            6 => 'bg-secondary',
                                            7 => 'bg-dark'
                                        ];
                                        $dayBadgeClass = $dayClass[$tt->day_of_week] ?? 'bg-secondary';
                                        
                                        $locationType = $tt->location_type ?? null;
                                        $type = $tt->type ?? 'teori';
                                        
                                        if ($locationType === 'lab') {
                                            $typeDisplay = 'Lab';
                                        } elseif ($locationType === 'theory') {
                                            $typeDisplay = 'Teori';
                                        } elseif ($type === 'praktik' || $type === 'Praktik') {
                                            $typeDisplay = 'Praktik';
                                        } else {
                                            $typeDisplay = 'Teori';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <span class="badge {{ $dayBadgeClass }}">{{ $dayName }}</span>
                                        </td>
                                        <td>
                                            <h6 class="mb-0">{{ optional($tt->classSubject->subject)->name ?? '—' }}</h6>
                                            <small class="text-muted">{{ optional($tt->classSubject->subject)->code ?? '—' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ optional($tt->classSubject->class)->name ?? '—' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $typeDisplay }}</span>
                                        </td>
                                        <td>{{ optional(optional($tt->classSubject->teacher)->user)->full_name ?? '—' }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ \Carbon\Carbon::parse($tt->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($tt->end_time)->format('H:i') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-calendar-x display-4"></i>
                                                <p class="mt-2">{{ $emptyMessage }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            @else
                                @php
                                    $hasJadwal = false;
                                    if ($days && $semuaJadwal) {
                                        foreach ($days as $dayNumber => $dayName) {
                                            if (isset($semuaJadwal[$dayNumber]) && $semuaJadwal[$dayNumber]->count() > 0) {
                                                $hasJadwal = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                @if($hasJadwal)
                                    @foreach ($days ?? [] as $dayNumber => $dayName)
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
                                                        <span class="badge bg-info-subtle text-info py-1 px-2">
                                                            {{ $jadwal->classSubject->class->name ?? 'N/A' }}
                                                            @if($jadwal->classSubject->class->grade ?? null)
                                                                -{{ $jadwal->classSubject->class->grade }}
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                                            <iconify-icon icon="solar:users-group-rounded-outline" class="fs-12 me-1"></iconify-icon>
                                                            {{ $jadwal->jumlah_murid ?? 0 }} Siswa
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
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted text-center">
                                                <iconify-icon icon="solar:calendar-mark-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                                {{ $emptyMessage }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

