<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Pengaturan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-all me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-block-helper me-2"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:user-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="card-title mb-1">
                                Profil Guru
                            </h4>
                            <p class="text-muted mb-0">Kelola informasi profil Anda</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3" style="width: 120px; height: 120px;">
                            <img id="avatarPreview" src="<?php echo e(Auth::user()->photo ? asset('storage/users/' . Auth::user()->photo) : '/images/users/avatar-1.jpg'); ?>" alt="Avatar" class="rounded-circle img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <input type="file" id="photoInput" accept="image/*" style="display: none;">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('photoInput').click()">
                            <iconify-icon icon="solar:camera-outline" class="fs-16 me-1"></iconify-icon>
                            Ganti Foto
                        </button>
                        <div id="photoError" class="text-danger mt-2" style="display: none;"></div>
                    </div>
                    
                    <form method="POST" action="<?php echo e(route('guru.pengaturan.update-profil')); ?>" id="profilForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="full_name" value="<?php echo e(Auth::user()->full_name); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nip" class="form-label fw-semibold">NIP</label>
                                    <input type="text" class="form-control" id="nip" name="nip" value="<?php echo e(Auth::user()->teacher->nip ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo e(Auth::user()->email); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label fw-semibold">No. Handphone</label>
                                    <input type="text" class="form-control" id="no_hp" name="phone" value="<?php echo e(Auth::user()->phone ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="solar:diskette-outline" class="fs-16 me-2"></iconify-icon>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-warning bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:shield-user-outline" class="fs-32 text-warning avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="card-title mb-1">
                                Keamanan Akun
                            </h4>
                            <p class="text-muted mb-0">Kelola keamanan akun Anda</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('guru.pengaturan.update-password')); ?>" id="passwordForm">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="password_lama" class="form-label fw-semibold">Password Lama</label>
                            <input type="password" class="form-control" id="password_lama" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_baru" class="form-label fw-semibold">Password Baru</label>
                            <input type="password" class="form-control" id="password_baru" name="password" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label for="konfirmasi_password" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="konfirmasi_password" name="password_confirmation" required minlength="8">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <iconify-icon icon="solar:lock-password-outline" class="fs-16 me-2"></iconify-icon>
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:info-circle-outline" class="fs-20 me-2"></iconify-icon>
                        Informasi Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Versi Aplikasi</span>
                        <span class="fw-semibold"><?php echo e(config('app.version', '1.0.0')); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">PHP Version</span>
                        <span class="fw-semibold"><?php echo e(phpversion()); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Laravel Version</span>
                        <span class="fw-semibold"><?php echo e(app()->version()); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Database</span>
                        <span class="fw-semibold"><?php echo e(config('database.default')); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Environment</span>
                        <span class="badge bg-<?php echo e(app()->environment() === 'production' ? 'danger' : 'warning'); ?>-subtle text-<?php echo e(app()->environment() === 'production' ? 'danger' : 'warning'); ?> py-1 px-2">
                            <?php echo e(strtoupper(app()->environment())); ?>

                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Status Server</span>
                        <span class="badge bg-success-subtle text-success py-1 px-2">
                            <i class="bx bxs-circle text-success me-1"></i>Online
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Total Siswa</span>
                        <span class="fw-semibold"><?php echo e(\App\Models\Student::count()); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));

        // Ensure close buttons work
        document.querySelector('#notificationModal .btn-close').addEventListener('click', () => {
            notificationModal.hide();
        });
        document.querySelector('#notificationModal .btn-light').addEventListener('click', () => {
            notificationModal.hide();
        });

        function showNotification(message, isSuccess = true) {
            document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
            document.getElementById('notificationMessage').innerText = message;
            notificationModal.show();
        }

        // Handle photo upload
        const photoInput = document.getElementById('photoInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const photoError = document.getElementById('photoError');
        
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                
                if (!file) {
                    return;
                }
                
                // Validate file type
                if (!file.type.match('image.*')) {
                    photoError.textContent = 'File harus berupa gambar';
                    photoError.style.display = 'block';
                    return;
                }
                
                // Validate file size (200KB = 200 * 1024 bytes)
                const maxSize = 200 * 1024;
                if (file.size > maxSize) {
                    photoError.textContent = 'Ukuran file maksimal 200KB';
                    photoError.style.display = 'block';
                    return;
                }
                
                // Clear error
                photoError.style.display = 'none';
                
                // Preview image
                const reader = new FileReader();
                reader.onload = function(event) {
                    avatarPreview.src = event.target.result;
                    // Ensure circular shape with fixed dimensions
                    avatarPreview.style.width = '100%';
                    avatarPreview.style.height = '100%';
                    avatarPreview.style.objectFit = 'cover';
                };
                reader.readAsDataURL(file);
                
                // Upload photo automatically
                uploadPhoto(file);
            });
        }
        
        function uploadPhoto(file) {
            const formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('<?php echo e(route("guru.pengaturan.photo")); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Foto profil berhasil diperbarui.', true);
                } else {
                    showNotification(data.message || 'Gagal memperbarui foto profil.', false);
                    // Revert to original photo on error
                    avatarPreview.src = '/images/users/avatar-1.jpg';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat mengunggah foto.', false);
                // Revert to original photo on error
                avatarPreview.src = '/images/users/avatar-1.jpg';
            });
        }

        // Handle form submissions with AJAX
        const profilForm = document.getElementById('profilForm');
        const passwordForm = document.getElementById('passwordForm');

        if (profilForm) {
            profilForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                
                // Disable button and show loading
                submitButton.disabled = true;
                submitButton.innerHTML = '<iconify-icon icon="solar:loading-outline" class="fs-16 me-2"></iconify-icon>Menyimpan...';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, true);
                        // Reset form if needed
                        if (data.reset_form) {
                            this.reset();
                        }
                    } else {
                        showNotification(data.message, false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat memproses permintaan.', false);
                })
                .finally(() => {
                    // Re-enable button
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                });
            });
        }

        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                
                // Disable button and show loading
                submitButton.disabled = true;
                submitButton.innerHTML = '<iconify-icon icon="solar:loading-outline" class="fs-16 me-2"></iconify-icon>Mengubah...';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, true);
                        // Reset form
                        this.reset();
                    } else {
                        showNotification(data.message, false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat memproses permintaan.', false);
                })
                .finally(() => {
                    // Re-enable button
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                });
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Pengaturan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/pengaturan-guru.blade.php ENDPATH**/ ?>