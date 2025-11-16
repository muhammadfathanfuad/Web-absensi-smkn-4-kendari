@props([
    'timetables' => collect(),
    'title' => 'Jadwal Pelajaran Hari Ini',
    'titleId' => 'jadwalTitle',
    'buttonId' => 'tombolBesok',
    'tbodyId' => 'todayTimetableBody',
    'viewDay' => 'today',
    'mode' => 'murid', // 'murid' or 'guru'
    'emptyMessage' => 'Tidak ada jadwal untuk hari ini'
])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-calendar-check me-2"></i>
                        <span id="{{ $titleId }}">
                            @php
                                if ($viewDay === 'besok') {
                                    $currentDay = \App\Services\TimeOverrideService::dayOfWeek();
                                    if ($currentDay >= 5) {
                                        $nextDate = \App\Services\TimeOverrideService::now()->copy();
                                        $daysUntilMonday = (8 - $currentDay) % 7;
                                        if ($daysUntilMonday == 0) {
                                            $daysUntilMonday = 7;
                                        }
                                        $nextDate->addDays($daysUntilMonday);
                                    } else {
                                        $nextDate = \App\Services\TimeOverrideService::now()->addDay();
                                    }
                                    echo $title . ' Besok - ' . $nextDate->translatedFormat('l, j F Y');
                                } else {
                                    echo $title . ' - ' . \App\Services\TimeOverrideService::localeFormat('dddd, D MMMM Y');
                                }
                            @endphp
                        </span>
                    </h4>
                    @php
                        $buttonText = $viewDay === 'besok' ? 'Hari Ini' : 'Besok';
                        $buttonOnClick = $viewDay === 'besok' ? 'lihatJadwalHariIni()' : 'lihatJadwalBesok()';
                    @endphp
                    <button type="button" class="btn btn-outline-primary btn-sm" id="{{ $buttonId }}" onclick="{{ $buttonOnClick }}">
                        <i class="bx bx-calendar me-1"></i> {{ $buttonText }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                @if($mode === 'murid')
                                    <th scope="col">No</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Jenis Kelas</th>
                                    <th scope="col">Guru</th>
                                    <th scope="col">Jam</th>
                                    <th scope="col">Status</th>
                                @else
                                    <th scope="col">Jam</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Jumlah Murid</th>
                                    <th scope="col">Status</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="{{ $tbodyId }}">
                            @forelse ($timetables as $i => $tt)
                                @if($mode === 'murid')
                                    @php
                                        $viewDay = request()->input('view_day', 'today');
                                        if ($viewDay === 'besok') {
                                            $statusBadge = '<span class="badge bg-secondary">Belum Dimulai</span>';
                                        } else {
                                            $now = \App\Services\TimeOverrideService::now();
                                            $startTime = \Carbon\Carbon::parse($tt->start_time);
                                            $endTime = \Carbon\Carbon::parse($tt->end_time);
                                            
                                            if ($now->lt($startTime)) {
                                                $statusBadge = '<span class="badge bg-secondary">Belum Dimulai</span>';
                                            } elseif ($now->between($startTime, $endTime)) {
                                                $statusBadge = '<span class="badge bg-primary">Sedang Berlangsung</span>';
                                            } else {
                                                $statusBadge = '<span class="badge bg-success">Selesai</span>';
                                            }
                                        }
                                        
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
                                        <td>
                                            {!! $statusBadge ?? '<span class="badge bg-secondary">Belum Dimulai</span>' !!}
                                        </td>
                                    </tr>
                                @else
                                    @php
                                        $currentTime = \App\Services\TimeOverrideService::now();
                                        $startTime = \Carbon\Carbon::parse($tt->start_time);
                                        $endTime = \Carbon\Carbon::parse($tt->end_time);
                                        $viewDay = request()->input('view_day', 'today');
                                        
                                        if ($viewDay === 'besok') {
                                            $isUpcoming = false;
                                            $isCurrent = false;
                                            $isPast = false;
                                        } else {
                                            $isUpcoming = $startTime->isFuture() && $startTime->diffInMinutes($currentTime) <= 30;
                                            $isCurrent = $currentTime->between($startTime, $endTime);
                                            $isPast = $endTime->isPast();
                                        }
                                    @endphp
                                    <tr class="@if($isUpcoming) table-warning @elseif($isCurrent) table-success @endif">
                                        <td>
                                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($tt->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($tt->end_time)->format('H:i') }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                        <iconify-icon icon="solar:book-outline" class="fs-12"></iconify-icon>
                                                    </span>
                                                </div>
                                                {{ $tt->classSubject->subject->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary py-1 px-2">
                                                {{ $tt->classSubject->class->name ?? 'N/A' }}
                                                @if($tt->classSubject->class->grade ?? null)
                                                    -{{ $tt->classSubject->class->grade }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info py-1 px-2">
                                                <iconify-icon icon="solar:users-group-rounded-outline" class="fs-12 me-1"></iconify-icon>
                                                {{ $tt->jumlah_murid ?? 0 }} Siswa
                                            </span>
                                        </td>
                                        <td>
                                            @if($viewDay === 'besok')
                                                <span class="badge bg-info-subtle text-info py-1 px-2">
                                                    <i class="bx bxs-circle text-info me-1"></i>Besok
                                                </span>
                                            @elseif($isUpcoming)
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
                                @endif
                            @empty
                                <tr>
                                    <td colspan="{{ $mode === 'murid' ? '7' : '5' }}" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bx bx-calendar-x display-4"></i>
                                            <p class="mt-2">{{ $emptyMessage }}</p>
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

