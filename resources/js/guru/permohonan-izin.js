document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification modal
    const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));

    // Ensure close buttons work
    document.querySelector('#notificationModal .btn-close').addEventListener('click', () => {
        notificationModal.hide();
    });
    document.querySelector('#notificationModal .btn-light').addEventListener('click', () => {
        notificationModal.hide();
    });

    // Make showNotification available globally
    let modalHiddenHandler = null;
    let shouldReload = false; // Flag to control reload
    let isNotificationShowing = false; // Flag to prevent multiple notifications
    
    window.showNotification = function(message, isSuccess = true, reloadAfter = false) {
        // Prevent multiple notifications from showing at the same time
        if (isNotificationShowing) {
            return;
        }
        
        isNotificationShowing = true;
        
        document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
        document.getElementById('notificationMessage').innerText = message;
        
        // Set reload flag
        shouldReload = reloadAfter;
        
        // For error cases, reset button immediately when modal is shown
        if (!isSuccess) {
            const form = document.getElementById('permohonanForm');
            if (form) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && submitBtn.disabled) {
                    const originalText = submitBtn.getAttribute('data-original-text');
                    if (originalText) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    } else {
                        // Fallback if data-original-text is not set
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bx bx-send me-1"></i>Ajukan Permohonan';
                    }
                }
            }
        }
        
        // Remove previous handler if exists
        const modalElement = document.getElementById('notificationModal');
        if (modalHiddenHandler) {
            modalElement.removeEventListener('hidden.bs.modal', modalHiddenHandler);
        }
        
        // Add new handler to ensure button is reset when modal is closed (for error cases that weren't reset)
        modalHiddenHandler = function() {
            // Reset notification flag
            isNotificationShowing = false;
            
            // Find submit button and reset it if still in loading state (fallback for error cases)
            const form = document.getElementById('permohonanForm');
            if (form) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && submitBtn.disabled) {
                    // Only reset if button is still disabled (error case fallback)
                    const originalText = submitBtn.getAttribute('data-original-text');
                    if (originalText) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    } else {
                        // Fallback if data-original-text is not set
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bx bx-send me-1"></i>Ajukan Permohonan';
                    }
                }
            }
            
            // Reload only if success and reloadAfter is true
            if (shouldReload && isSuccess) {
                // Reset submitting flag before reload
                if (typeof isSubmitting !== 'undefined') {
                    isSubmitting = false;
                }
                location.reload();
            }
        };
        modalElement.addEventListener('hidden.bs.modal', modalHiddenHandler, { once: true });
        
        notificationModal.show();
    };

    const form = document.getElementById('permohonanForm');
    const leaveType = document.getElementById('leave_type');
    const customLeaveTypeWrapper = document.getElementById('custom_leave_type_wrapper');
    const customLeaveType = document.getElementById('custom_leave_type');
    const leaveDate = document.getElementById('leave_date');
    const endDate = document.getElementById('end_date');
    const dateRangeInfo = document.getElementById('date_range_info');
    const dateRangeText = document.getElementById('date_range_text');
    
    // Flag to prevent double submission
    let isSubmitting = false;
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    leaveDate.min = today;
    endDate.min = today;
    
    // Update end_date min when leave_date changes
    leaveDate.addEventListener('change', function() {
        if (this.value) {
            endDate.min = this.value;
            if (endDate.value && endDate.value < this.value) {
                endDate.value = '';
            }
            updateDateRangeInfo();
            fetchTimetables();
        }
    });
    
    // Update date range info when end_date changes
    endDate.addEventListener('change', function() {
        updateDateRangeInfo();
        fetchTimetables();
    });
    
    // Function to fetch timetables based on date range
    function fetchTimetables() {
        const startDate = leaveDate.value;
        const endDateValue = endDate.value || startDate;
        
        if (!startDate) {
            document.getElementById('timetables_placeholder').style.display = 'block';
            document.getElementById('timetables_list').style.display = 'none';
            document.getElementById('timetables_loading').style.display = 'none';
            return;
        }
        
        if (endDateValue && endDateValue < startDate) {
            return;
        }
        
        // Show loading
        document.getElementById('timetables_placeholder').style.display = 'none';
        document.getElementById('timetables_list').style.display = 'none';
        document.getElementById('timetables_loading').style.display = 'block';
        
        // Fetch timetables
        fetch(window.permohonanIzinRoutes.getTimetables, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                start_date: startDate,
                end_date: endDateValue || null
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('timetables_loading').style.display = 'none';
            
            if (data.success && data.timetables && data.timetables.length > 0) {
                displayTimetables(data.timetables);
            } else {
                document.getElementById('timetables_list').style.display = 'block';
                document.getElementById('timetables_checkboxes').innerHTML = '';
                document.getElementById('no_timetables').style.display = 'block';
            }
        })
        .catch(error => {
            document.getElementById('timetables_loading').style.display = 'none';
            document.getElementById('timetables_placeholder').style.display = 'block';
            document.getElementById('timetables_placeholder').className = 'alert alert-danger';
            document.getElementById('timetables_placeholder').innerHTML = '<i class="bx bx-error-circle"></i> Terjadi kesalahan saat memuat jadwal mengajar.';
        });
    }
    
    // Function to display timetables
    function displayTimetables(timetables) {
        const checkboxesContainer = document.getElementById('timetables_checkboxes');
        checkboxesContainer.innerHTML = '';
        
        // Group by date first
        const groupedByDate = {};
        timetables.forEach(timetable => {
            if (!groupedByDate[timetable.date]) {
                groupedByDate[timetable.date] = [];
            }
            groupedByDate[timetable.date].push(timetable);
        });
        
        // Display grouped by date
        Object.keys(groupedByDate).sort().forEach(date => {
            const dateTimetables = groupedByDate[date];
            const dateFormatted = formatDate(date);
            
            // Group by class within the same date
            const groupedByClass = {};
            dateTimetables.forEach(timetable => {
                const classKey = timetable.class;
                if (!groupedByClass[classKey]) {
                    groupedByClass[classKey] = [];
                }
                groupedByClass[classKey].push(timetable);
            });
            
            const dateGroup = document.createElement('div');
            dateGroup.className = 'mb-3';
            dateGroup.innerHTML = `<h6 class="mb-2"><strong>${dateFormatted} (${dateTimetables[0].day_name})</strong></h6>`;
            
            // Display grouped by class
            Object.keys(groupedByClass).forEach(classKey => {
                const classTimetables = groupedByClass[classKey];
                
                // Find earliest start time and latest end time
                let earliestStart = classTimetables[0].start_time;
                let latestEnd = classTimetables[0].end_time;
                
                classTimetables.forEach(timetable => {
                    if (timetable.start_time < earliestStart) {
                        earliestStart = timetable.start_time;
                    }
                    if (timetable.end_time > latestEnd) {
                        latestEnd = timetable.end_time;
                    }
                });
                
                // Get unique subjects
                const subjects = [...new Set(classTimetables.map(t => t.subject))];
                const subjectText = subjects.length > 1 ? subjects.join(', ') : subjects[0];
                
                // Build label text
                let labelText = `${subjectText} - ${classKey} (${earliestStart} - ${latestEnd})`;
                
                // Add group type if all have the same group type
                const groupTypes = [...new Set(classTimetables.map(t => t.group_type).filter(Boolean))];
                if (groupTypes.length === 1) {
                    labelText += ` - Kelompok ${groupTypes[0]}`;
                } else if (groupTypes.length > 1) {
                    labelText += ` - Kelompok ${groupTypes.join(', ')}`;
                }
                
                // Add location type if all have the same location type
                const locationTypes = [...new Set(classTimetables.map(t => t.location_type).filter(Boolean))];
                if (locationTypes.length === 1) {
                    labelText += ` - ${locationTypes[0] === 'lab' ? 'Lab' : 'Teori'}`;
                } else if (locationTypes.length > 1) {
                    const locationText = locationTypes.map(lt => lt === 'lab' ? 'Lab' : 'Teori').join(', ');
                    labelText += ` - ${locationText}`;
                }
                
                // Add week alternation if all have the same
                const weekAlternations = [...new Set(classTimetables.map(t => t.week_alternation).filter(Boolean))];
                if (weekAlternations.length === 1) {
                    labelText += ` - Minggu ${weekAlternations[0] === 'ganjil' ? 'Ganjil' : 'Genap'}`;
                }
                
                // Create checkbox with all timetable_ids
                const checkboxDiv = document.createElement('div');
                checkboxDiv.className = 'form-check mb-2';
                
                // Create value with all timetable_ids (comma separated)
                const valueIds = classTimetables.map(t => `${t.id}_${t.date}`).join(',');
                const safeId = `class_${classKey.replace(/[^a-zA-Z0-9]/g, '_')}_${date.replace(/[^a-zA-Z0-9]/g, '_')}`;
                
                checkboxDiv.innerHTML = `
                    <input class="form-check-input" type="checkbox" 
                           name="timetable_ids[]" 
                           id="timetable_${safeId}" 
                           value="${valueIds}" 
                           data-timetable-ids="${valueIds}"
                           required>
                    <label class="form-check-label" for="timetable_${safeId}">
                        ${labelText}
                    </label>
                `;
                
                dateGroup.appendChild(checkboxDiv);
            });
            
            checkboxesContainer.appendChild(dateGroup);
        });
        
        document.getElementById('timetables_list').style.display = 'block';
        document.getElementById('no_timetables').style.display = 'none';
    }
    
    // Function to update date range info
    function updateDateRangeInfo() {
        const startDate = leaveDate.value;
        const endDateValue = endDate.value;
        
        if (startDate && endDateValue) {
            if (endDateValue < startDate) {
                dateRangeInfo.className = 'alert alert-danger';
                dateRangeText.textContent = 'Tanggal akhir harus lebih besar atau sama dengan tanggal mulai';
                dateRangeInfo.style.display = 'block';
            } else {
                const start = new Date(startDate);
                const end = new Date(endDateValue);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                dateRangeInfo.className = 'alert alert-info';
                dateRangeText.textContent = `Izin selama ${diffDays} hari (${formatDate(startDate)} - ${formatDate(endDateValue)})`;
                dateRangeInfo.style.display = 'block';
            }
        } else if (startDate) {
            dateRangeInfo.className = 'alert alert-info';
            dateRangeText.textContent = `Izin 1 hari pada ${formatDate(startDate)}`;
            dateRangeInfo.style.display = 'block';
        } else {
            dateRangeInfo.style.display = 'none';
        }
    }
    
    // Function to format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate();
        const month = date.toLocaleString('id-ID', { month: 'long' });
        const year = date.getFullYear();
        return `${day} ${month} ${year}`;
    }
    
    // Show/hide custom leave type field
    leaveType.addEventListener('change', function() {
        if (this.value === 'lainnya') {
            customLeaveTypeWrapper.style.display = 'block';
            customLeaveType.required = true;
        } else {
            customLeaveTypeWrapper.style.display = 'none';
            customLeaveType.required = false;
            customLeaveType.value = '';
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Prevent double submission
        if (isSubmitting) {
            return;
        }
        
        // Validate custom leave type if "lainnya" is selected
        if (leaveType.value === 'lainnya' && !customLeaveType.value.trim()) {
            alert('Harap isi jenis izin lainnya.');
            return;
        }
        
        // Validate end_date if provided
        if (endDate.value && endDate.value < leaveDate.value) {
            alert('Tanggal akhir izin harus lebih besar atau sama dengan tanggal mulai izin.');
            return;
        }
        
        // Validate at least one timetable is selected
        const selectedTimetables = form.querySelectorAll('input[name="timetable_ids[]"]:checked');
        if (selectedTimetables.length === 0) {
            alert('Harap pilih minimal satu jadwal mengajar.');
            return;
        }
        
        // Validate file size (500KB = 512000 bytes)
        const fileInput = document.getElementById('dokumenPendukung');
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 512000; // 500KB in bytes
            if (file.size > maxSize) {
                alert('Ukuran file dokumen pendukung maksimal 500KB.');
                return;
            }
        }
        
        // Set submitting flag
        isSubmitting = true;
        
        // Process timetable_ids - split comma-separated values
        const allTimetableIds = [];
        selectedTimetables.forEach(checkbox => {
            const value = checkbox.value;
            // Split by comma if multiple
            if (value.includes(',')) {
                const ids = value.split(',');
                ids.forEach(id => {
                    if (id.trim()) {
                        allTimetableIds.push(id.trim());
                    }
                });
            } else {
                allTimetableIds.push(value);
            }
        });
        
        const formData = new FormData(this);
        
        // Remove old timetable_ids and add processed ones
        formData.delete('timetable_ids[]');
        allTimetableIds.forEach(id => {
            formData.append('timetable_ids[]', id);
        });
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Store original text as data attribute for recovery
        submitBtn.setAttribute('data-original-text', originalText);
        
        // Disable button to prevent double submission (no loading spinner)
        submitBtn.disabled = true;
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            let data;
            try {
                data = await response.json();
            } catch (e) {
                // If response is not JSON, treat as error
                throw { status: response.status, message: 'Invalid response format' };
            }
            
            // Check if data has success property first (regardless of response status)
            // This handles cases where data is saved but response status is not 200
            const isSuccess = data.success === true || data.success === 'true';
            
            if (isSuccess) {
                // Data successfully saved - reset button immediately
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                const successMessage = data.message || 'Permohonan izin berhasil diajukan!';
                showNotification(successMessage, true, true); // Pass true to reload after modal closes
                // Don't reset isSubmitting here - let reload handle it
                return; // Exit early to prevent further processing
            }
            
            // If not success, check response status
            if (response.ok) {
                // Response OK but success is false or not present
                let errorMessage = data.error || data.message || 'Terjadi kesalahan saat mengajukan permohonan izin.';
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMessage = errors.join('\n');
                }
                // Reset submitting flag for error case
                isSubmitting = false;
                // Reset button immediately for error case
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                // Show notification
                showNotification(errorMessage, false);
            } else {
                // Response not OK (status 400+)
                let errorMessage = data.error || data.message || 'Terjadi kesalahan saat mengajukan permohonan izin.';
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMessage = errors.join('\n');
                }
                // Reset submitting flag for error case
                isSubmitting = false;
                // Reset button immediately for error case
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                // Show notification
                showNotification(errorMessage, false);
            }
        })
        .catch(error => {
            // Reset submitting flag on error
            isSubmitting = false;
            
            // Reset button immediately for error case
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            console.error('Error:', error);
            let errorMessage = 'Terjadi kesalahan saat mengajukan permohonan izin.';
            if (error.data) {
                if (error.data.error) {
                errorMessage = error.data.error;
                } else if (error.data.errors) {
                const errors = Object.values(error.data.errors).flat();
                errorMessage = errors.join('\n');
                } else if (error.data.message) {
                    errorMessage = error.data.message;
                }
            } else if (error.message) {
                errorMessage = error.message;
            }
            showNotification(errorMessage, false);
        })
        .finally(() => {
            // Button is already reset in error handlers above
            // This block is kept for any additional cleanup if needed
        });
    });
});

