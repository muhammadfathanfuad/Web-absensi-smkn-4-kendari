<?php $__env->startSection('title', 'Permohonan Izin'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Permohonan Izin</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Siswa</li>
                        <li class="breadcrumb-item active">Permohonan Izin</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-file-plus me-2"></i>
                        Ajukan Permohonan Izin
                    </h4>
                </div>
                <div class="card-body">
                    <form id="permohonanForm" action="<?php echo e(route('murid.permohonan-izin.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenisIzin" class="form-label">Jenis Izin <span class="text-danger">*</span></label>
                                    <select class="form-select" id="jenisIzin" name="jenisIzin" required>
                                        <option value="">Pilih Jenis Izin</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="izin">Izin</option>
                                        <option value="keperluan-keluarga">Keperluan Keluarga</option>
                                        <option value="acara-keluarga">Acara Keluarga</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggalMulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggalMulai" name="tanggalMulai" required>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row" id="jenisIzinLainnya" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenisIzinCustom" class="form-label">Jenis Izin Lainnya <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="jenisIzinCustom" name="jenisIzinCustom" placeholder="Tuliskan jenis izin lainnya...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggalSelesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggalSelesai" name="tanggalSelesai">
                                </div>
                            </div>
                        </div>

                        
                        <div class="row" id="tanggalSelesaiNormal">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggalSelesaiNormal" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggalSelesaiNormal" name="tanggalSelesaiNormal" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Izin <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="4" placeholder="Jelaskan alasan mengajukan izin..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="dokumenPendukung" class="form-label">Dokumen Pendukung</label>
                            <input type="file" class="form-control" id="dokumenPendukung" name="dokumenPendukung" accept=".pdf,.jpg,.jpeg,.png" data-max-size="512000">
                            <small class="form-text text-muted">Format yang diperbolehkan: PDF, JPG, PNG (Maksimal 500KB)</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-send me-1"></i>
                                Ajukan Permohonan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        Informasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Ketentuan Izin:</h6>
                        <ul class="mb-0 small">
                            <li>Izin harus diajukan minimal 1 hari sebelumnya</li>
                            <li>Untuk izin sakit, lampirkan surat dokter</li>
                            <li>Izin akan diproses dalam 1-2 hari kerja</li>
                            <li>Status dapat dicek di riwayat permohonan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-history me-2"></i>
                        Riwayat Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php $__empty_1 = true; $__currentLoopData = $recentRequests ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?php echo e($request->leave_type_display); ?></h6>
                                    <small class="text-muted"><?php echo e($request->created_at->format('d M Y')); ?></small>
                                </div>
                                <span class="badge bg-<?php echo e($request->status_badge); ?>">
                                    <?php switch($request->status):
                                        case ('pending'): ?>
                                            Menunggu
                                            <?php break; ?>
                                        <?php case ('approved'): ?>
                                            Disetujui
                                            <?php break; ?>
                                        <?php case ('rejected'): ?>
                                            Ditolak
                                            <?php break; ?>
                                        <?php default: ?>
                                            <?php echo e(ucfirst($request->status)); ?>

                                    <?php endswitch; ?>
                                </span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="list-group-item text-center text-muted">
                                <small>Belum ada permohonan izin</small>
                            </div>
                        <?php endif; ?>
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
                        <i class="bx bx-list-ul me-2"></i>
                        Riwayat Permohonan Izin
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Izin</th>
                                    <th>Tanggal</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Tanggal Ajukan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $startDate = \Carbon\Carbon::parse($request->start_date);
                                        $endDate = \Carbon\Carbon::parse($request->end_date);
                                        $duration = $startDate->diffInDays($endDate) + 1;
                                        $leaveTypeDisplay = $request->leave_type_display ?? ucfirst($request->leave_type);
                                        // Calculate correct sequential number across pages
                                        $sequentialNumber = ($recentRequests->currentPage() - 1) * $recentRequests->perPage() + $i + 1;
                                    ?>
                                    <tr>
                                        <td><?php echo e($sequentialNumber); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($request->leave_type === 'sakit' ? 'danger' : ($request->leave_type === 'izin' ? 'secondary' : ($request->leave_type === 'keperluan-keluarga' ? 'info' : 'primary'))); ?>">
                                                <?php echo e($leaveTypeDisplay); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($startDate->format('d M Y')); ?> <?php if($duration > 1): ?> - <?php echo e($endDate->format('d M Y')); ?> <?php endif; ?></td>
                                        <td><?php echo e($duration); ?> hari</td>
                                        <td>
                                            <span class="badge bg-<?php echo e($request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning')); ?>">
                                                <?php if($request->status === 'pending'): ?>
                                                    Menunggu
                                                <?php elseif($request->status === 'approved'): ?>
                                                    Disetujui
                                                <?php elseif($request->status === 'rejected'): ?>
                                                    Ditolak
                                                <?php else: ?>
                                                    <?php echo e(ucfirst($request->status)); ?>

                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td><?php echo e($request->created_at->format('d M Y')); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="showDetailModal(<?php echo e($request->id); ?>)">
                                                <i class="bx bx-show"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-inbox fs-48 d-block mx-auto mb-2"></i>
                                                Belum ada riwayat permohonan izin
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    
                    <?php if($recentRequests->hasPages()): ?>
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top" id="pagination-wrapper">
                            <div class="text-muted">
                                Menampilkan <?php echo e($recentRequests->firstItem()); ?> sampai <?php echo e($recentRequests->lastItem()); ?> dari <?php echo e($recentRequests->total()); ?> data
                            </div>
                            <div class="d-flex">
                                <?php echo e($recentRequests->links('pagination::bootstrap-4')); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
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

    <!-- Modal Detail Permohonan -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">
                        <i class="bx bx-detail me-2"></i>Detail Permohonan Izin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('permohonanForm');
    const tanggalMulai = document.getElementById('tanggalMulai');
    const tanggalSelesai = document.getElementById('tanggalSelesai');
    const tanggalSelesaiNormal = document.getElementById('tanggalSelesaiNormal');
    const jenisIzin = document.getElementById('jenisIzin');
    const jenisIzinLainnya = document.getElementById('jenisIzinLainnya');
    const jenisIzinCustom = document.getElementById('jenisIzinCustom');
    const modalElement = document.getElementById('notificationModal');
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
        document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
        document.getElementById('notificationMessage').innerText = message;
        
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
            document.getElementById('tanggalSelesaiNormal').style.display = 'none';
            jenisIzinCustom.required = true;
            tanggalSelesai.required = true;
            tanggalSelesaiNormal.required = false;
        } else {
            jenisIzinLainnya.style.display = 'none';
            document.getElementById('tanggalSelesaiNormal').style.display = 'block';
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
        if (fileInput.files.length > 0) {
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
        
        // Send AJAX request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
                document.getElementById('tanggalSelesaiNormal').style.display = 'block';
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
    
    form.reset();
    
    // Reset default date to today
    const today = new Date().toISOString().split('T')[0];
    tanggalMulai.value = today;
    tanggalSelesaiNormal.value = today;
    
    // Hide custom field and show normal field
    jenisIzinLainnya.style.display = 'none';
    document.getElementById('tanggalSelesaiNormal').style.display = 'block';
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
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    const modalBody = document.getElementById('detailModalBody');
    
    // Show modal with loading state
    modal.show();
    
    // Fetch leave request details
    fetch(`/student/permohonan-izin/${id}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.vertical-murid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/murid/permohonan-izin.blade.php ENDPATH**/ ?>