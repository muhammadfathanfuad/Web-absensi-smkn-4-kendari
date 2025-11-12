@extends('layouts.vertical-guru', ['subtitle' => 'Jadwal Mengajar'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Jadwal Mengajar'])

    {{-- Jadwal Hari Ini --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                        <iconify-icon icon="solar:calendar-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                        <h4 class="card-title mb-1" id="jadwalTitle">
                            Jadwal Mengajar {{ isset($viewDay) && $viewDay === 'besok' ? 'Besok' : 'Hari Ini' }}
                    </h4>
                        <p class="text-muted mb-0" id="jadwalDate">
                            @if(isset($viewDate))
                                {{ $viewDate->translatedFormat('l, j F Y') }}
                            @else
                                {{ \App\Services\TimeOverrideService::translatedFormat('l, j F Y') }}
                            @endif
                        </p>
                    </div>
                </div>
                @php
                    $viewDay = request()->input('view_day', 'today');
                    $buttonText = $viewDay === 'besok' ? 'Hari Ini' : 'Besok';
                    $buttonOnClick = $viewDay === 'besok' ? 'lihatJadwalHariIni()' : 'lihatJadwalBesok()';
                @endphp
                <button type="button" class="btn btn-outline-primary btn-sm" id="tombolBesok" onclick="{{ $buttonOnClick }}">
                    <i class="bx bx-calendar me-1"></i> {{ $buttonText }}
                </button>
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
                    <tbody id="todayTimetableBody">
                        @forelse ($jadwalHariIni as $index => $jadwal)
                            @php
                                $currentTime = \App\Services\TimeOverrideService::now();
                                $startTime = \Carbon\Carbon::parse($jadwal->start_time);
                                $endTime = \Carbon\Carbon::parse($jadwal->end_time);
                                $viewDay = request()->input('view_day', 'today');
                                // Only show status for today, not for tomorrow
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
                                    <span class="badge bg-primary-subtle text-primary py-1 px-2">
                                        {{ $jadwal->classSubject->class->name ?? 'N/A' }}
                                        @if($jadwal->classSubject->class->grade ?? null)
                                            -{{ $jadwal->classSubject->class->grade }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info py-1 px-2">
                                        <iconify-icon icon="solar:users-group-rounded-outline" class="fs-12 me-1"></iconify-icon>
                                        {{ $jadwal->jumlah_murid ?? 0 }} Siswa
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
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted text-center">
                                        <iconify-icon icon="solar:calendar-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                        Tidak ada jadwal mengajar {{ isset($viewDay) && $viewDay === 'besok' ? 'besok' : 'hari ini' }}.
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
            {{-- Tombol Export untuk Jadwal Semester --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Jadwal Mengajar Semester Ini</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false" id="exportJadwalGuruDropdownBtn">
                        <i class="bx bx-download"></i> <span>Export</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="exportJadwalGuru('pdf'); return false;">
                                <i class="bx bx-file"></i> Export PDF (.pdf)
                            </a></li>
                    </ul>
                </div>
            </div>
            
            <div class="table-responsive" id="printableJadwalSemester">
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

@section('scripts')
<script>
    // Show loading indicator for export
    function showExportLoading(format, reportType = '', message = '', type = 'info') {
        const formatText = format === 'pdf' ? 'PDF' : 'File';
        const alertClass = type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : 'alert-info');
        const iconClass = type === 'success' ? 'bx-check-circle' : (type === 'danger' ? 'bx-x-circle' : '');
        const spinnerHtml = type === 'success' || type === 'danger' ? '' : '<div class="spinner-border spinner-border-sm me-2" role="status"><span class="visually-hidden">Loading...</span></div>';
        const iconHtml = iconClass ? `<i class="bx ${iconClass} me-2" style="font-size: 1.2em;"></i>` : '';
        
        const loadingHtml = `
            <div id="exportLoading" class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
                <div class="d-flex align-items-center">
                    ${spinnerHtml}
                    ${iconHtml}
                    <div>
                        <strong>${message || `Sedang memproses export ${formatText}${reportType ? ' - ' + reportType : ''}...`}</strong>
                        ${message ? '' : '<br><small>File akan segera diunduh</small>'}
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing loading if any
        const existingLoading = document.getElementById('exportLoading');
        if (existingLoading) {
            existingLoading.remove();
        }
        
        // Add new loading indicator
        document.body.insertAdjacentHTML('beforeend', loadingHtml);
    }
    
    // Show success message in loading indicator
    function showExportSuccess(message = 'Export berhasil! File sedang diunduh.') {
        showExportLoading('pdf', '', message, 'success');
        setTimeout(function() {
            const loadingElement = document.getElementById('exportLoading');
            if (loadingElement) {
                loadingElement.classList.remove('show');
                setTimeout(function() {
                    loadingElement.remove();
                }, 150);
            }
        }, 3000);
    }
    
    // Show error message in loading indicator
    function showExportError(message = 'Gagal mengexport data. Silakan coba lagi atau hubungi administrator.') {
        showExportLoading('pdf', '', message, 'danger');
        setTimeout(function() {
            const loadingElement = document.getElementById('exportLoading');
            if (loadingElement) {
                loadingElement.classList.remove('show');
                setTimeout(function() {
                    loadingElement.remove();
                }, 3000);
            }
        }, 5000);
    }
    
    // Export function for teacher timetable
    function exportJadwalGuru(format = 'pdf') {
        try {
            // Check if export is already in progress
            if (window.exportNavigating) {
                console.log('Export already in progress, skipping...');
                return;
            }

            // Build export URL
            let exportUrl = '{{ route("guru.jadwal-mengajar.export") }}?format=' + format;
            
            // Show loading indicator
            showExportLoading(format, 'Jadwal Mengajar');
            
            // Mark as navigating to prevent duplicate
            window.exportNavigating = true;
            
            // Use window.location.href for direct download (more reliable)
            window.location.href = exportUrl;
            
            // Show success message after a delay
            setTimeout(function() {
                showExportSuccess('Export berhasil! File sedang diunduh.');
                window.exportNavigating = false;
            }, 2000);
            
        } catch (error) {
            console.error('Export error:', error);
            showExportError('Terjadi kesalahan saat export: ' + error.message);
            window.exportNavigating = false;
        }
    }

    // Function to view tomorrow's schedule
    function lihatJadwalBesok() {
        const tbody = document.getElementById('todayTimetableBody');
        const title = document.getElementById('jadwalTitle');
        const dateText = document.getElementById('jadwalDate');
        const tombolBesok = document.getElementById('tombolBesok');
        
        if (!tbody || !title) return;

        // Show loading state
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

        // Build URL with tomorrow parameter
        const url = new URL('{{ route("guru.jadwal-mengajar") }}', window.location.origin);
        url.searchParams.set('view_day', 'besok');

        // Fetch data
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.timetables) {
                renderTodayTimetable(data.timetables, data.dayName, data.dateText, true);
                // Change button to "Hari Ini"
                if (tombolBesok) {
                    tombolBesok.innerHTML = '<i class="bx bx-calendar me-1"></i> Hari Ini';
                    tombolBesok.onclick = lihatJadwalHariIni;
                }
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="text-muted"><iconify-icon icon="solar:calendar-x" class="fs-48 d-block mx-auto mb-2"></iconify-icon><p class="mt-2">Tidak ada jadwal untuk besok</p></div></td></tr>';
            }
        })
        .catch(error => {
            console.error('Error loading tomorrow timetable:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="text-danger">Terjadi kesalahan saat memuat data.</div></td></tr>';
        });
    }

    // Function to view today's schedule
    function lihatJadwalHariIni() {
        const tbody = document.getElementById('todayTimetableBody');
        const title = document.getElementById('jadwalTitle');
        const dateText = document.getElementById('jadwalDate');
        const tombolBesok = document.getElementById('tombolBesok');
        
        if (!tbody || !title) return;

        // Show loading state
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

        // Build URL without view_day parameter (default to today)
        const url = new URL('{{ route("guru.jadwal-mengajar") }}', window.location.origin);

        // Fetch data
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.timetables) {
                renderTodayTimetable(data.timetables, data.dayName, data.dateText, false);
                // Change button back to "Besok"
                if (tombolBesok) {
                    tombolBesok.innerHTML = '<i class="bx bx-calendar me-1"></i> Besok';
                    tombolBesok.onclick = lihatJadwalBesok;
                }
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="text-muted"><iconify-icon icon="solar:calendar-x" class="fs-48 d-block mx-auto mb-2"></iconify-icon><p class="mt-2">Tidak ada jadwal untuk hari ini</p></div></td></tr>';
            }
        })
        .catch(error => {
            console.error('Error loading today timetable:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="text-danger">Terjadi kesalahan saat memuat data.</div></td></tr>';
        });
    }

    // Render today's timetable
    function renderTodayTimetable(timetables, dayName, dateText, isTomorrow = false) {
        const tbody = document.getElementById('todayTimetableBody');
        const title = document.getElementById('jadwalTitle');
        const dateElement = document.getElementById('jadwalDate');
        
        if (!tbody) return;

        // Update title and date
        if (title) {
            title.textContent = 'Jadwal Mengajar ' + (isTomorrow ? 'Besok' : 'Hari Ini');
        }
        if (dateElement) {
            dateElement.textContent = dateText;
        }

        if (!timetables || timetables.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="text-muted text-center"><iconify-icon icon="solar:calendar-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>Tidak ada jadwal mengajar ' + (isTomorrow ? 'besok' : 'hari ini') + '.</div></td></tr>';
            return;
        }
        const currentTime = new Date();
        
        let html = '';
        timetables.forEach(function(jadwal) {
            const startTime = new Date('1970-01-01T' + jadwal.start_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            const endTime = new Date('1970-01-01T' + jadwal.end_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            const classGrade = jadwal.class_grade ? '-' + jadwal.class_grade : '';
            
            // Determine status badge
            let statusBadge = '';
            if (isTomorrow) {
                statusBadge = '<span class="badge bg-info-subtle text-info py-1 px-2"><i class="bx bxs-circle text-info me-1"></i>Besok</span>';
            } else {
                // Calculate status for today
                const startDateTime = new Date('1970-01-01T' + jadwal.start_time);
                const endDateTime = new Date('1970-01-01T' + jadwal.end_time);
                const nowTime = new Date('1970-01-01T' + currentTime.toTimeString().slice(0, 5));
                
                const startMinutes = startDateTime.getHours() * 60 + startDateTime.getMinutes();
                const endMinutes = endDateTime.getHours() * 60 + endDateTime.getMinutes();
                const nowMinutes = nowTime.getHours() * 60 + nowTime.getMinutes();
                
                if (nowMinutes >= startMinutes && nowMinutes <= endMinutes) {
                    statusBadge = '<span class="badge bg-success-subtle text-success py-1 px-2"><i class="bx bxs-circle text-success me-1"></i>Sedang Berlangsung</span>';
                } else if (nowMinutes < startMinutes && (startMinutes - nowMinutes) <= 30) {
                    statusBadge = '<span class="badge bg-warning-subtle text-warning py-1 px-2"><i class="bx bxs-circle text-warning me-1"></i>Segera Dimulai</span>';
                } else {
                    statusBadge = '<span class="badge bg-secondary-subtle text-secondary py-1 px-2"><i class="bx bxs-circle text-secondary me-1"></i>Selesai</span>';
                }
            }

            html += `
                <tr>
                    <td>
                        <span class="fw-semibold">${startTime} - ${endTime}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-2">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    <iconify-icon icon="solar:book-outline" class="fs-12"></iconify-icon>
                                </span>
                            </div>
                            ${jadwal.subject_name || 'N/A'}
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary py-1 px-2">
                            ${jadwal.class_name || 'N/A'}${classGrade}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-info-subtle text-info py-1 px-2">
                            <iconify-icon icon="solar:users-group-rounded-outline" class="fs-12 me-1"></iconify-icon>
                            ${jadwal.jumlah_murid || 0} Siswa
                        </span>
                    </td>
                    <td>
                        ${statusBadge}
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }
</script>
@endsection