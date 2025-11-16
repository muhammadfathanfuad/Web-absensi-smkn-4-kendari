// Riwayat Absensi JavaScript
// This file handles attendance history functionality for students

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Flatpickr for date range filter
    // Check if flatpickr is loaded (from CDN)
    if (typeof flatpickr !== 'undefined') {
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
    } else {
        // If flatpickr is not loaded yet, wait a bit and try again
        setTimeout(function() {
            if (typeof flatpickr !== 'undefined') {
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
            }
        }, 500);
    }
});

// Show loading indicator
function showExportLoading(format = 'pdf', reportType = '', message = '', type = 'info') {
    const formatText = format.toUpperCase();
    const iconClass = type === 'success' ? 'bx-check-circle' : type === 'danger' ? 'bx-x-circle' : 'bx-loader-alt';
    const iconColor = type === 'success' ? '#28a745' : type === 'danger' ? '#dc3545' : '#007bff';
    const bgColor = type === 'success' ? '#d4edda' : type === 'danger' ? '#f8d7da' : '#d1ecf1';
    const borderColor = type === 'success' ? '#c3e6cb' : type === 'danger' ? '#f5c6cb' : '#bee5eb';
    const spinClass = type === 'info' ? 'bx-spin' : '';
    
    const iconHtml = type === 'info' 
        ? `<i class="bx bx-loader-alt ${spinClass}" style="font-size: 24px; color: ${iconColor};"></i>`
        : `<i class="bx ${iconClass}" style="font-size: 24px; color: ${iconColor};"></i>`;
    
    const loadingHtml = `
        <div id="exportLoading" class="alert alert-${type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'info'} show" 
             style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); background-color: ${bgColor}; border-color: ${borderColor};">
            <div class="d-flex align-items-center gap-2">
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

// Export function for student attendance history
function exportAbsensiMurid(format = 'pdf') {
    try {
        // Prevent duplicate calls
        if (window.exportNavigating) {
            return;
        }
        
        // Get filter values
        const fromDate = document.getElementById('date-from')?.value || '';
        const toDate = document.getElementById('date-to')?.value || '';
        const subjectId = document.getElementById('subject_id')?.value || '';
        
        // Get route from window object
        const exportRoute = window.riwayatAbsensiRoutes && window.riwayatAbsensiRoutes.export;
        if (!exportRoute) {
            showExportError('Error: Route export tidak ditemukan.');
            return;
        }
        
        // Build export URL
        let exportUrl = exportRoute + '?format=' + format;
        if (fromDate) {
            exportUrl += '&from=' + encodeURIComponent(fromDate);
        }
        if (toDate) {
            exportUrl += '&to=' + encodeURIComponent(toDate);
        }
        if (subjectId) {
            exportUrl += '&subject_id=' + encodeURIComponent(subjectId);
        }
        
        // Show loading indicator
        showExportLoading(format, 'Riwayat Absensi');
        
        // Set flag to prevent duplicate calls
        window.exportNavigating = true;
        
        // Trigger download
        window.location.href = exportUrl;
        
        // Show success message after a delay
        setTimeout(function() {
            showExportSuccess('Export berhasil! File sedang diunduh.');
            window.exportNavigating = false;
        }, 2000);
        
    } catch (error) {
        showExportError('Terjadi kesalahan saat mengexport data. Silakan coba lagi.');
        window.exportNavigating = false;
    }
}

// Expose function to global scope for onclick handlers
window.exportAbsensiMurid = exportAbsensiMurid;

