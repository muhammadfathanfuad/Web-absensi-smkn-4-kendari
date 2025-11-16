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

function exportAbsensiGuru(format = 'pdf', viewType = 'detail') {
    try {
        // Check if export is already in progress
        if (window.exportNavigating) {
            return;
        }
        
        // Get route from window object (injected from blade)
        const exportRoute = window.statusAbsensiRoutes?.export || '';
        
        if (!exportRoute) {
            showExportError('Route tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        
        // Get current filter values
        const selectedSubject = document.getElementById('subject_id');
        const selectedClassroom = document.getElementById('classroom_id');
        const dateFrom = document.getElementById('date_from');
        const dateTo = document.getElementById('date_to');
        const periodPreset = document.getElementById('period_preset');
        const viewTypeInput = document.getElementById('view_type');
        
        // Build export URL with filters
        let exportUrl = exportRoute + '?format=' + format;
        
        if (viewTypeInput && viewTypeInput.value) {
            exportUrl += '&view_type=' + encodeURIComponent(viewTypeInput.value);
        } else {
            exportUrl += '&view_type=' + encodeURIComponent(viewType);
        }
        
        if (periodPreset && periodPreset.value) {
            exportUrl += '&period_preset=' + encodeURIComponent(periodPreset.value);
        }
        
        if (selectedSubject && selectedSubject.value) {
            exportUrl += '&subject_id=' + encodeURIComponent(selectedSubject.value);
        }
        
        if (selectedClassroom && selectedClassroom.value) {
            exportUrl += '&classroom_id=' + encodeURIComponent(selectedClassroom.value);
        }
        
        if (dateFrom && dateFrom.value) {
            exportUrl += '&date_from=' + encodeURIComponent(dateFrom.value);
        }
        
        if (dateTo && dateTo.value) {
            exportUrl += '&date_to=' + encodeURIComponent(dateTo.value);
        }
        
        // Show loading indicator
        const reportType = viewType === 'summary' ? 'Ringkasan Absensi' : 'Rekap Kehadiran Siswa';
        showExportLoading(format, reportType);
        
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
        showExportError('Terjadi kesalahan saat export: ' + error.message);
        window.exportNavigating = false;
    }
}

function switchViewType(type) {
    const viewTypeInput = document.getElementById('view_type');
    const filterForm = document.getElementById('filterForm');
    
    if (viewTypeInput && filterForm) {
        viewTypeInput.value = type;
        // Submit form to reload with new view type
        filterForm.submit();
    }
}

function handlePeriodPreset() {
    const preset = document.getElementById('period_preset');
    const dateFromWrapper = document.getElementById('date_from_wrapper');
    const dateToWrapper = document.getElementById('date_to_wrapper');
    
    if (!preset || !dateFromWrapper || !dateToWrapper) {
        return;
    }
    
    if (preset.value === 'custom') {
        dateFromWrapper.style.display = 'block';
        dateToWrapper.style.display = 'block';
    } else {
        dateFromWrapper.style.display = 'none';
        dateToWrapper.style.display = 'none';
    }
}

function resetFilter() {
    const periodPreset = document.getElementById('period_preset');
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');
    const subjectId = document.getElementById('subject_id');
    const classroomId = document.getElementById('classroom_id');
    const viewTypeInput = document.getElementById('view_type');
    
    if (periodPreset) periodPreset.value = 'custom';
    if (dateFrom) dateFrom.value = '';
    if (dateTo) dateTo.value = '';
    if (subjectId) subjectId.value = '';
    if (classroomId) classroomId.value = '';
    
    // Get default view type from window object (injected from blade)
    const defaultViewType = window.statusAbsensiData?.defaultViewType || 'summary';
    if (viewTypeInput) {
        viewTypeInput.value = defaultViewType;
    }
    
    handlePeriodPreset();
}

// Function to filter detail table
function filterDetailTable() {
    const searchInput = document.getElementById('detailSearchInput');
    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const rows = document.querySelectorAll('#detailTableBody .detail-row');
    const emptyRow = document.getElementById('emptyRow');
    let visibleCount = 0;
    let rowNumber = 1;

    rows.forEach(function(row) {
        const nis = row.getAttribute('data-nis') || '';
        const nama = row.getAttribute('data-nama') || '';
        const kelas = row.getAttribute('data-kelas') || '';
        const mapel = row.getAttribute('data-mapel') || '';
        const tanggal = row.getAttribute('data-tanggal') || '';
        
        // Format tanggal untuk pencarian (multiple formats)
        let tanggalFormatted = '';
        if (tanggal) {
            try {
                const dateObj = new Date(tanggal);
                // Format: dd/mm/yyyy
                const dd = String(dateObj.getDate()).padStart(2, '0');
                const mm = String(dateObj.getMonth() + 1).padStart(2, '0');
                const yyyy = dateObj.getFullYear();
                tanggalFormatted = `${dd}/${mm}/${yyyy} ${dd}-${mm}-${yyyy} ${dd} ${mm} ${yyyy}`.toLowerCase();
            } catch (e) {
                tanggalFormatted = tanggal.toLowerCase();
            }
        }
        
        const searchableText = `${nis} ${nama} ${kelas} ${mapel} ${tanggalFormatted}`;
        
        if (searchTerm === '' || searchableText.includes(searchTerm)) {
            row.style.display = '';
            // Update row number
            const noCell = row.querySelector('.detail-no');
            if (noCell) {
                noCell.textContent = rowNumber++;
            }
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Show/hide empty row
    if (emptyRow) {
        if (visibleCount === 0 && searchTerm !== '') {
            emptyRow.style.display = '';
            const td = emptyRow.querySelector('td');
            if (td) {
                td.colSpan = 8;
            }
            const textMuted = emptyRow.querySelector('.text-muted');
            if (textMuted) {
                textMuted.innerHTML = 
                    '<i class="bx bx-info-circle me-2"></i>Tidak ada data yang sesuai dengan pencarian.';
            }
        } else if (visibleCount === 0 && searchTerm === '') {
            emptyRow.style.display = '';
            const td = emptyRow.querySelector('td');
            if (td) {
                td.colSpan = 8;
            }
            const textMuted = emptyRow.querySelector('.text-muted');
            if (textMuted) {
                textMuted.innerHTML = 
                    '<i class="bx bx-info-circle me-2"></i>Tidak ada data absensi untuk filter yang dipilih.';
            }
        } else {
            emptyRow.style.display = 'none';
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    handlePeriodPreset();
    
    // Initialize detail search if on detail tab
    const detailTab = document.getElementById('detail');
    if (detailTab && detailTab.classList.contains('active')) {
        filterDetailTable();
    }
    
    // Re-filter when switching to detail tab
    const detailTabButton = document.getElementById('detail-tab');
    if (detailTabButton) {
        detailTabButton.addEventListener('shown.bs.tab', function() {
            filterDetailTable();
        });
    }
});

// Expose functions to global scope for inline onclick handlers
window.switchViewType = switchViewType;
window.handlePeriodPreset = handlePeriodPreset;
window.resetFilter = resetFilter;
window.filterDetailTable = filterDetailTable;
window.exportAbsensiGuru = exportAbsensiGuru;

