// Jadwal Pelajaran JavaScript
// This file handles timetable functionality for students

// CRITICAL: Prevent duplicate declarations - use window-level variable
if (typeof window._jadwalVars === 'undefined') {
    window._jadwalVars = {
        currentViewDay: null // Will be set from blade template
    };
}

// Use local reference for convenience
var currentViewDay = window._jadwalVars.currentViewDay;

// Function to view tomorrow's schedule
function lihatJadwalBesok() {
    const tbody = document.getElementById('todayTimetableBody');
    const title = document.getElementById('jadwalTitle');
    const tombolBesok = document.getElementById('tombolBesok');
    
    if (!tbody || !title) return;

    // Show loading state
    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

    // Get route from window object
    const jadwalRoute = window.jadwalMuridRoutes && window.jadwalMuridRoutes.index;
    if (!jadwalRoute) {
        console.error('Jadwal route not found');
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-danger">Error: Route tidak ditemukan.</div></td></tr>';
        return;
    }

    // Build URL with tomorrow parameter
    const url = new URL(jadwalRoute, window.location.origin);
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

    // Get route from window object
    const jadwalRoute = window.jadwalMuridRoutes && window.jadwalMuridRoutes.index;
    if (!jadwalRoute) {
        console.error('Jadwal route not found');
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-danger">Error: Route tidak ditemukan.</div></td></tr>';
        return;
    }

    // Build URL without view_day parameter (default to today)
    const url = new URL(jadwalRoute, window.location.origin);

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

    // Get route from window object
    const jadwalRoute = window.jadwalMuridRoutes && window.jadwalMuridRoutes.index;
    if (!jadwalRoute) {
        console.error('Jadwal route not found');
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-danger">Error: Route tidak ditemukan.</div></td></tr>';
        return;
    }

    // Build URL with filter
    const url = new URL(jadwalRoute, window.location.origin);
    url.searchParams.set('week_filter', weekType);

    // Fetch data
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Check if response has allTimetables (for all schedules table)
        if (data.success) {
            // The controller returns 'allTimetables' for the filtered data
            const timetables = data.allTimetables || data.timetables || [];
            if (timetables.length > 0) {
                renderTimetablesTable(timetables);
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-muted"><i class="bx bx-calendar-x display-4"></i><p class="mt-2">Tidak ada jadwal pelajaran ditemukan</p></div></td></tr>';
            }
        } else {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-muted"><i class="bx bx-calendar-x display-4"></i><p class="mt-2">Tidak ada jadwal pelajaran ditemukan</p></div></td></tr>';
        }
    })
    .catch(error => {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="text-danger">Terjadi kesalahan saat memuat data: ' + error.message + '</div></td></tr>';
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
        // Use day_name from response if available, otherwise map from day_of_week
        const dayName = tt.day_name || days[tt.day_of_week] || 'Unknown';
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
            return;
        }

        // Get selected week filter
        const weekFilter = document.getElementById('weekFilter');
        const selectedWeek = weekFilter ? weekFilter.value : 'all';

        // Get route from window object
        const exportRoute = window.jadwalMuridRoutes && window.jadwalMuridRoutes.export;
        if (!exportRoute) {
            console.error('Export route not found');
            showExportError('Error: Route export tidak ditemukan.');
            return;
        }

        // Build export URL with week filter
        let exportUrl = exportRoute + '?format=' + format;
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

// Expose functions to global scope for onclick handlers
window.lihatJadwalBesok = lihatJadwalBesok;
window.lihatJadwalHariIni = lihatJadwalHariIni;
window.exportJadwalMurid = exportJadwalMurid;
window.loadAllTimetables = loadAllTimetables;

// Filter week change handler - Use event delegation for reliability
// This approach works even if the element is added dynamically
(function() {
    function handleWeekFilterChange(e) {
        // Check if the event target is the weekFilter select element
        if (e.target && e.target.id === 'weekFilter') {
            const selectedWeek = e.target.value;
            
            // Use window.loadAllTimetables to ensure it's available
            if (window.loadAllTimetables && typeof window.loadAllTimetables === 'function') {
                window.loadAllTimetables(selectedWeek);
            }
        }
    }
    
    // Use event delegation on document - wait for body to be available
    function setupEventDelegation() {
        if (document.body) {
            document.body.addEventListener('change', handleWeekFilterChange);
        } else {
            // Wait for body to be available
            setTimeout(setupEventDelegation, 50);
        }
    }
    setupEventDelegation();
    
    // Also try direct attachment when DOM is ready
    function setupDirectListener() {
        const weekFilter = document.getElementById('weekFilter');
        if (weekFilter) {
            // Remove any existing listener by cloning (prevents duplicates)
            const newWeekFilter = weekFilter.cloneNode(true);
            weekFilter.parentNode.replaceChild(newWeekFilter, weekFilter);
            
            // Attach direct listener
            newWeekFilter.addEventListener('change', function(e) {
                const selectedWeek = this.value;
                
                if (window.loadAllTimetables && typeof window.loadAllTimetables === 'function') {
                    window.loadAllTimetables(selectedWeek);
                }
            });
        }
    }
    
    // Try to setup immediately if DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupDirectListener);
    } else {
        // DOM is already ready
        setupDirectListener();
    }
    
    // Also try after delays to ensure everything is loaded
    setTimeout(setupDirectListener, 100);
    setTimeout(setupDirectListener, 500);
    setTimeout(setupDirectListener, 1000);
})();

