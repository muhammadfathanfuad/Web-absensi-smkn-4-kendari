    @extends('layouts.vertical-murid')

    @section('title', 'Jadwal Pelajaran')

    @section('content')
        {{-- Page Title --}}
    <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Jadwal Pelajaran</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('murid.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Jadwal Pelajaran</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    {{-- Jadwal Pelajaran Hari Ini --}}
        <div class="row">
        <div class="col-12">
                <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-calendar-check me-2"></i>
                        Jadwal Pelajaran Hari Ini - {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </h4>
                </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover table-centered">
                            <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Mata Pelajaran</th>
                                        <th scope="col">Kelas</th>
                                        <th scope="col">Jenis Kelas</th>
                                        <th scope="col">Guru</th>
                                        <th scope="col">Jam</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($timetables ?? collect() as $i => $tt)
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
                                            @php
                                                $classGrade = optional($tt->classSubject->class)->grade ?? 0;
                                                $classType = '';
                                                if ($classGrade == 11) {
                                                    $classType = optional($tt->classSubject->class)->class_type ?? '—';
                                                }
                                            @endphp
                                            <span class="badge bg-secondary">{{ $classType ?: '—' }}</span>
                                        </td>
                                        <td>{{ optional(optional($tt->classSubject->teacher)->user)->full_name ?? '—' }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ \Carbon\Carbon::parse($tt->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($tt->end_time)->format('H:i') }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $startTime = \Carbon\Carbon::parse($tt->start_time);
                                                $endTime = \Carbon\Carbon::parse($tt->end_time);
                                            @endphp
                                            @if($now->lt($startTime))
                                                <span class="badge bg-secondary">Belum Dimulai</span>
                                            @elseif($now->between($startTime, $endTime))
                                                <span class="badge bg-primary">Sedang Berlangsung</span>
                                            @else
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                        </tr>
                                    @empty
                                        <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-calendar-x display-4"></i>
                                                <p class="mt-2">Tidak ada jadwal untuk hari ini</p>
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

    {{-- Semua Jadwal Pelajaran --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-calendar me-2"></i>
                        Semua Jadwal Pelajaran
                    </h4>
                </div>
                    <div class="card-body">
                    {{-- Filter Hari --}}
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="dayFilter" class="form-label">Filter Hari</label>
                            <select class="form-select" id="dayFilter">
                                <option value="">Semua Hari</option>
                                <option value="monday">Senin</option>
                                <option value="tuesday">Selasa</option>
                                <option value="wednesday">Rabu</option>
                                <option value="thursday">Kamis</option>
                                <option value="friday">Jumat</option>
                                <option value="saturday">Sabtu</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="subjectFilter" class="form-label">Filter Mata Pelajaran</label>
                            <select class="form-select" id="subjectFilter">
                                <option value="">Semua Mata Pelajaran</option>
                                @if(isset($allTimetables))
                                    @php
                                        $subjects = $allTimetables->pluck('classSubject.subject.name')->filter()->unique()->sort();
                                    @endphp
                                    @foreach($subjects as $subject)
                                        <option value="{{ strtolower(str_replace(' ', '-', $subject)) }}">{{ $subject }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-centered" id="allScheduleTable">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Hari</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Jenis Kelas</th>
                                    <th scope="col">Guru</th>
                                    <th scope="col">Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($allTimetables ?? collect() as $i => $tt)
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
                                    @endphp
                                    <tr data-day="{{ strtolower($dayName) }}" data-subject="{{ strtolower(str_replace(' ', '-', optional($tt->classSubject->subject)->name ?? '')) }}">
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
                                            @php
                                                $classGrade = optional($tt->classSubject->class)->grade ?? 0;
                                                $classType = '';
                                                if ($classGrade == 11) {
                                                    $classType = optional($tt->classSubject->class)->class_type ?? '—';
                                                }
                                            @endphp
                                            <span class="badge bg-secondary">{{ $classType ?: '—' }}</span>
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
                                                <p class="mt-2">Tidak ada jadwal pelajaran ditemukan</p>
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
        <script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const dayFilter = document.getElementById('dayFilter');
    const subjectFilter = document.getElementById('subjectFilter');
    const table = document.getElementById('allScheduleTable');
    const rows = table.querySelectorAll('tbody tr');

    function filterTable() {
        const selectedDay = dayFilter.value;
        const selectedSubject = subjectFilter.value;

        rows.forEach(row => {
            const rowDay = row.getAttribute('data-day');
            const rowSubject = row.getAttribute('data-subject');
            
            const dayMatch = !selectedDay || rowDay === selectedDay;
            const subjectMatch = !selectedSubject || rowSubject === selectedSubject;
            
            if (dayMatch && subjectMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    dayFilter.addEventListener('change', filterTable);
    subjectFilter.addEventListener('change', filterTable);
            });
        </script>
@endpush