<?php $__env->startSection('content'); ?>

<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Pengaturan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0 d-flex align-items-center">
                    <i class="bx bx-user me-2"></i>
                    Pengaturan Profil Admin
                </h4>
            </div>
            <div class="card-body">
                
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-3" style="width: 120px; height: 120px;">
                        <img id="avatarPreview" src="<?php echo e(auth()->user()->photo ? asset('storage/users/' . auth()->user()->photo) : '/images/users/avatar-1.jpg'); ?>" alt="Avatar" class="rounded-circle img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <input type="file" id="photoInput" accept="image/*" style="display: none;">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('photoInput').click()">
                        <i class="bx bx-camera me-1"></i>
                        Ganti Foto
                    </button>
                    <div id="photoError" class="text-danger mt-2" style="display: none;"></div>
                </div>
                
                <form id="profileForm">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo e(auth()->user()->full_name ?? ''); ?>" required>
                            <div class="invalid-feedback" id="full_name_error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo e(auth()->user()->email ?? ''); ?>" required>
                            <div class="invalid-feedback" id="email_error"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Nomor HP</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo e(auth()->user()->phone ?? ''); ?>" required>
                            <div class="invalid-feedback" id="phone_error"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini (untuk konfirmasi)</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <div class="invalid-feedback" id="current_password_error"></div>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-12">
                            <hr class="my-4">
                            <h6 class="mb-3 text-primary">
                                <i class="bx bx-lock me-2"></i>
                                Ubah Password
                            </h6>
                            <p class="text-muted mb-3">Kosongkan field password jika tidak ingin mengubah password</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                   minlength="8" placeholder="Minimal 8 karakter">
                            <div class="invalid-feedback" id="new_password_error"></div>
                            <div class="form-text">Password harus minimal 8 karakter</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" 
                                   minlength="8" placeholder="Ulangi password baru">
                            <div class="invalid-feedback" id="new_password_confirmation_error"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Simpan Perubahan
                            </button>
                            <button type="button" class="btn btn-secondary ms-2" onclick="resetForm()">
                                <i class="bx bx-refresh me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0 d-flex align-items-center">
                    <i class="bx bx-info-circle me-2"></i>
                    Informasi Sistem
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><strong>Versi Aplikasi:</strong></td>
                                    <td>1.0.0</td>
                                </tr>
                                <tr>
                                    <td><strong>Versi PHP:</strong></td>
                                    <td><?php echo e(phpversion()); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Framework:</strong></td>
                                    <td>Laravel <?php echo e(app()->version()); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Database:</strong></td>
                                    <td><?php echo e(config('database.default')); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Environment:</strong></td>
                                    <td><?php echo e(app()->environment()); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><strong>Server:</strong></td>
                                    <td><?php echo e($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>OS:</strong></td>
                                    <td><?php echo e(PHP_OS); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Memory Limit:</strong></td>
                                    <td><?php echo e(ini_get('memory_limit')); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Max Execution Time:</strong></td>
                                    <td><?php echo e(ini_get('max_execution_time')); ?>s</td>
                                </tr>
                                <tr>
                                    <td><strong>Upload Max Size:</strong></td>
                                    <td><?php echo e(ini_get('upload_max_filesize')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="mb-3">Statistik Database</h6>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center">
                                            <i class="bx bx-user fs-20 text-primary"></i>
                                        </div>
                                        <h5 class="mb-1" id="totalUsers">-</h5>
                                        <p class="text-muted mb-0">Total Users</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center">
                                            <i class="bx bx-chalkboard fs-20 text-success"></i>
                                        </div>
                                        <h5 class="mb-1" id="totalTeachers">-</h5>
                                        <p class="text-muted mb-0">Total Guru</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <div class="avatar-sm bg-info bg-opacity-10 rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center">
                                            <i class="bx bx-group fs-20 text-info"></i>
                                        </div>
                                        <h5 class="mb-1" id="totalStudents">-</h5>
                                        <p class="text-muted mb-0">Total Siswa</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center">
                                            <i class="bx bx-news fs-20 text-warning"></i>
                                        </div>
                                        <h5 class="mb-1" id="totalAnnouncements">-</h5>
                                        <p class="text-muted mb-0">Total Pengumuman</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Load database statistics on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadDatabaseStats();
        
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
            
            fetch('/admin/pengaturan/photo', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message || 'Foto profil berhasil diperbarui.');
                } else {
                    showAlert('error', data.message || 'Gagal memperbarui foto profil.');
                    // Revert to original photo on error
                    avatarPreview.src = '/images/users/avatar-1.jpg';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat mengunggah foto.');
                // Revert to original photo on error
                avatarPreview.src = '/images/users/avatar-1.jpg';
            });
        }
    });

    function loadDatabaseStats() {
        fetch('/admin/pengaturan/stats', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalUsers').textContent = data.stats.users || 0;
                document.getElementById('totalTeachers').textContent = data.stats.teachers || 0;
                document.getElementById('totalStudents').textContent = data.stats.students || 0;
                document.getElementById('totalAnnouncements').textContent = data.stats.announcements || 0;
            }
        })
        .catch(error => {
            console.error('Error loading database stats:', error);
            // Set default values if API fails
            document.getElementById('totalUsers').textContent = '0';
            document.getElementById('totalTeachers').textContent = '0';
            document.getElementById('totalStudents').textContent = '0';
            document.getElementById('totalAnnouncements').textContent = '0';
        });
    }

    // Profile form functions
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateProfile();
    });

    // Add real-time password confirmation validation
    document.getElementById('new_password_confirmation').addEventListener('input', function() {
        validatePasswordConfirmation();
    });

    document.getElementById('new_password').addEventListener('input', function() {
        validatePasswordConfirmation();
    });

    function validatePasswordConfirmation() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('new_password_confirmation').value;
        const confirmField = document.getElementById('new_password_confirmation');
        const errorElement = document.getElementById('new_password_confirmation_error');

        if (newPassword && confirmPassword) {
            if (newPassword !== confirmPassword) {
                confirmField.classList.add('is-invalid');
                errorElement.textContent = 'Konfirmasi password tidak sesuai';
            } else {
                confirmField.classList.remove('is-invalid');
                errorElement.textContent = '';
            }
        } else {
            confirmField.classList.remove('is-invalid');
            errorElement.textContent = '';
        }
    }

    function updateProfile() {
        const form = document.getElementById('profileForm');
        const formData = new FormData(form);
        
        // Validate password confirmation before submit
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('new_password_confirmation').value;
        
        if (newPassword && confirmPassword && newPassword !== confirmPassword) {
            showAlert('error', 'Konfirmasi password tidak sesuai');
            return;
        }
        
        // Clear previous validation errors
        clearValidationErrors();
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...';
        submitBtn.disabled = true;

        fetch('/admin/pengaturan/profile', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message || 'Profil berhasil diperbarui');
                // Clear password fields
                document.getElementById('current_password').value = '';
                document.getElementById('new_password').value = '';
                document.getElementById('new_password_confirmation').value = '';
            } else {
                if (data.errors) {
                    showValidationErrors(data.errors);
                } else {
                    showAlert('error', data.message || 'Gagal memperbarui profil');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memperbarui profil');
        })
        .finally(() => {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    function resetForm() {
        if (confirm('Apakah Anda yakin ingin mereset form ke nilai awal?')) {
            document.getElementById('profileForm').reset();
            clearValidationErrors();
            // Reload page to get fresh data
            window.location.reload();
        }
    }

    function clearValidationErrors() {
        const errorElements = document.querySelectorAll('.invalid-feedback');
        const inputElements = document.querySelectorAll('.form-control');
        
        errorElements.forEach(element => {
            element.textContent = '';
        });
        
        inputElements.forEach(element => {
            element.classList.remove('is-invalid');
        });
    }

    function showValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            const errorElement = document.getElementById(field + '_error');
            
            if (input && errorElement) {
                input.classList.add('is-invalid');
                errorElement.textContent = errors[field][0];
            }
        });
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insert alert at the top of the content
        const content = document.querySelector('.page-content .container-fluid');
        content.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            const alert = content.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'Pengaturan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/pengaturan.blade.php ENDPATH**/ ?>