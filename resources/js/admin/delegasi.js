// Admin Delegasi JavaScript
// This file handles all delegation functionality including CRUD operations and leave request management
(function () {
    "use strict";

    // Inisialisasi modal
    var delegasiModal;
    var delegasiEditingId = null;

    // Store all timetables data - ensure it's an array
    const allTimetables = window.allTimetables || [];

    // Initialize notification modal listener
    document.addEventListener("DOMContentLoaded", function () {
        // Check URL hash and open corresponding tab
        function activateTabFromHash() {
            if (window.location.hash === '#permohonan-izin') {
                const permohonanTab = document.getElementById('permohonan-izin-tab');
                if (permohonanTab) {
                    // Remove active class from all tabs
                    document.querySelectorAll('.nav-link').forEach(tab => {
                        tab.classList.remove('active');
                        tab.setAttribute('aria-selected', 'false');
                    });
                    document.querySelectorAll('.tab-pane').forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });
                    
                    // Activate permohonan izin tab
                    permohonanTab.classList.add('active');
                    permohonanTab.setAttribute('aria-selected', 'true');
                    const permohonanPane = document.getElementById('permohonan-izin');
                    if (permohonanPane) {
                        permohonanPane.classList.add('show', 'active');
                    }
                }
            }
        }
        
        // Activate tab on page load
        activateTabFromHash();
        
        // Also listen for hash changes (in case user navigates with browser back/forward)
        window.addEventListener('hashchange', activateTabFromHash);
        
        // Inisialisasi modal
        var modalElement = document.getElementById('delegasiModal');
        if (modalElement) {
            delegasiModal = new bootstrap.Modal(modalElement);
        }
        
        // Setup notification modal
        const notificationModal = document.getElementById('notificationModal');
        if (notificationModal) {
            // Ensure close buttons work
            const closeBtn = notificationModal.querySelector('.btn-close');
            const closeLightBtn = notificationModal.querySelector('.btn-light');
            
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    const modalInstance = bootstrap.Modal.getInstance(notificationModal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                });
            }
            
            if (closeLightBtn) {
                closeLightBtn.addEventListener('click', function() {
                    const modalInstance = bootstrap.Modal.getInstance(notificationModal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                });
            }
        }
        
        // Show valid_until field when type is temporary
        var typeSelect = document.getElementById('type');
        if (typeSelect) {
            typeSelect.addEventListener('change', function() {
                var wrapper = document.getElementById('validUntilWrapper');
                var validUntilInput = document.getElementById('valid_until');
                
                if (this.value === 'temporary') {
                    wrapper.style.display = 'block';
                    validUntilInput.setAttribute('required', 'required');
                } else {
                    wrapper.style.display = 'none';
                    validUntilInput.removeAttribute('required');
                }
            });
        }
        
        // Filter schedules based on subject, class, and teacher
        document.getElementById('subject_id').addEventListener('change', filterSchedules);
        document.getElementById('class_id').addEventListener('change', filterSchedules);
        
        // Teacher email validation and schedule filtering
        var teacherEmailInput = document.getElementById('teacher_email');
        if (teacherEmailInput) {
            teacherEmailInput.addEventListener('blur', function() {
                const email = this.value;
                const validationMsg = document.getElementById('teacher_email_validation_message');
                
                if (!email) {
                    validationMsg.innerHTML = '';
                    document.getElementById('teacher_id').value = '';
                    filterSchedules();
                    return;
                }
                
                // Call API to check if email exists
                fetch('/admin/delegasi/check-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        validationMsg.innerHTML = `<span class="text-success"><i class="bx bx-check-circle"></i> ${data.message}</span>`;
                        document.getElementById('teacher_id').value = data.user_id;
                        filterSchedules(); // Refresh schedules when teacher is validated
                    } else {
                        validationMsg.innerHTML = `<span class="text-danger"><i class="bx bx-x-circle"></i> ${data.message}</span>`;
                        document.getElementById('teacher_id').value = '';
                    }
                })
                .catch(error => {
                    validationMsg.innerHTML = '<span class="text-warning">Terjadi kesalahan saat validasi</span>';
                });
            });
        }
        
        // When schedule is selected, show info and update date input constraints
        var scheduleSelect = document.getElementById('schedule_id');
        if (scheduleSelect) {
            scheduleSelect.addEventListener('change', function() {
                if (this.value) {
                    const schedule = allTimetables.find(t => t.id == this.value);
                    if (schedule) {
                        const info = `${schedule.day_of_week} - ${schedule.start_time} - ${schedule.end_time}`;
                        document.getElementById('schedule_info').innerHTML = '<strong>Jadwal:</strong> ' + info;
                    }
                }
            });
        }
        
        // Email validation
        var delegatedEmailInput = document.getElementById('delegated_to_email');
        if (delegatedEmailInput) {
            delegatedEmailInput.addEventListener('blur', function() {
                const email = this.value;
                const validationMsg = document.getElementById('email_validation_message');
                
                if (!email) {
                    validationMsg.innerHTML = '';
                    return;
                }
                
                // Call API to check if email exists
                fetch('/admin/delegasi/check-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        validationMsg.innerHTML = `<span class="text-success"><i class="bx bx-check-circle"></i> ${data.message}</span>`;
                        document.getElementById('delegated_to_user_id').value = data.user_id;
                    } else {
                        validationMsg.innerHTML = `<span class="text-danger"><i class="bx bx-x-circle"></i> ${data.message}</span>`;
                        document.getElementById('delegated_to_user_id').value = '';
                    }
                })
                .catch(error => {
                    validationMsg.innerHTML = '<span class="text-warning">Terjadi kesalahan saat validasi</span>';
                });
            });
        }
    });

    function filterSchedules() {
        const subjectId = document.getElementById('subject_id').value;
        const classId = document.getElementById('class_id').value;
        const teacherId = document.getElementById('teacher_id').value;
        
        const scheduleWrapper = document.getElementById('schedule_wrapper');
        const scheduleSelect = document.getElementById('schedule_id');
        
        // Reset
        scheduleSelect.innerHTML = '<option value="">Pilih Jadwal</option>';
        scheduleWrapper.style.display = 'none';
        
        // If all filters are selected
        if (subjectId && classId && teacherId) {
            // Filter timetables
            const filtered = allTimetables.filter(t => {
                return t.class_subject && 
                    t.class_subject.subject_id == subjectId &&
                    t.class_subject.class_id == classId &&
                    t.class_subject.teacher && 
                    t.class_subject.teacher.user_id == teacherId;
            });
            
            if (filtered.length > 0) {
                scheduleWrapper.style.display = 'block';
                
                // Process grouped schedules - group by class_subject_id and day_of_week (like attendance system)
                const groupedByClassSubject = {};
                filtered.forEach(t => {
                    const classSubjectId = t.class_subject ? t.class_subject.id : null;
                    const dayOfWeek = t.day_of_week;
                    const key = `${classSubjectId}_${dayOfWeek}`;
                    
                    if (!groupedByClassSubject[key]) {
                        groupedByClassSubject[key] = {
                            class_subject_id: classSubjectId,
                            day_of_week: dayOfWeek,
                            timetables: []
                        };
                    }
                    groupedByClassSubject[key].timetables.push(t);
                });
                
                // Process each class subject group
                Object.keys(groupedByClassSubject).forEach(key => {
                    const group = groupedByClassSubject[key];
                    const timetables = group.timetables;
                    
                    // Sort by start_time
                    timetables.sort((a, b) => {
                        if (a.start_time < b.start_time) return -1;
                        if (a.start_time > b.start_time) return 1;
                        return 0;
                    });
                    
                    // Merge consecutive times (like attendance system)
                    let mergedTimes = [];
                    let currentStart = null;
                    let currentEnd = null;
                    
                    timetables.forEach(t => {
                        if (currentStart === null) {
                            currentStart = t.start_time;
                            currentEnd = t.end_time;
                        } else if (t.start_time === currentEnd) {
                            // Consecutive, merge
                            currentEnd = t.end_time;
                        } else {
                            // Not consecutive, save current and start new
                            mergedTimes.push({ start: currentStart, end: currentEnd });
                            currentStart = t.start_time;
                            currentEnd = t.end_time;
                        }
                    });
                    if (currentStart !== null) {
                        mergedTimes.push({ start: currentStart, end: currentEnd });
                    }
                    
                    // Create option for each merged time (or single option if all merged)
                    const dayNames = {1: 'Senin', 2: 'Selasa', 3: 'Rabu', 4: 'Kamis', 5: 'Jumat', 6: 'Sabtu', 7: 'Minggu'};
                    const dayName = dayNames[group.day_of_week] || group.day_of_week;
                    
                    // Use first timetable ID as reference, but send class_subject_id and day_of_week
                    const firstTimetable = timetables[0];
                    
                    // Format time range
                    const earliestStart = mergedTimes[0].start;
                    const latestEnd = mergedTimes[mergedTimes.length - 1].end;
                    const timeRange = `${earliestStart.substring(0, 5)} - ${latestEnd.substring(0, 5)}`;
                    
                    // Create option with class_subject_id and day_of_week as value
                    const option = document.createElement('option');
                    // Format: class_subject_id|day_of_week (we'll parse this in backend)
                    option.value = `${group.class_subject_id}|${group.day_of_week}`;
                    option.text = `${dayName} - ${timeRange}`;
                    option.setAttribute('data-class-subject-id', group.class_subject_id);
                    option.setAttribute('data-day-of-week', group.day_of_week);
                    scheduleSelect.appendChild(option);
                });
            } else {
                if (subjectId && classId && teacherId) {
                    // Only show alert if all filters are filled
                    document.getElementById('schedule_info').innerHTML = '<span class="text-danger">Tidak ada jadwal yang cocok dengan kriteria yang dipilih.</span>';
                }
            }
    }

    // Global function untuk buka modal (dipanggil dari onclick)
    function bukaModalTambahDelegasi() {
        delegasiEditingId = null;
        
        var modalTitle = document.getElementById('delegasiModalTitle');
        var delegasiForm = document.getElementById('delegasiForm');
        var delegasiId = document.getElementById('delegasi_id');
        var validUntilWrapper = document.getElementById('validUntilWrapper');
        var scheduleWrapper = document.getElementById('schedule_wrapper');
        var multipleScheduleWrapper = document.getElementById('multiple_schedule_wrapper');
        var singleDelegationWrapper = document.getElementById('single_delegation_wrapper');
        var leaveRequestId = document.getElementById('leave_request_id');
        
        if (modalTitle) modalTitle.textContent = 'Tambah Delegasi';
        if (delegasiForm) delegasiForm.reset();
        if (delegasiId) delegasiId.value = '';
        if (leaveRequestId) leaveRequestId.value = '';
        if (validUntilWrapper) validUntilWrapper.style.display = 'none';
        if (scheduleWrapper) scheduleWrapper.style.display = 'none';
        
        // Hide multiple schedule wrapper, show single delegation wrapper
        const delegationDateWrapper = document.getElementById('delegation_date_wrapper');
        const delegatedToEmailWrapper = document.getElementById('delegated_to_email_wrapper');
        if (multipleScheduleWrapper) multipleScheduleWrapper.style.display = 'none';
        if (singleDelegationWrapper) singleDelegationWrapper.style.display = 'block';
        if (delegationDateWrapper) delegationDateWrapper.style.display = 'block';
        if (delegatedToEmailWrapper) delegatedToEmailWrapper.style.display = 'block';
        
        // Reset all email fields
        var delegatedEmailField = document.getElementById('delegated_to_email');
        var teacherEmailField = document.getElementById('teacher_email');
        var emailValidationMsg = document.getElementById('email_validation_message');
        var teacherEmailValidationMsg = document.getElementById('teacher_email_validation_message');
        
        if (delegatedEmailField) delegatedEmailField.value = '';
        if (teacherEmailField) teacherEmailField.value = '';
        if (emailValidationMsg) emailValidationMsg.innerHTML = '';
        if (teacherEmailValidationMsg) teacherEmailValidationMsg.innerHTML = '';
        
        if (delegasiModal) {
            delegasiModal.show();
        } else {
            // Fallback: coba init modal lagi
            var modalElement = document.getElementById('delegasiModal');
            if (modalElement) {
                delegasiModal = new bootstrap.Modal(modalElement);
                delegasiModal.show();
            }
        }
    }

    // Function to open delegation modal from leave request with pre-filled data
    function bukaModalDelegasiDariPermohonanIzin(leaveRequest, leaveRequestId) {
        // Set leave request ID
        const leaveRequestIdField = document.getElementById('leave_request_id');
        if (leaveRequestIdField) {
            leaveRequestIdField.value = leaveRequestId;
        }
        
        // Hide single delegation wrapper, show multiple schedule wrapper
        const multipleScheduleWrapper = document.getElementById('multiple_schedule_wrapper');
        const singleDelegationWrapper = document.getElementById('single_delegation_wrapper');
        const delegationDateWrapper = document.getElementById('delegation_date_wrapper');
        const delegatedToEmailWrapper = document.getElementById('delegated_to_email_wrapper');
        if (multipleScheduleWrapper) multipleScheduleWrapper.style.display = 'block';
        if (singleDelegationWrapper) singleDelegationWrapper.style.display = 'none';
        if (delegationDateWrapper) delegationDateWrapper.style.display = 'none';
        if (delegatedToEmailWrapper) delegatedToEmailWrapper.style.display = 'none';
        
        // Set modal title
        const modalTitle = document.getElementById('delegasiModalTitle');
        if (modalTitle) {
            modalTitle.textContent = 'Tambah Delegasi - Setujui Permohonan Izin';
        }
        
        // Get timetables from leave request
        let timetables = [];
        if (leaveRequest.timetables && leaveRequest.timetables.length > 0) {
            timetables = leaveRequest.timetables;
        } else if (leaveRequest.timetable) {
            timetables = [leaveRequest.timetable];
        }
        
        // Group timetables by day_of_week and class_name (consolidate duplicates)
        const groupedSchedules = {};
        timetables.forEach(timetable => {
            const t = timetable.timetable || timetable;
            if (!t || !t.class_subject) return;
            
            const dayOfWeek = t.day_of_week;
            const className = t.class_subject.class ? t.class_subject.class.name : 'N/A';
            const subjectName = t.class_subject.subject ? t.class_subject.subject.name : 'N/A';
            const classSubjectId = t.class_subject.id || t.class_subject_id;
            
            // Create unique key based on day_of_week and class_name
            const key = `${dayOfWeek}_${className}_${classSubjectId}`;
            
            if (!groupedSchedules[key]) {
                groupedSchedules[key] = {
                    day_of_week: dayOfWeek,
                    class_name: className,
                    subject_name: subjectName,
                    class_subject_id: classSubjectId,
                    timetable_ids: [],
                    start_times: [],
                    end_times: [],
                    dates: []
                };
            }
            
            // Collect timetable IDs
            const timetableId = t.id || timetable.id;
            if (timetableId && !groupedSchedules[key].timetable_ids.includes(timetableId)) {
                groupedSchedules[key].timetable_ids.push(timetableId);
            }
            
            // Collect times
            if (t.start_time) groupedSchedules[key].start_times.push(t.start_time);
            if (t.end_time) groupedSchedules[key].end_times.push(t.end_time);
            
            // Collect dates
            if (timetable.date) {
                const dateStr = timetable.date;
                if (!groupedSchedules[key].dates.includes(dateStr)) {
                    groupedSchedules[key].dates.push(dateStr);
                }
            } else if (leaveRequest.leave_date) {
                const dateStr = new Date(leaveRequest.leave_date).toISOString().split('T')[0];
                if (!groupedSchedules[key].dates.includes(dateStr)) {
                    groupedSchedules[key].dates.push(dateStr);
                }
            }
        });
        
        // Build HTML for schedule list
        const scheduleListContainer = document.getElementById('schedule_list_container');
        if (!scheduleListContainer) return;
        
        const dayNames = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        let html = '';
        Object.keys(groupedSchedules).forEach((key, index) => {
            const schedule = groupedSchedules[key];
            const dayName = dayNames[schedule.day_of_week] || 'N/A';
            
            // Find earliest start and latest end time
            let earliestStart = null;
            let latestEnd = null;
            
            schedule.start_times.forEach(time => {
                if (time) {
                    const timeObj = new Date('2000-01-01 ' + time);
                    if (!earliestStart || timeObj < earliestStart) {
                        earliestStart = timeObj;
                    }
                }
            });
            
            schedule.end_times.forEach(time => {
                if (time) {
                    const timeObj = new Date('2000-01-01 ' + time);
                    if (!latestEnd || timeObj > latestEnd) {
                        latestEnd = timeObj;
                    }
                }
            });
            
            const timeRange = earliestStart && latestEnd 
                ? `${earliestStart.toTimeString().substring(0, 5)} - ${latestEnd.toTimeString().substring(0, 5)}`
                : 'N/A';
            
            const datesStr = schedule.dates.length > 0 
                ? schedule.dates.map(d => new Date(d).toLocaleDateString('id-ID')).join(', ')
                : 'N/A';
            
            const timetableIdsJson = JSON.stringify(schedule.timetable_ids);
            
            html += `
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input schedule-checkbox" type="checkbox" 
                                   id="schedule_checkbox_${index}" 
                                   data-timetable-ids='${timetableIdsJson}'
                                   data-class-subject-id="${schedule.class_subject_id}"
                                   data-day-of-week="${schedule.day_of_week}">
                            <label class="form-check-label fw-bold" for="schedule_checkbox_${index}">
                                ${dayName} - ${schedule.subject_name} - ${schedule.class_name}
                            </label>
                        </div>
                        <div class="ms-4">
                            <p class="mb-2"><strong>Waktu:</strong> ${timeRange}</p>
                            <p class="mb-2"><strong>Tanggal:</strong> ${datesStr}</p>
                            <div class="mb-2">
                                <label class="form-label small">Email Pengganti untuk jadwal ini:</label>
                                <input type="email" class="form-control form-control-sm schedule-delegate-email" 
                                       id="schedule_email_${index}" 
                                       placeholder="contoh@email.com"
                                       disabled>
                                <div class="schedule-email-validation" id="schedule_email_validation_${index}"></div>
                                <input type="hidden" class="schedule-delegate-user-id" id="schedule_user_id_${index}" value="">
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        if (html === '') {
            html = '<p class="text-muted text-center">Tidak ada jadwal yang ditemukan</p>';
        }
        
        scheduleListContainer.innerHTML = html;
        
        // Setup checkbox change handlers
        document.querySelectorAll('.schedule-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const emailInput = document.getElementById(this.id.replace('schedule_checkbox_', 'schedule_email_'));
                if (emailInput) {
                    emailInput.disabled = !this.checked;
                    if (!this.checked) {
                        emailInput.value = '';
                        const validationDiv = document.getElementById(this.id.replace('schedule_checkbox_', 'schedule_email_validation_'));
                        if (validationDiv) validationDiv.innerHTML = '';
                        const userIdInput = document.getElementById(this.id.replace('schedule_checkbox_', 'schedule_user_id_'));
                        if (userIdInput) userIdInput.value = '';
                    }
                }
            });
        });
        
        // Setup email validation for each schedule email input
        document.querySelectorAll('.schedule-delegate-email').forEach(emailInput => {
            emailInput.addEventListener('blur', function() {
                const email = this.value;
                const index = this.id.replace('schedule_email_', '');
                const validationDiv = document.getElementById('schedule_email_validation_' + index);
                const userIdInput = document.getElementById('schedule_user_id_' + index);
                
                if (!email) {
                    if (validationDiv) validationDiv.innerHTML = '';
                    if (userIdInput) userIdInput.value = '';
                    return;
                }
                
                fetch('/admin/delegasi/check-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        if (validationDiv) {
                            validationDiv.innerHTML = `<span class="text-success small"><i class="bx bx-check-circle"></i> ${data.message}</span>`;
                        }
                        if (userIdInput) userIdInput.value = data.user_id;
                    } else {
                        if (validationDiv) {
                            validationDiv.innerHTML = `<span class="text-danger small"><i class="bx bx-x-circle"></i> ${data.message}</span>`;
                        }
                        if (userIdInput) userIdInput.value = '';
                    }
                })
                .catch(error => {
                    if (validationDiv) {
                        validationDiv.innerHTML = '<span class="text-warning small">Terjadi kesalahan saat validasi</span>';
                    }
                });
            });
        });
        
        // Open modal
        if (delegasiModal) {
            delegasiModal.show();
        } else {
            const modalElement = document.getElementById('delegasiModal');
            if (modalElement) {
                delegasiModal = new bootstrap.Modal(modalElement);
                delegasiModal.show();
            }
        }
    }

    // Global functions
    function simpanDelegasi() {
        // Check if we're in multiple schedule mode (from leave request)
        const multipleScheduleWrapper = document.getElementById('multiple_schedule_wrapper');
        const isMultipleScheduleMode = multipleScheduleWrapper && multipleScheduleWrapper.style.display !== 'none';
        
        if (isMultipleScheduleMode) {
            // Multiple schedule mode - collect checked schedules
            const checkedSchedules = [];
            document.querySelectorAll('.schedule-checkbox:checked').forEach(checkbox => {
                const timetableIds = JSON.parse(checkbox.getAttribute('data-timetable-ids') || '[]');
                const classSubjectId = checkbox.getAttribute('data-class-subject-id');
                const dayOfWeek = checkbox.getAttribute('data-day-of-week');
                const index = checkbox.id.replace('schedule_checkbox_', '');
                const emailInput = document.getElementById('schedule_email_' + index);
                const userIdInput = document.getElementById('schedule_user_id_' + index);
                
                if (!emailInput || !userIdInput || !userIdInput.value) {
                    alert('Pastikan semua jadwal yang dicentang sudah memiliki email pengganti yang valid.');
                    return;
                }
                
                checkedSchedules.push({
                    timetable_ids: timetableIds,
                    class_subject_id: classSubjectId,
                    day_of_week: dayOfWeek,
                    delegated_to_user_id: userIdInput.value
                });
            });
            
            if (checkedSchedules.length === 0) {
                alert('Silakan pilih minimal satu jadwal yang akan didelegasikan.');
                return;
            }
            
            // Get common fields
            const type = document.getElementById('type').value;
            const adminNotes = document.getElementById('admin_notes').value;
            const validUntil = document.getElementById('valid_until').value;
            const leaveRequestId = document.getElementById('leave_request_id').value;
            
            if (!type) {
                alert('Silakan pilih tipe delegasi.');
                return;
            }
            
            // Build schedule delegations array
            const scheduleDelegations = checkedSchedules.map(schedule => ({
                timetable_ids: schedule.timetable_ids,
                class_subject_id: schedule.class_subject_id,
                day_of_week: schedule.day_of_week,
                delegated_to_user_id: schedule.delegated_to_user_id,
                type: type,
                valid_until: type === 'temporary' ? validUntil : null,
                admin_notes: adminNotes
            }));
            
            // Submit multiple delegations
            const formData = new FormData();
            formData.append('schedule_delegations', JSON.stringify(scheduleDelegations));
            formData.append('type', type);
            if (validUntil && type === 'temporary') {
                formData.append('valid_until', validUntil);
            }
            if (adminNotes) {
                formData.append('admin_notes', adminNotes);
            }
            if (leaveRequestId) {
                formData.append('leave_request_id', leaveRequestId);
            }
            
            fetch('/admin/delegasi', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Close add delegation modal
                if (delegasiModal) {
                    delegasiModal.hide();
                }
                
                if (data.success) {
                    showNotification(data.message, true);
                    setTimeout(() => {
                        // Reload with hash to stay on permohonan izin tab
                        window.location.hash = '#permohonan-izin';
                        window.location.reload();
                    }, 1500);
                } else {
                    showNotification(data.message || 'Terjadi kesalahan', false);
                }
            })
            .catch(error => {
                // Close add delegation modal even on error
                if (delegasiModal) {
                    delegasiModal.hide();
                }
                
                showNotification('Terjadi kesalahan saat menyimpan: ' + error.message, false);
            });
            
            return;
        }
        
        // Single delegation mode (original logic)
        // Validate teacher email
        const teacherId = document.getElementById('teacher_id').value;
        if (!teacherId) {
            alert('Email guru tidak valid. Pastikan email terdaftar di sistem.');
            return;
        }
        
        // Validate delegation email
        const delegatedUserId = document.getElementById('delegated_to_user_id').value;
        if (!delegatedUserId) {
            alert('Email delegasi tidak valid. Pastikan email terdaftar di sistem.');
            return;
        }
        
        // Validate schedule
        const scheduleValue = document.getElementById('schedule_id').value;
        if (!scheduleValue) {
            alert('Silakan pilih jadwal terlebih dahulu.');
            return;
        }
        
        // Parse class_subject_id and day_of_week from schedule value
        const [classSubjectId, dayOfWeek] = scheduleValue.split('|');
        if (!classSubjectId || !dayOfWeek) {
            alert('Format jadwal tidak valid. Silakan pilih ulang.');
            return;
        }
        
        // Update hidden fields
        document.getElementById('selected_timetable_id').value = scheduleValue;
        
        // Build form data
        const formData = new FormData();
        formData.append('class_subject_id', classSubjectId);
        formData.append('day_of_week', dayOfWeek);
        formData.append('delegated_to_user_id', delegatedUserId);
        formData.append('teacher_id', teacherId);
        formData.append('type', document.getElementById('type').value);
        formData.append('delegation_date', document.getElementById('delegation_date').value);
        formData.append('admin_notes', document.getElementById('admin_notes').value);
        
        // Add valid_until if temporary
        if (document.getElementById('type').value === 'temporary') {
            formData.append('valid_until', document.getElementById('valid_until').value);
        }
        
        // Submit
        fetch('/admin/delegasi', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Close add delegation modal
            if (delegasiModal) {
                delegasiModal.hide();
            }
            
            if (data.success) {
                showNotification(data.message, true);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Terjadi kesalahan', false);
            }
        })
        .catch(error => {
            // Close add delegation modal even on error
            if (delegasiModal) {
                delegasiModal.hide();
            }
            
            showNotification("Terjadi kesalahan saat menyimpan: " + error.message, false);
        });
    }

    function showNotification(message, isSuccess = true) {
        const notifModal = document.getElementById('notificationModal');
        const notificationModal = new bootstrap.Modal(notifModal);
        
        // Set title based on success/failure
        document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
        document.getElementById('notificationMessage').innerText = message;
        
        notificationModal.show();
    }

    function editDelegasi(id) {
        alert("Fitur edit akan segera ditambahkan");
    }

    // Global variable untuk menyimpan ID yang akan dihapus
    let delegationIdToDelete = null;

    function hapusDelegasi(id) {
        delegationIdToDelete = id;
        
        // Show confirmation modal
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        confirmModal.show();
        
        // Clear previous event listeners
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
        
        // Add event listener to confirm button
        newConfirmBtn.addEventListener('click', function() {
            if (delegationIdToDelete) {
                executeDelete(delegationIdToDelete);
                confirmModal.hide();
            }
        });
    }

    function executeDelete(id) {
        fetch(`/admin/delegasi/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, true);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Terjadi kesalahan', false);
            }
        })
        .catch(error => {
            showNotification("Terjadi kesalahan saat menghapus", false);
        });
    }

    // Function to delete multiple delegations at once
    function hapusSemuaDelegasi(ids) {
        if (!Array.isArray(ids) || ids.length === 0) {
            showNotification('Tidak ada delegasi yang akan dihapus', false);
            return;
        }
        
        // Show confirmation modal
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        const confirmMessage = document.getElementById('confirmDeleteModalMessage');
        
        if (ids.length === 1) {
            confirmMessage.textContent = 'Apakah Anda yakin ingin menghapus delegasi ini?';
            delegationIdToDelete = ids[0];
        } else {
            confirmMessage.textContent = `Apakah Anda yakin ingin menghapus ${ids.length} delegasi sekaligus?`;
            delegationIdToDelete = ids;
        }
        
        confirmModal.show();
        
        // Clear previous event listeners
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
        
        // Add event listener to confirm button
        newConfirmBtn.addEventListener('click', function() {
            if (delegationIdToDelete) {
                if (Array.isArray(delegationIdToDelete)) {
                    executeDeleteMultiple(delegationIdToDelete);
                } else {
                    executeDelete(delegationIdToDelete);
                }
                confirmModal.hide();
            }
        });
    }

    function executeDeleteMultiple(ids) {
        // Delete all delegations sequentially
        let deletedCount = 0;
        let errorCount = 0;
        const total = ids.length;
        
        // Show loading notification
        showNotification('Menghapus delegasi...', true);
        
        // Delete each delegation
        const deletePromises = ids.map(id => {
            return fetch(`/admin/delegasi/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    deletedCount++;
                } else {
                    errorCount++;
                }
            })
            .catch(error => {
                errorCount++;
            });
        });
        
        // Wait for all deletions to complete
        Promise.all(deletePromises).then(() => {
            if (errorCount === 0) {
                showNotification(`${deletedCount} delegasi berhasil dihapus!`, true);
            } else if (deletedCount > 0) {
                showNotification(`${deletedCount} delegasi berhasil dihapus, ${errorCount} gagal.`, false);
            } else {
                showNotification('Gagal menghapus delegasi', false);
            }
            
            setTimeout(() => {
                location.reload();
            }, 1500);
        });
    }

    // ========== Functions for Teacher Leave Requests ==========

    // View detail of leave request
    function lihatDetailIzin(id) {
        try {
            const modalElement = document.getElementById('detailIzinModal');
            const modalBody = document.getElementById('detailIzinModalBody');
            
            if (!modalElement || !modalBody) {
                alert('Modal tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            const modal = new bootstrap.Modal(modalElement);
            
            // Show loading
            modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            modal.show();
            
            // Fetch detail
            fetch(`/admin/delegasi/teacher-leave-request/${id}/detail`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const request = data.data;
                    const dayNames = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    
                    let statusBadge = '';
                    if (request.status === 'pending') {
                        statusBadge = '<span class="badge bg-warning">Menunggu</span>';
                    } else if (request.status === 'approved') {
                        statusBadge = '<span class="badge bg-success">Disetujui</span>';
                    } else {
                        statusBadge = '<span class="badge bg-danger">Ditolak</span>';
                    }
                    
                    let documentHtml = '';
                    if (request.document_url) {
                        documentHtml = `
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dokumen Pendukung:</label>
                                <div>
                                    <a href="${request.document_url}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-file"></i> Lihat Dokumen
                                    </a>
                                </div>
                            </div>
                        `;
                    }
                    
                    let substituteInfo = '';
                    if (request.substitute) {
                        substituteInfo = `
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pengganti:</label>
                                <p class="mb-0">${request.substitute.full_name || 'N/A'}</p>
                            </div>
                        `;
                    }
                    
                    let processedInfo = '';
                    const processedBy = request.processed_by || request.processedBy;
                    if (processedBy) {
                        processedInfo = `
                            <div class="mb-3">
                                <label class="form-label fw-bold">Diproses Oleh:</label>
                                <p class="mb-0">${processedBy.full_name || 'N/A'} pada ${request.processed_at ? new Date(request.processed_at).toLocaleString('id-ID') : '-'}</p>
                            </div>
                        `;
                    }
                    
                    // Build schedule HTML
                    let scheduleHtml = '';
                    if (request.timetables && request.timetables.length > 0) {
                        // Group timetables by day and class
                        const grouped = {};
                        request.timetables.forEach(t => {
                            const dayOfWeek = t.day_of_week || 'N/A';
                            const className = (t.class_subject && t.class_subject.class) ? t.class_subject.class.name : 'N/A';
                            const key = `${dayOfWeek}_${className}`;
                            
                            if (!grouped[key]) {
                                grouped[key] = {
                                    day_of_week: dayOfWeek,
                                    class_name: className,
                                    dates: [],
                                    subjects: new Set(),
                                    start_times: [],
                                    end_times: [],
                                    group_type: t.group_type,
                                    location_type: t.location_type,
                                    week_alternation: t.week_alternation
                                };
                            }
                            
                            // Add date if not already exists
                            if (t.date) {
                                const dateStr = new Date(t.date).toLocaleDateString('id-ID');
                                if (!grouped[key].dates.includes(dateStr)) {
                                    grouped[key].dates.push(dateStr);
                                }
                            }
                            
                            // Add subject
                            const subjectName = (t.class_subject && t.class_subject.subject) ? t.class_subject.subject.name : 'N/A';
                            if (subjectName !== 'N/A') {
                                grouped[key].subjects.add(subjectName);
                            }
                            
                            // Add start and end times
                            if (t.start_time) {
                                grouped[key].start_times.push(t.start_time);
                            }
                            if (t.end_time) {
                                grouped[key].end_times.push(t.end_time);
                            }
                        });
                        
                        // Process grouped data
                        const processedGroups = Object.values(grouped).map(group => {
                            // Find earliest start time and latest end time
                            let earliestStart = null;
                            let latestEnd = null;
                            
                            // Helper function to convert time string to comparable format
                            const timeToMinutes = (timeStr) => {
                                if (!timeStr || timeStr === 'N/A') return null;
                                const parts = timeStr.split(':');
                                if (parts.length >= 2) {
                                    return parseInt(parts[0]) * 60 + parseInt(parts[1]);
                                }
                                return null;
                            };
                            
                            // Find earliest start time
                            group.start_times.forEach(time => {
                                if (time && time !== 'N/A') {
                                    const timeMinutes = timeToMinutes(time);
                                    if (timeMinutes !== null) {
                                        if (earliestStart === null || timeMinutes < timeToMinutes(earliestStart)) {
                                            earliestStart = time;
                                        }
                                    }
                                }
                            });
                            
                            // Find latest end time
                            group.end_times.forEach(time => {
                                if (time && time !== 'N/A') {
                                    const timeMinutes = timeToMinutes(time);
                                    if (timeMinutes !== null) {
                                        if (latestEnd === null || timeMinutes > timeToMinutes(latestEnd)) {
                                            latestEnd = time;
                                        }
                                    }
                                }
                            });
                            
                            return {
                                day_of_week: group.day_of_week,
                                class_name: group.class_name,
                                dates: group.dates.sort(),
                                subjects: Array.from(group.subjects).sort(),
                                start_time: earliestStart || 'N/A',
                                end_time: latestEnd || 'N/A',
                                group_type: group.group_type,
                                location_type: group.location_type,
                                week_alternation: group.week_alternation
                            };
                        });
                        
                        // Check if we need extra columns
                        const hasGroupType = processedGroups.some(g => g.group_type);
                        const hasLocationType = processedGroups.some(g => g.location_type);
                        const hasWeekAlternation = processedGroups.some(g => g.week_alternation);
                        
                        // New structure with multiple timetables (grouped)
                        scheduleHtml = `
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Jadwal Mengajar:</label>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Hari</th>
                                                <th>Mata Pelajaran</th>
                                                <th>Kelas</th>
                                                <th>Waktu</th>
                                                ${hasGroupType ? '<th>Kelompok</th>' : ''}
                                                ${hasLocationType ? '<th>Lokasi</th>' : ''}
                                                ${hasWeekAlternation ? '<th>Minggu</th>' : ''}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${processedGroups.map(g => {
                                                const dayName = dayNames[g.day_of_week] || 'N/A';
                                                const datesStr = g.dates.length > 0 ? g.dates.join(', ') : 'N/A';
                                                const subjectsStr = g.subjects.length > 0 ? g.subjects.join(', ') : 'N/A';
                                                
                                                // Format time (remove seconds if present)
                                                const formatTime = (timeStr) => {
                                                    if (!timeStr || timeStr === 'N/A') return 'N/A';
                                                    return timeStr.substring(0, 5); // HH:mm
                                                };
                                                
                                                const startTime = formatTime(g.start_time);
                                                const endTime = formatTime(g.end_time);
                                                
                                                let row = `<tr>
                                                    <td>${datesStr}</td>
                                                    <td>${dayName}</td>
                                                    <td>${subjectsStr}</td>
                                                    <td>${g.class_name}</td>
                                                    <td>${startTime} - ${endTime}</td>`;
                                                if (hasGroupType) {
                                                    row += `<td>${g.group_type ? 'Kelompok ' + g.group_type : '-'}</td>`;
                                                }
                                                if (hasLocationType) {
                                                    row += `<td>${g.location_type ? (g.location_type === 'lab' ? 'Lab' : 'Teori') : '-'}</td>`;
                                                }
                                                if (hasWeekAlternation) {
                                                    row += `<td>${g.week_alternation ? (g.week_alternation === 'ganjil' ? 'Ganjil' : 'Genap') : '-'}</td>`;
                                                }
                                                row += `</tr>`;
                                                return row;
                                            }).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    } else if (request.timetable && request.timetable.class_subject) {
                        // Old structure with single timetable (backward compatibility)
                        const dayName = dayNames[request.timetable.day_of_week] || 'N/A';
                        scheduleHtml = `
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mata Pelajaran:</label>
                                <p class="mb-0">${request.timetable.class_subject.subject ? request.timetable.class_subject.subject.name : 'N/A'}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kelas:</label>
                                <p class="mb-0">${request.timetable.class_subject.class ? request.timetable.class_subject.class.name : 'N/A'}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Hari:</label>
                                <p class="mb-0">${dayName}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Waktu:</label>
                                <p class="mb-0">${request.timetable.start_time || 'N/A'} - ${request.timetable.end_time || 'N/A'}</p>
                            </div>
                        `;
                    } else {
                        scheduleHtml = '<div class="col-12 mb-3"><p class="text-muted">Tidak ada jadwal tersedia</p></div>';
                    }
                    
                    modalBody.innerHTML = `
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Guru:</label>
                                <p class="mb-0">${request.teacher ? (request.teacher.full_name || 'N/A') : 'N/A'}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status:</label>
                                <p class="mb-0">${statusBadge}</p>
                            </div>
                            ${scheduleHtml}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Izin:</label>
                                <p class="mb-0">
                                    ${request.end_date && request.end_date !== request.leave_date 
                                        ? `${new Date(request.leave_date).toLocaleDateString('id-ID')} - ${new Date(request.end_date).toLocaleDateString('id-ID')}<br><small class="text-muted">(${Math.ceil((new Date(request.end_date) - new Date(request.leave_date)) / (1000 * 60 * 60 * 24)) + 1} hari)</small>`
                                        : new Date(request.leave_date).toLocaleDateString('id-ID')
                                    }
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jenis Izin:</label>
                                <p class="mb-0"><span class="badge bg-info">${request.leave_type_display || request.leave_type || 'N/A'}</span></p>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Alasan:</label>
                                <p class="mb-0">${request.reason || '-'}</p>
                            </div>
                            ${documentHtml}
                            ${substituteInfo}
                            ${processedInfo}
                            ${request.admin_notes ? `
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Catatan Admin:</label>
                                <p class="mb-0">${request.admin_notes}</p>
                            </div>
                            ` : ''}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Pengajuan:</label>
                                <p class="mb-0">${request.created_at ? new Date(request.created_at).toLocaleString('id-ID') : 'N/A'}</p>
                            </div>
                        </div>
                    `;
                } else {
                    modalBody.innerHTML = '<div class="alert alert-danger">Gagal memuat detail permohonan izin.</div>';
                }
            })
            .catch(error => {
                console.error('Error loading detail:', error);
                modalBody.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat memuat detail.</div>';
            });
        } catch (error) {
            console.error('Error in lihatDetailIzin:', error);
            alert("Terjadi kesalahan saat membuka detail permohonan izin.");
        }
    }

    // Open approve modal - opens delegation modal with pre-filled data
    function setujuiIzin(id) {
        try {
            // Fetch leave request details
            fetch(`/admin/delegasi/teacher-leave-request/${id}/detail`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(result => {
                if (!result.success || !result.data) {
                    alert('Gagal memuat detail permohonan izin. Silakan coba lagi.');
                    return;
                }
                
                const leaveRequest = result.data;
                
                // Open delegation modal with pre-filled data
                bukaModalDelegasiDariPermohonanIzin(leaveRequest, id);
            })
            .catch(error => {
                console.error('Error fetching leave request details:', error);
                alert('Terjadi kesalahan saat memuat detail permohonan izin.');
            });
        } catch (error) {
            console.error('Error in setujuiIzin:', error);
            alert("Terjadi kesalahan saat membuka modal setujui izin.");
        }
    }

    // Add delegation for already approved leave request (only show schedules without delegation)
    function tambahDelegasiDariPermohonan(id) {
        try {
            // Fetch leave request details
            fetch(`/admin/delegasi/teacher-leave-request/${id}/detail`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(result => {
                if (!result.success || !result.data) {
                    alert('Gagal memuat detail permohonan izin. Silakan coba lagi.');
                    return;
                }
                
                const leaveRequest = result.data;
                
                // Filter out timetables that already have delegations
                if (leaveRequest.timetables && leaveRequest.timetables.length > 0) {
                    const timetablesWithoutDelegation = leaveRequest.timetables.filter(t => {
                        // Check has_delegation property (it's directly on the timetable object from backend)
                        return !t.has_delegation;
                    });
                    
                    if (timetablesWithoutDelegation.length === 0) {
                        alert('Semua jadwal dari permohonan izin ini sudah memiliki delegasi.');
                        return;
                    }
                    
                    // Create a modified leave request with only timetables without delegation
                    const modifiedLeaveRequest = {
                        ...leaveRequest,
                        timetables: timetablesWithoutDelegation
                    };
                    
                    // Open delegation modal with filtered data
                    bukaModalDelegasiDariPermohonanIzin(modifiedLeaveRequest, id);
                } else if (leaveRequest.timetable) {
                    // Single timetable - check if it has delegation
                    if (leaveRequest.timetable.has_delegation) {
                        alert('Jadwal dari permohonan izin ini sudah memiliki delegasi.');
                        return;
                    }
                    
                    // Open delegation modal
                    bukaModalDelegasiDariPermohonanIzin(leaveRequest, id);
                } else {
                    alert('Tidak ada jadwal yang ditemukan untuk permohonan izin ini.');
                }
            })
            .catch(error => {
                console.error('Error fetching leave request details:', error);
                alert('Terjadi kesalahan saat memuat detail permohonan izin.');
            });
        } catch (error) {
            console.error('Error in tambahDelegasiDariPermohonan:', error);
            alert("Terjadi kesalahan saat membuka modal tambah delegasi.");
        }
    }

    // Process approve
    function prosesSetujuiIzin() {
        const id = document.getElementById('setujui_izin_id').value;
        const substituteUserId = document.getElementById('substitute_user_id').value;
        const adminNotes = document.getElementById('approve_admin_notes').value;
        
        if (!substituteUserId) {
            alert('Email pengganti tidak valid. Pastikan email terdaftar di sistem.');
            return;
        }
        
        fetch(`/admin/delegasi/teacher-leave-request/${id}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                substitute_user_id: substituteUserId,
                admin_notes: adminNotes
            })
        })
        .then(response => response.json())
        .then(data => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('setujuiIzinModal'));
            if (modal) {
                modal.hide();
            }
            
            if (data.success) {
                showNotification(data.message, true);
                setTimeout(() => {
                    // Reload with hash to stay on permohonan izin tab
                    window.location.hash = '#permohonan-izin';
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Terjadi kesalahan', false);
            }
        })
        .catch(error => {
            showNotification("Terjadi kesalahan saat menyetujui permohonan izin", false);
        });
    }

    // Open reject modal
    function tolakIzin(id) {
        try {
            const modalElement = document.getElementById('tolakIzinModal');
            if (!modalElement) {
                alert('Modal tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            document.getElementById('tolak_izin_id').value = id;
            const form = document.getElementById('tolakIzinForm');
            if (form) {
                form.reset();
            }
            document.getElementById('tolak_izin_id').value = id;
            
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } catch (error) {
            console.error('Error in tolakIzin:', error);
            alert("Terjadi kesalahan saat membuka modal tolak izin.");
        }
    }

    // Process reject
    function prosesTolakIzin() {
        const id = document.getElementById('tolak_izin_id').value;
        const adminNotes = document.getElementById('reject_admin_notes').value;
        
        fetch(`/admin/delegasi/teacher-leave-request/${id}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                admin_notes: adminNotes
            })
        })
        .then(response => response.json())
        .then(data => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('tolakIzinModal'));
            if (modal) {
                modal.hide();
            }
            
            if (data.success) {
                showNotification(data.message, true);
                setTimeout(() => {
                    // Reload with hash to stay on permohonan izin tab
                    window.location.hash = '#permohonan-izin';
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Terjadi kesalahan', false);
            }
        })
        .catch(error => {
            showNotification("Terjadi kesalahan saat menolak permohonan izin", false);
        });
    }

    // Make functions globally available
    window.bukaModalTambahDelegasi = bukaModalTambahDelegasi;
    window.bukaModalDelegasiDariPermohonanIzin = bukaModalDelegasiDariPermohonanIzin;
    window.simpanDelegasi = simpanDelegasi;
    window.showNotification = showNotification;
    window.editDelegasi = editDelegasi;
    window.hapusDelegasi = hapusDelegasi;
    window.hapusSemuaDelegasi = hapusSemuaDelegasi;
    window.lihatDetailIzin = lihatDetailIzin;
    window.setujuiIzin = setujuiIzin;
    window.tambahDelegasiDariPermohonan = tambahDelegasiDariPermohonan;
    window.prosesSetujuiIzin = prosesSetujuiIzin;
    window.tolakIzin = tolakIzin;
    window.prosesTolakIzin = prosesTolakIzin;
    window.filterSchedules = filterSchedules;
}})();
