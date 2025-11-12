    

    <?php $__env->startSection('title', 'Jadwal Pelajaran'); ?>

    <?php $__env->startSection('content'); ?>
        
    <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Jadwal Pelajaran</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Siswa</li>
                        <li class="breadcrumb-item active">Jadwal Pelajaran</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    
        <div class="row">
        <div class="col-12">
                <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 d-flex align-items-center">
                            <i class="bx bx-calendar-check me-2"></i>
                            <span id="jadwalTitle">
                                <?php
                                    $viewDay = request()->input('view_day', 'today');
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
                                        echo 'Jadwal Pelajaran Besok - ' . $nextDate->translatedFormat('l, j F Y');
                                    } else {
                                        echo 'Jadwal Pelajaran Hari Ini - ' . \App\Services\TimeOverrideService::localeFormat('dddd, D MMMM Y');
                                    }
                                ?>
                            </span>
                        </h4>
                        <?php
                            $viewDay = request()->input('view_day', 'today');
                            $buttonText = $viewDay === 'besok' ? 'Hari Ini' : 'Besok';
                            $buttonOnClick = $viewDay === 'besok' ? 'lihatJadwalHariIni()' : 'lihatJadwalBesok()';
                        ?>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="tombolBesok" onclick="<?php echo e($buttonOnClick); ?>">
                            <i class="bx bx-calendar me-1"></i> <?php echo e($buttonText); ?>

                        </button>
                    </div>
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
                                <tbody id="todayTimetableBody">
                                    <?php $__empty_1 = true; $__currentLoopData = $timetables ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                        <td><?php echo e($i + 1); ?></td>
                                        <td>
                                            <h6 class="mb-0"><?php echo e(optional($tt->classSubject->subject)->name ?? '—'); ?></h6>
                                            <small class="text-muted"><?php echo e(optional($tt->classSubject->subject)->code ?? '—'); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e(optional($tt->classSubject->class)->name ?? '—'); ?></span>
                                        </td>
                                        <td>
                                            <?php
                                                // Format jenis kelas: use location_type if available, otherwise use type
                                                // location_type: 'lab' -> 'Lab', 'theory' -> 'Teori'
                                                // type: 'praktik' -> 'Praktik', 'teori' -> 'Teori'
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
                                            ?>
                                            <span class="badge bg-secondary"><?php echo e($typeDisplay); ?></span>
                                        </td>
                                        <td><?php echo e(optional(optional($tt->classSubject->teacher)->user)->full_name ?? '—'); ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo e(\Carbon\Carbon::parse($tt->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($tt->end_time)->format('H:i')); ?></span>
                                        </td>
                                        <td>
                                            <?php
                                                $viewDay = request()->input('view_day', 'today');
                                                if ($viewDay === 'besok') {
                                                    // For tomorrow, always show as "Belum Dimulai"
                                                    $statusBadge = '<span class="badge bg-secondary">Belum Dimulai</span>';
                                                } else {
                                                    // For today, calculate actual status
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
                                            ?>
                                            <?php echo $statusBadge ?? '<span class="badge bg-secondary">Belum Dimulai</span>'; ?>

                                        </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-calendar-x display-4"></i>
                                                <p class="mt-2">Tidak ada jadwal untuk hari ini</p>
                                            </div>
                                        </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    
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
                        
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <label for="weekFilter" class="form-label mb-0">Filter Minggu:</label>
                                <select id="weekFilter" class="form-select" style="width: auto; min-width: 150px;">
                                    <option value="all">Semua Minggu</option>
                                    <option value="ganjil">Minggu Ganjil</option>
                                    <option value="genap">Minggu Genap</option>
                                </select>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false" id="exportJadwalMuridDropdownBtn">
                                    <i class="bx bx-download"></i> <span>Export</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="exportJadwalMurid('pdf'); return false;">
                                            <i class="bx bx-file"></i> Export PDF (.pdf)
                                        </a></li>
                                </ul>
                            </div>
                        </div>

                    <div class="table-responsive" id="printableJadwalPelajaran">
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
                            <tbody id="allScheduleTableBody">
                                <?php $__empty_1 = true; $__currentLoopData = $allTimetables ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
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
                                    ?>
                                    <tr>
                                        <td><?php echo e($i + 1); ?></td>
                                        <td>
                                            <span class="badge <?php echo e($dayBadgeClass); ?>"><?php echo e($dayName); ?></span>
                                        </td>
                                        <td>
                                            <h6 class="mb-0"><?php echo e(optional($tt->classSubject->subject)->name ?? '—'); ?></h6>
                                            <small class="text-muted"><?php echo e(optional($tt->classSubject->subject)->code ?? '—'); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e(optional($tt->classSubject->class)->name ?? '—'); ?></span>
                                        </td>
                                        <td>
                                            <?php
                                                // Format jenis kelas: use location_type if available, otherwise use type
                                                // location_type: 'lab' -> 'Lab', 'theory' -> 'Teori'
                                                // type: 'praktik' -> 'Praktik', 'teori' -> 'Teori'
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
                                            ?>
                                            <span class="badge bg-secondary"><?php echo e($typeDisplay); ?></span>
                                        </td>
                                        <td><?php echo e(optional(optional($tt->classSubject->teacher)->user)->full_name ?? '—'); ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo e(\Carbon\Carbon::parse($tt->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($tt->end_time)->format('H:i')); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-calendar-x display-4"></i>
                                                <p class="mt-2">Tidak ada jadwal pelajaran ditemukan</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    <?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // CRITICAL: Prevent duplicate declarations - use window-level variable
    if (typeof window._jadwalVars === 'undefined') {
        window._jadwalVars = {
            currentViewDay: <?php echo json_encode(request()->input('view_day', 'today') === 'besok' ? 'besok' : null, 512) ?> // null = hari ini, atau 'besok' untuk besok
        };
    }
    
    // Use local reference for convenience
    var currentViewDay = window._jadwalVars.currentViewDay;
    
    // Filter week change handler
    document.addEventListener('DOMContentLoaded', function() {
        const weekFilter = document.getElementById('weekFilter');
        if (weekFilter) {
            weekFilter.addEventListener('change', function() {
                const selectedWeek = this.value;
                loadAllTimetables(selectedWeek);
            });
        }
    });

    // Function to view tomorrow's schedule
    function lihatJadwalBesok() {
        const tbody = document.getElementById('todayTimetableBody');
        const title = document.getElementById('jadwalTitle');
        const tombolBesok = document.getElementById('tombolBesok');
        
        if (!tbody || !title) return;

        // Show loading state
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

        // Build URL with tomorrow parameter
        const url = new URL('<?php echo e(route("murid.jadwal")); ?>', window.location.origin);
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
                currentViewDay = 'besok';
                window._jadwalVars.currentViewDay = 'besok'; // Sync with window
                renderTodayTimetable(data.timetables, data.dayName, data.dateText);
                // Change button to "Hari Ini"
                if (tombolBesok) {
                    tombolBesok.innerHTML = '<i class="bx bx-calendar me-1"></i> Hari Ini';
                    tombolBesok.onclick = lihatJadwalHariIni;
                }
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-muted"><i class="bx bx-calendar-x display-4"></i><p class="mt-2">Tidak ada jadwal untuk besok</p></div></td></tr>';
            }
        })
        .catch(error => {
            console.error('Error loading tomorrow timetable:', error);
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-danger">Terjadi kesalahan saat memuat data.</div></td></tr>';
        });
    }

    // Function to view today's schedule
    function lihatJadwalHariIni() {
        const tbody = document.getElementById('todayTimetableBody');
        const title = document.getElementById('jadwalTitle');
        const tombolBesok = document.getElementById('tombolBesok');
        
        if (!tbody || !title) return;

        // Show loading state
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

        // Build URL without view_day parameter (default to today)
        const url = new URL('<?php echo e(route("murid.jadwal")); ?>', window.location.origin);

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
                currentViewDay = null;
                window._jadwalVars.currentViewDay = null; // Sync with window
                renderTodayTimetable(data.timetables, data.dayName, data.dateText);
                // Change button back to "Besok"
                if (tombolBesok) {
                    tombolBesok.innerHTML = '<i class="bx bx-calendar me-1"></i> Besok';
                    tombolBesok.onclick = lihatJadwalBesok;
                }
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-muted"><i class="bx bx-calendar-x display-4"></i><p class="mt-2">Tidak ada jadwal untuk hari ini</p></div></td></tr>';
            }
        })
        .catch(error => {
            console.error('Error loading today timetable:', error);
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-danger">Terjadi kesalahan saat memuat data.</div></td></tr>';
        });
    }

    // Render today's timetable
    function renderTodayTimetable(timetables, dayName, dateText) {
        const tbody = document.getElementById('todayTimetableBody');
        const title = document.getElementById('jadwalTitle');
        
        if (!tbody || !title) return;

        // Update title
        title.textContent = 'Jadwal Pelajaran ' + dayName + ' - ' + dateText;

        if (timetables.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-muted"><i class="bx bx-calendar-x display-4"></i><p class="mt-2">Tidak ada jadwal untuk hari ini</p></div></td></tr>';
            return;
        }

        let html = '';
        timetables.forEach((tt, index) => {
            // Format jenis kelas
            let typeDisplay = 'Teori';
            if (tt.location_type === 'lab') {
                typeDisplay = 'Lab';
            } else if (tt.location_type === 'theory') {
                typeDisplay = 'Teori';
            } else if (tt.type === 'praktik' || tt.type === 'Praktik') {
                typeDisplay = 'Praktik';
            }

            const startTime = formatTime(tt.start_time);
            const endTime = formatTime(tt.end_time);
            
            // Determine status (simplified - always show as upcoming for tomorrow)
            // Use window-level variable to ensure consistency
            const viewDay = (window._jadwalVars && window._jadwalVars.currentViewDay !== undefined) 
                ? window._jadwalVars.currentViewDay 
                : currentViewDay;
            let statusBadge = '';
            if (viewDay === 'besok') {
                statusBadge = '<span class="badge bg-secondary">Belum Dimulai</span>';
            } else {
                // For today, we could calculate status, but for simplicity, show as upcoming
                statusBadge = '<span class="badge bg-secondary">Belum Dimulai</span>';
            }

            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <h6 class="mb-0">${tt.subject_name || '—'}</h6>
                        ${tt.subject_code ? '<small class="text-muted">' + tt.subject_code + '</small>' : ''}
                    </td>
                    <td><span class="badge bg-info">${tt.class_name || '—'}</span></td>
                    <td><span class="badge bg-secondary">${typeDisplay}</span></td>
                    <td>${tt.teacher_name || '—'}</td>
                    <td><span class="badge bg-primary">${startTime} - ${endTime}</span></td>
                    <td>${statusBadge}</td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    // Format time helper
    function formatTime(timeStr) {
        if (!timeStr) return '—';
        const parts = timeStr.split(':');
        if (parts.length >= 2) {
            return parts[0].padStart(2, '0') + ':' + parts[1].padStart(2, '0');
        }
        return timeStr;
    }

    // Load all timetables based on week filter
    function loadAllTimetables(weekType = 'all') {
        const tbody = document.getElementById('allScheduleTableBody');
        if (!tbody) return;

        // Show loading state
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

        // Build URL with filter
        const url = new URL('<?php echo e(route("murid.jadwal")); ?>', window.location.origin);
        url.searchParams.set('week_filter', weekType);

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
                renderTimetablesTable(data.timetables);
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-muted"><i class="bx bx-calendar-x display-4"></i><p class="mt-2">Tidak ada jadwal pelajaran ditemukan</p></div></td></tr>';
            }
        })
        .catch(error => {
            console.error('Error loading timetables:', error);
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-danger">Terjadi kesalahan saat memuat data.</div></td></tr>';
        });
    }

    // Render timetables table
    function renderTimetablesTable(timetables) {
        const tbody = document.getElementById('allScheduleTableBody');
        if (!tbody) return;

        if (timetables.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-muted"><i class="bx bx-calendar-x display-4"></i><p class="mt-2">Tidak ada jadwal pelajaran ditemukan</p></div></td></tr>';
            return;
        }

        const days = {
            1: 'Senin',
            2: 'Selasa',
            3: 'Rabu',
            4: 'Kamis',
            5: 'Jumat',
            6: 'Sabtu',
            7: 'Minggu'
        };

        const dayClass = {
            1: 'bg-primary',
            2: 'bg-success',
            3: 'bg-warning',
            4: 'bg-info',
            5: 'bg-danger',
            6: 'bg-secondary',
            7: 'bg-dark'
        };

        let html = '';
        timetables.forEach((tt, index) => {
            const dayName = days[tt.day_of_week] || 'Unknown';
            const dayBadgeClass = dayClass[tt.day_of_week] || 'bg-secondary';
            
            // Format jenis kelas
            let typeDisplay = 'Teori';
            if (tt.location_type === 'lab') {
                typeDisplay = 'Lab';
            } else if (tt.location_type === 'theory') {
                typeDisplay = 'Teori';
            } else if (tt.type === 'praktik' || tt.type === 'Praktik') {
                typeDisplay = 'Praktik';
            }

            // Format time (assuming format is HH:mm:ss or HH:mm)
            const formatTime = (timeStr) => {
                if (!timeStr) return '—';
                const parts = timeStr.split(':');
                if (parts.length >= 2) {
                    return parts[0].padStart(2, '0') + ':' + parts[1].padStart(2, '0');
                }
                return timeStr;
            };
            
            const startTime = formatTime(tt.start_time);
            const endTime = formatTime(tt.end_time);

            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td><span class="badge ${dayBadgeClass}">${dayName}</span></td>
                    <td>
                        <h6 class="mb-0">${tt.subject_name || '—'}</h6>
                        ${tt.subject_code ? '<small class="text-muted">' + tt.subject_code + '</small>' : ''}
                    </td>
                    <td><span class="badge bg-info">${tt.class_name || '—'}</span></td>
                    <td><span class="badge bg-secondary">${typeDisplay}</span></td>
                    <td>${tt.teacher_name || '—'}</td>
                    <td><span class="badge bg-primary">${startTime} - ${endTime}</span></td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

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
    
    // Export function for student timetable
    function exportJadwalMurid(format = 'pdf') {
        try {
            // Check if export is already in progress
            if (window.exportNavigating) {
                console.log('Export already in progress, skipping...');
                return;
            }

            // Get selected week filter
            const weekFilter = document.getElementById('weekFilter');
            const selectedWeek = weekFilter ? weekFilter.value : 'all';

            // Build export URL with week filter
            let exportUrl = '<?php echo e(route("murid.jadwal.export")); ?>?format=' + format;
            if (selectedWeek && selectedWeek !== 'all') {
                exportUrl += '&week_filter=' + encodeURIComponent(selectedWeek);
            }
            
            // Show loading indicator
            showExportLoading(format, 'Jadwal Pelajaran');
            
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
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.vertical-murid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/murid/jadwal-pelajaran.blade.php ENDPATH**/ ?>