// Reset form
function resetForm() {
    document.getElementById('permohonanForm').reset();
    document.getElementById('custom_leave_type_wrapper').style.display = 'none';
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('leave_date').min = today;
}

// Show detail modal
function showDetailModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    const modalBody = document.getElementById('detailModalBody');
    
    modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data...</p>
        </div>
    `;
    
    modal.show();
    
    const showUrl = window.permohonanIzinRoutes.show.replace(':id', id);
    fetch(showUrl)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const request = data.data;
                const days = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                const leaveDate = new Date(request.leave_date);
                let formattedDate = leaveDate.toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
                
                if (request.end_date && request.end_date !== request.leave_date) {
                    const endDate = new Date(request.end_date);
                    const endDateFormatted = endDate.toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
                    const diffDays = Math.ceil((endDate - leaveDate) / (1000 * 60 * 60 * 24)) + 1;
                    formattedDate = `${formattedDate} - ${endDateFormatted} (${diffDays} hari)`;
                }
                
                // Build jadwal HTML
                let jadwalHTML = '';
                if (request.timetables && request.timetables.length > 0) {
                    // Use grouped timetables - display in 4 columns
                    jadwalHTML = '<div class="row">';
                    request.timetables.forEach(timetable => {
                        const dayName = days[timetable.day_of_week] || `Hari ${timetable.day_of_week}`;
                        const subjects = timetable.subjects.join(', ');
                        const startTime = new Date('2000-01-01T' + timetable.start_time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
                        const endTime = new Date('2000-01-01T' + timetable.end_time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
                        
                        jadwalHTML += `
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="p-3 border rounded h-100">
                                    <strong>${dayName}</strong><br>
                                    <strong>${timetable.class_name}</strong><br>
                                    <small class="text-muted">${subjects}</small><br>
                                    <small class="text-muted">${startTime} - ${endTime}</small>
                                </div>
                            </div>
                        `;
                    });
                    jadwalHTML += '</div>';
                } else if (request.timetable) {
                    // Fallback to single timetable (backward compatibility)
                const dayName = days[request.timetable.day_of_week] || request.timetable.day_of_week;
                    const startTime = new Date('2000-01-01T' + request.timetable.start_time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
                    const endTime = new Date('2000-01-01T' + request.timetable.end_time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
                    
                    jadwalHTML = `
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="p-3 border rounded h-100">
                                    <strong>${dayName}</strong><br>
                                    <strong>${request.timetable.class_subject.class.name}</strong><br>
                                    <small class="text-muted">${request.timetable.class_subject.subject.name}</small><br>
                                    <small class="text-muted">${startTime} - ${endTime}</small>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Tanggal Izin:</strong><br>${formattedDate}</p>
                        </div>
                        <div class="col-12">
                            <p><strong>Jadwal:</strong></p>
                            ${jadwalHTML || '<p class="text-muted">Tidak ada jadwal</p>'}
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jenis Izin:</strong><br>
                            <span class="badge bg-${request.leave_type === 'sakit' ? 'danger' : (request.leave_type === 'izin' ? 'secondary' : (request.leave_type === 'keperluan-keluarga' ? 'info' : 'primary'))}">
                                ${request.leave_type_display || request.leave_type}
                            </span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong><br>
                            <span class="badge bg-${request.status === 'approved' ? 'success' : (request.status === 'rejected' ? 'danger' : 'warning')}">
                                ${request.status === 'pending' ? 'Menunggu' : (request.status === 'approved' ? 'Disetujui' : 'Ditolak')}
                            </span></p>
                        </div>
                        <div class="col-12">
                            <p><strong>Alasan:</strong><br>${request.reason || '-'}</p>
                        </div>
                        ${request.substitute ? `
                        <div class="col-12">
                            <p><strong>Pengganti:</strong><br>${request.substitute.full_name}</p>
                        </div>
                        ` : ''}
                        ${request.admin_notes ? `
                        <div class="col-12">
                            <p><strong>Catatan Admin:</strong><br>${request.admin_notes}</p>
                        </div>
                        ` : ''}
                        ${request.supporting_document ? `
                        <div class="col-12">
                            <p><strong>Dokumen Pendukung:</strong><br>
                            <a href="${request.document_url}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-file"></i> Lihat Dokumen
                            </a></p>
                        </div>
                        ` : ''}
                        <div class="col-12">
                            <p><strong>Tanggal Ajukan:</strong><br>${new Date(request.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'})}</p>
                        </div>
                    </div>
                `;
            } else {
                modalBody.innerHTML = '<p class="text-danger">Gagal memuat data.</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = '<p class="text-danger">Terjadi kesalahan saat memuat data.</p>';
        });
}

// Expose functions to global scope for inline onclick handlers
window.resetForm = resetForm;
window.showDetailModal = showDetailModal;

