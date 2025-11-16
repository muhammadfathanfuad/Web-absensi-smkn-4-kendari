// Permohonan Izin JavaScript
// This file handles leave request functionality for students

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('permohonanForm');
    const tanggalMulai = document.getElementById('tanggalMulai');
    const tanggalSelesai = document.getElementById('tanggalSelesai');
    const tanggalSelesaiNormal = document.getElementById('tanggalSelesaiNormal');
    const jenisIzin = document.getElementById('jenisIzin');
    const jenisIzinLainnya = document.getElementById('jenisIzinLainnya');
    const jenisIzinCustom = document.getElementById('jenisIzinCustom');
    const modalElement = document.getElementById('notificationModal');
    
    if (!form || !tanggalMulai || !tanggalSelesai || !tanggalSelesaiNormal || !jenisIzin || !jenisIzinLainnya || !jenisIzinCustom || !modalElement) {
        return;
    }
    
    const notificationModal = new bootstrap.Modal(modalElement);
    
    // Flag to prevent double submission
    let isSubmitting = false;
    
    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    tanggalMulai.value = today;
    tanggalMulai.min = today;
    tanggalSelesai.min = today;
    tanggalSelesaiNormal.min = today;
    
    // Set default end date to today as well
    tanggalSelesaiNormal.value = today;

    // Function to clean up modal backdrop
    function cleanupModal() {
        // Remove backdrop if it exists
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // Remove modal-open class from body
        document.body.classList.remove('modal-open');
        
        // Reset body style
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    // Function to show notification modal
    function showNotification(message, isSuccess = true) {
        const modalLabel = document.getElementById('notificationModalLabel');
        const modalMessage = document.getElementById('notificationMessage');
        
        if (!modalLabel || !modalMessage) return;
        
        modalLabel.innerText = isSuccess ? 'Berhasil' : 'Gagal';
        modalMessage.innerText = message;
        
        // Clean up any existing backdrop before showing
        cleanupModal();
        
        // Show modal
        notificationModal.show();
    }
    
    // Listen for modal hidden event to ensure cleanup
    modalElement.addEventListener('hidden.bs.modal', function() {
        cleanupModal();
    });

    // Show/hide custom jenis izin field
    jenisIzin.addEventListener('change', function() {
        if (this.value === 'lainnya') {
            jenisIzinLainnya.style.display = 'block';
            const tanggalSelesaiNormalEl = document.getElementById('tanggalSelesaiNormal');
            if (tanggalSelesaiNormalEl) {
                tanggalSelesaiNormalEl.style.display = 'none';
            }
            jenisIzinCustom.required = true;
            tanggalSelesai.required = true;
            tanggalSelesaiNormal.required = false;
        } else {
            jenisIzinLainnya.style.display = 'none';
            const tanggalSelesaiNormalEl = document.getElementById('tanggalSelesaiNormal');
            if (tanggalSelesaiNormalEl) {
                tanggalSelesaiNormalEl.style.display = 'block';
            }
            jenisIzinCustom.required = false;
            jenisIzinCustom.value = '';
            tanggalSelesai.required = false;
            tanggalSelesaiNormal.required = true;
        }
    });

    // Update end date when start date changes
    function updateEndDate() {
        const startDate = tanggalMulai.value;
        tanggalSelesai.min = startDate;
        tanggalSelesaiNormal.min = startDate;
        
        if (tanggalSelesai.value && tanggalSelesai.value < startDate) {
            tanggalSelesai.value = startDate;
        }
        if (tanggalSelesaiNormal.value && tanggalSelesaiNormal.value < startDate) {
            tanggalSelesaiNormal.value = startDate;
        }
    }

    tanggalMulai.addEventListener('change', updateEndDate);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Prevent double submission
        if (isSubmitting) {
            console.log('Already submitting, please wait...');
            return;
        }
        
        console.log('Form submitted'); // Debug log
        
        // Set flag to prevent double submission
        isSubmitting = true;
        
        // Validate custom jenis izin if "lainnya" is selected
        if (jenisIzin.value === 'lainnya' && !jenisIzinCustom.value.trim()) {
            isSubmitting = false;
            showNotification('Harap isi jenis izin lainnya.', false);
            return;
        }
        
        // Validate file size (500KB = 512000 bytes)
        const fileInput = document.getElementById('dokumenPendukung');
        if (fileInput && fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 512000; // 500KB in bytes
            if (file.size > maxSize) {
                isSubmitting = false;
                showNotification('Ukuran file dokumen pendukung maksimal 500KB.', false);
                return;
            }
        }
        
        // Validate end date based on leave type
        let endDateValue = '';
        if (jenisIzin.value === 'lainnya') {
            endDateValue = tanggalSelesai.value;
            if (!endDateValue) {
                isSubmitting = false;
                showNotification('Harap isi tanggal selesai.', false);
                return;
            }
        } else {
            endDateValue = tanggalSelesaiNormal.value;
            if (!endDateValue) {
                isSubmitting = false;
                showNotification('Harap isi tanggal selesai.', false);
                return;
            }
        }
        
        console.log('Selected end date value:', endDateValue);
        
        // Show loading and prevent double submission
        const submitButton = form.querySelector('button[type="submit"]');
        if (!submitButton) {
            isSubmitting = false;
            return;
        }
        
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Mengirim...';
        submitButton.disabled = true;
        
        // Disable form to prevent double submission
        form.style.pointerEvents = 'none';
        
        // Prepare form data
        const formData = new FormData(form);
        
        // Debug: Log form data
        console.log('Form action:', form.action);
        console.log('Form data entries:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        // Debug: Log specific date values
        console.log('Jenis izin:', jenisIzin.value);
        console.log('Tanggal mulai:', tanggalMulai.value);
        console.log('Tanggal selesai (lainnya):', tanggalSelesai.value);
        console.log('Tanggal selesai (normal):', tanggalSelesaiNormal.value);
        console.log('Jenis izin custom:', jenisIzinCustom.value);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        // Send AJAX request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json().then(data => {
                if (!response.ok) {
                    throw { status: response.status, data: data };
                }
                return data;
            });
        })
        .then(data => {
            console.log('Response data:', data);
            
            // Reset flag after successful response
            isSubmitting = false;
            
            // Reset button and enable form
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            form.style.pointerEvents = 'auto';
            
            if (data.success) {
                showNotification(data.message, true);
                form.reset();
                // Reset default date to today
                tanggalMulai.value = today;
                tanggalSelesaiNormal.value = today;
                // Hide custom field and show normal field
                jenisIzinLainnya.style.display = 'none';
                const tanggalSelesaiNormalEl = document.getElementById('tanggalSelesaiNormal');
                if (tanggalSelesaiNormalEl) {
                    tanggalSelesaiNormalEl.style.display = 'block';
                }
                jenisIzinCustom.required = false;
                // Reload page to show updated data
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showNotification(data.message || 'Terjadi kesalahan saat mengajukan permohonan.', false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Reset flag on error
            isSubmitting = false;
            
            // Reset button and enable form
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            form.style.pointerEvents = 'auto';
            
            // Handle validation errors
            if (error.status === 422 && error.data && error.data.errors) {
                const firstError = Object.values(error.data.errors)[0][0];
                showNotification(firstError || 'Validasi gagal.', false);
            } else {
                showNotification(error.data?.message || 'Terjadi kesalahan saat mengirim permohonan.', false);
            }
        });
    });
});

