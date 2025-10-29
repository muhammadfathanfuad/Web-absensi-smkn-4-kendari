

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Manajemen Tugas Pengganti'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">📋 Manajemen Delegasi Absensi</h5>
                    <button type="button" class="btn btn-primary" id="tambahDelegasiBtn" onclick="bukaModalTambahDelegasi()">
                        <i class="bx bx-plus"></i> Tambah Delegasi
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jadwal</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Guru Asli</th>
                                <th>Delegasi Kepada</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="delegasiTableBody">
                            <?php $__empty_1 = true; $__currentLoopData = $delegations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $delegasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($delegasi->timetable->day_of_week); ?></td>
                                <td><?php echo e($delegasi->timetable->classSubject->subject->name ?? 'N/A'); ?></td>
                                <td><?php echo e($delegasi->timetable->classSubject->class->name ?? 'N/A'); ?></td>
                                <td><?php echo e($delegasi->originalTeacher->user->full_name ?? 'N/A'); ?></td>
                                <td><?php echo e($delegasi->delegatedTo->full_name ?? 'N/A'); ?></td>
                                <td>
                                    <?php if($delegasi->type == 'permanent'): ?>
                                        <span class="badge bg-info">Permanent</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Temporary</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($delegasi->status == 'active'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php elseif($delegasi->status == 'revoked'): ?>
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Kedaluwarsa</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusDelegasi(<?php echo e($delegasi->id); ?>)">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <p class="text-muted mb-0">Belum ada delegasi</p>
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

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalTitle">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmDeleteModalMessage">Apakah Anda yakin ingin menghapus delegasi ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Notifikasi -->
<div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="notificationMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Delegasi -->
<div class="modal fade" id="delegasiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delegasiModalTitle">Tambah Delegasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="delegasiForm">
                    <input type="hidden" id="delegasi_id" name="id">
                    <input type="hidden" id="selected_timetable_id" name="timetable_id" value="">
                    
                    <!-- Step 1: Pilih Mata Pelajaran -->
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select" id="subject_id" name="subject_id" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Step 2: Pilih Kelas -->
                    <div class="mb-3">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select" id="class_id" name="class_id" required>
                            <option value="">Pilih Kelas</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>">
                                <?php echo e($class->name); ?> 
                                <?php if($class->grade): ?> - Kelas <?php echo e($class->grade); ?> <?php endif; ?>
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Step 3: Pilih Guru yang digantikan -->
                    <div class="mb-3">
                        <label class="form-label">Email Guru yang Digantikan <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="teacher_email" name="teacher_email" placeholder="contoh@email.com" required>
                        <small class="text-muted">Masukkan email guru yang akan digantikan</small>
                        <div id="teacher_email_validation_message" class="mt-2"></div>
                        <input type="hidden" id="teacher_id" name="teacher_id" value="">
                    </div>

                    <!-- Step 4: Pilih Jadwal (filtered by subject, class, teacher) -->
                    <div class="mb-3" id="schedule_wrapper" style="display: none;">
                        <label class="form-label">Jadwal <span class="text-danger">*</span></label>
                        <select class="form-select" id="schedule_id" name="schedule_id" required>
                            <option value="">Pilih Jadwal</option>
                        </select>
                        <div id="schedule_info" class="mt-2 text-muted"></div>
                    </div>

                    <!-- Step 5: Tanggal Sesuai Jadwal -->
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="delegation_date" name="delegation_date" required>
                        <small class="text-muted">Pilih tanggal sesuai jadwal hari yang dipilih</small>
                    </div>

                    <!-- Step 6: Pilih Delegasi Kepada -->
                    <div class="mb-3">
                        <label class="form-label">Email Delegasi Kepada <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="delegated_to_email" name="delegated_to_email" placeholder="contoh@email.com" required>
                        <small class="text-muted">Masukkan email guru atau murid yang akan menerima delegasi</small>
                        <div id="email_validation_message" class="mt-2"></div>
                        <input type="hidden" id="delegated_to_user_id" name="delegated_to_user_id" value="">
                    </div>

                    <!-- Step 7: Tipe Delegasi -->
                    <div class="mb-3">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Pilih Tipe</option>
                            <option value="permanent">Permanent</option>
                            <option value="temporary">Temporary</option>
                        </select>
                    </div>

                    <div class="mb-3" id="validUntilWrapper" style="display: none;">
                        <label class="form-label">Berlaku Sampai</label>
                        <input type="date" class="form-control" id="valid_until" name="valid_until">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Admin</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" placeholder="Alasan delegasi (opsional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="simpanDelegasi()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Inisialisasi modal
var delegasiModal;
var delegasiEditingId = null;

// Store all timetables data
const allTimetables = <?php echo json_encode($timetables, 15, 512) ?>;

// Initialize notification modal listener
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded fired');
    console.log('Modal element:', document.getElementById('delegasiModal'));
    
    // Inisialisasi modal
    var modalElement = document.getElementById('delegasiModal');
    if (modalElement) {
        delegasiModal = new bootstrap.Modal(modalElement);
        console.log('Modal initialized successfully');
    } else {
        console.error('Modal element not found');
    }
    
    console.log('delegasiModal variable:', delegasiModal);
    
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
                console.error('Error:', error);
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
                console.error('Error:', error);
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
            
            // Group by day_of_week to merge duplicate schedules
            const groupedByDay = {};
            filtered.forEach(t => {
                const key = t.day_of_week;
                if (!groupedByDay[key]) {
                    groupedByDay[key] = [];
                }
                groupedByDay[key].push(t);
            });
            
            // Process grouped schedules
            Object.keys(groupedByDay).forEach(day => {
                const schedules = groupedByDay[day];
                
                // If only one schedule for this day, use it as is
                if (schedules.length === 1) {
                    const t = schedules[0];
                    const option = document.createElement('option');
                    option.value = t.id;
                    option.text = `${t.day_of_week} - ${t.start_time} - ${t.end_time}`;
                    scheduleSelect.appendChild(option);
                } else {
                    // Multiple schedules, find earliest start and latest end
                    let earliestStart = schedules[0].start_time;
                    let latestEnd = schedules[0].end_time;
                    let timetableId = schedules[0].id;
                    
                    schedules.forEach(t => {
                        if (t.start_time < earliestStart) {
                            earliestStart = t.start_time;
                            timetableId = t.id; // Use ID of the earliest starting schedule
                        }
                        if (t.end_time > latestEnd) {
                            latestEnd = t.end_time;
                        }
                    });
                    
                    // Create merged option
                    const option = document.createElement('option');
                    option.value = timetableId;
                    option.text = `${day} - ${earliestStart} - ${latestEnd}`;
                    scheduleSelect.appendChild(option);
                }
            });
        } else {
            if (subjectId && classId && teacherId) {
                // Only show alert if all filters are filled
                document.getElementById('schedule_info').innerHTML = '<span class="text-danger">Tidak ada jadwal yang cocok dengan kriteria yang dipilih.</span>';
            }
        }
    }
}

// Global function untuk buka modal (dipanggil dari onclick)
function bukaModalTambahDelegasi() {
    console.log('bukaModalTambahDelegasi called');
    console.log('delegasiModal:', delegasiModal);
    
    delegasiEditingId = null;
    
    var modalTitle = document.getElementById('delegasiModalTitle');
    var delegasiForm = document.getElementById('delegasiForm');
    var delegasiId = document.getElementById('delegasi_id');
    var validUntilWrapper = document.getElementById('validUntilWrapper');
    var scheduleWrapper = document.getElementById('schedule_wrapper');
    
    console.log('Elements found:', {
        modalTitle: modalTitle,
        delegasiForm: delegasiForm,
        delegasiId: delegasiId,
        validUntilWrapper: validUntilWrapper,
        scheduleWrapper: scheduleWrapper
    });
    
    if (modalTitle) modalTitle.textContent = 'Tambah Delegasi';
    if (delegasiForm) delegasiForm.reset();
    if (delegasiId) delegasiId.value = '';
    if (validUntilWrapper) validUntilWrapper.style.display = 'none';
    if (scheduleWrapper) scheduleWrapper.style.display = 'none';
    
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
        console.log('Attempting to show modal');
        delegasiModal.show();
    } else {
        console.error('delegasiModal is null! Trying to init...');
        // Fallback: coba init modal lagi
        var modalElement = document.getElementById('delegasiModal');
        if (modalElement) {
            delegasiModal = new bootstrap.Modal(modalElement);
            delegasiModal.show();
        } else {
            console.error('Modal element not found!');
        }
    }
}

// Global functions
function simpanDelegasi() {
    console.log('simpanDelegasi called');
    
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
    const scheduleId = document.getElementById('schedule_id').value;
    if (!scheduleId) {
        alert('Silakan pilih jadwal terlebih dahulu.');
        return;
    }
    
    // Update hidden fields
    document.getElementById('selected_timetable_id').value = scheduleId;
    
    // Build form data
    const formData = new FormData();
    formData.append('timetable_id', scheduleId);
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
        console.error('Error:', error);
        
        // Close add delegation modal even on error
        if (delegasiModal) {
            delegasiModal.hide();
        }
        
        showNotification('Terjadi kesalahan saat menyimpan: ' + error.message, false);
    });
}

function showNotification(message, isSuccess = true) {
    console.log('showNotification called:', message, isSuccess);
    
    const notifModal = document.getElementById('notificationModal');
    const notificationModal = new bootstrap.Modal(notifModal);
    
    // Set title based on success/failure
    document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
    document.getElementById('notificationMessage').innerText = message;
    
    console.log('Showing notification modal');
    notificationModal.show();
}

function editDelegasi(id) {
    alert('Fitur edit akan segera ditambahkan');
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
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat menghapus', false);
        });
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'Manajemen Delegasi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/delegasi.blade.php ENDPATH**/ ?>