function resetForm() {
    const form = document.getElementById('permohonanForm');
    const jenisIzinLainnya = document.getElementById('jenisIzinLainnya');
    const jenisIzinCustom = document.getElementById('jenisIzinCustom');
    const tanggalMulai = document.getElementById('tanggalMulai');
    const tanggalSelesaiNormal = document.getElementById('tanggalSelesaiNormal');
    
    if (!form || !jenisIzinLainnya || !jenisIzinCustom || !tanggalMulai || !tanggalSelesaiNormal) {
        return;
    }
    
    form.reset();
    
    // Reset default date to today
    const today = new Date().toISOString().split('T')[0];
    tanggalMulai.value = today;
    tanggalSelesaiNormal.value = today;
    
    // Hide custom field and show normal field
    jenisIzinLainnya.style.display = 'none';
    const tanggalSelesaiNormalEl = document.getElementById('tanggalSelesaiNormal');
    if (tanggalSelesaiNormalEl) {
        tanggalSelesaiNormalEl.style.display = 'block';
    }
    jenisIzinCustom.required = false;
}

// Auto-scroll to table when pagination is clicked
document.addEventListener('click', function(e) {
    if (e.target.closest('.pagination a')) {
        // Store the position of the table
        const tableCard = document.querySelector('.card');
        if (tableCard) {
            // Scroll to table after a short delay to allow page load
            setTimeout(function() {
                tableCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
    }
});

// Function to show detail modal
function showDetailModal(id) {
    const modalEl = document.getElementById('detailModal');
    const modalBody = document.getElementById('detailModalBody');
    
    if (!modalEl || !modalBody) return;
    
    const modal = new bootstrap.Modal(modalEl);
    
    // Show modal with loading state
    modal.show();
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    // Fetch leave request details
    fetch(`/student/permohonan-izin/${id}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const request = data.leaveRequest;
        
        // Format dates using Intl.DateTimeFormat for better browser compatibility
        const dateFormatter = new Intl.DateTimeFormat('id-ID', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            weekday: 'long'
        });
        
        const dateFormatterSimple = new Intl.DateTimeFormat('id-ID', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric'
        });
        
        const startDate = dateFormatter.format(new Date(request.start_date));
        const endDate = dateFormatter.format(new Date(request.end_date));
        const submittedDate = dateFormatterSimple.format(new Date(request.created_at));
        
        const leaveTypeDisplay = request.custom_leave_type || getLeaveTypeLabel(request.leave_type);
        const statusLabel = getStatusLabel(request.status);
        const statusBadgeClass = getStatusBadgeClass(request.status);
        
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-12">
                    <h6 class="text-muted border-bottom pb-2">Informasi Permohonan</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="40%"><strong>Jenis Izin:</strong></td>
                            <td>
                                <span class="badge bg-${getLeaveTypeBadge(request.leave_type)}">
                                    ${leaveTypeDisplay}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge bg-${statusBadgeClass}">${statusLabel}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Diproses:</strong></td>
                            <td>${request.processed_by ? `${request.processedBy?.name || 'Admin'} • ${request.processed_at ? dateFormatterSimple.format(new Date(request.processed_at)) : '-'}` : 'Belum diproses'}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Mulai:</strong></td>
                            <td>${startDate}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Selesai:</strong></td>
                            <td>${endDate}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Diajukan:</strong></td>
                            <td>${submittedDate}</td>
                        </tr>
                    </table>
                </div>
                
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-muted border-bottom pb-2">Alasan Izin</h6>
                    <div class="border rounded p-3 bg-light">
                        ${request.reason}
                    </div>
                </div>
            </div>
            ${request.teacher_notes && request.teacher_notes.length > 0 ? `
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-muted border-bottom pb-2">Catatan dari Guru</h6>
                    <div class="list-group">
                        ${request.teacher_notes.map(note => `
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="bx ${note.action === 'approve' ? 'bx-check-circle text-success' : 'bx-x-circle text-danger'}"></i>
                                            ${note.teacher?.full_name || note.teacher?.name || 'Guru'}
                                        </h6>
                                        <p class="mb-1 text-muted small">
                                            <strong>Mata Pelajaran:</strong> ${note.subject?.name || '-'}
                                        </p>
                                        ${note.note ? `
                                            <p class="mb-0 mt-2">${note.note}</p>
                                        ` : `
                                            <p class="mb-0 mt-2 text-muted font-italic">Tidak ada catatan</p>
                                        `}
                                    </div>
                                    <small class="text-muted">${new Date(note.created_at).toLocaleDateString('id-ID')}</small>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
            ` : ''}
            ${request.supporting_document && request.document_url ? `
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-muted border-bottom pb-2">Dokumen Pendukung</h6>
                    <a href="${encodeURI(request.document_url)}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm">
                        <i class="bx bx-file"></i> Lihat Dokumen
                    </a>
                </div>
            </div>
            ` : ''}
        `;
    })
    .catch(error => {
        console.error('Error:', error);
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="bx bx-error"></i> Terjadi kesalahan saat memuat data.
            </div>
        `;
    });
}

// Helper functions
function getLeaveTypeLabel(type) {
    const labels = {
        'sakit': 'Sakit',
        'izin': 'Izin',
        'keperluan-keluarga': 'Keperluan Keluarga',
        'acara-keluarga': 'Acara Keluarga',
        'lainnya': 'Lainnya'
    };
    return labels[type] || type;
}

function getLeaveTypeBadge(type) {
    const badges = {
        'sakit': 'danger',
        'izin': 'secondary',
        'keperluan-keluarga': 'info',
        'acara-keluarga': 'primary',
        'lainnya': 'primary'
    };
    return badges[type] || 'secondary';
}

function getStatusLabel(status) {
    const labels = {
        'pending': 'Menunggu',
        'approved': 'Disetujui',
        'rejected': 'Ditolak'
    };
    return labels[status] || status;
}

function getStatusBadgeClass(status) {
    const classes = {
        'pending': 'warning',
        'approved': 'success',
        'rejected': 'danger'
    };
    return classes[status] || 'secondary';
}

// Expose functions to global scope for onclick handlers
window.resetForm = resetForm;
window.showDetailModal = showDetailModal;

