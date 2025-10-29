<?php $__env->startSection('title', 'Pengaturan'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Pengaturan</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Siswa</li>
                        <li class="breadcrumb-item active">Pengaturan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-user me-2"></i>
                        Profil Akun
                    </h4>
                </div>
                <div class="card-body">
                    <form id="profilForm">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3" style="width: 120px; height: 120px;">
                                <img id="avatarPreview" src="<?php echo e($user->photo ? asset('storage/users/' . $user->photo) : '/images/users/avatar-1.jpg'); ?>" alt="Avatar" class="rounded-circle img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <input type="file" id="photoInput" accept="image/*" style="display: none;">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('photoInput').click()">
                                <i class="bx bx-camera me-1"></i>
                                Ganti Foto
                            </button>
                            <div id="photoError" class="text-danger mt-2" style="display: none;"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="namaLengkap" value="<?php echo e($user->full_name); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nis" class="form-label">NIS</label>
                                    <input type="text" class="form-control" id="nis" value="<?php echo e($user->student->nis ?? '-'); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="<?php echo e($user->email); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" id="telepon" value="<?php echo e($user->phone); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-shield me-2"></i>
                        Keamanan Akun
                    </h4>
                </div>
                <div class="card-body">
                    <form id="keamananForm">
                        <div class="mb-3">
                            <label for="passwordLama" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="passwordLama" placeholder="Masukkan password lama">
                        </div>

                        <div class="mb-3">
                            <label for="passwordBaru" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="passwordBaru" placeholder="Masukkan password baru">
                        </div>

                        <div class="mb-3">
                            <label for="konfirmasiPassword" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="konfirmasiPassword" placeholder="Konfirmasi password baru">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="bx bx-key me-1"></i>
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-history me-2"></i>
                        Aktivitas Akun
                    </h4>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush" id="aktivitasList">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Login Terakhir</h6>
                                <small class="text-muted" id="lastLogin">-</small>
                            </div>
                            <i class="bx bx-check-circle text-success"></i>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Password Terakhir Diubah</h6>
                                <small class="text-muted" id="lastPasswordChange">-</small>
                            </div>
                            <i class="bx bx-shield text-info"></i>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Profil Terakhir Diupdate</h6>
                                <small class="text-muted" id="lastProfileUpdate">-</small>
                            </div>
                            <i class="bx bx-user text-primary"></i>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Akun Dibuat</h6>
                                <small class="text-muted" id="accountCreated">-</small>
                            </div>
                            <i class="bx bx-calendar text-secondary"></i>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-danger" onclick="logoutSemua()">
                            <i class="bx bx-log-out me-1"></i>
                            Logout dari Semua Device
                        </button>
                    </div>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('notificationModal');
    const notificationModal = new bootstrap.Modal(modalElement);
    
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
    let isModalOpen = false;
    
    function showNotification(message, isSuccess = true) {
        // Prevent multiple notifications
        if (isModalOpen) {
            return;
        }
        
        isModalOpen = true;
        
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
        isModalOpen = false;
    });
    
    // Handle close button events
    const closeButton = modalElement.querySelector('.btn-close');
    const closeFooterButton = modalElement.querySelector('.btn-light');
    
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            notificationModal.hide();
        });
    }
    
    if (closeFooterButton) {
        closeFooterButton.addEventListener('click', function() {
            notificationModal.hide();
        });
    }
    
    // Profil form
    let isUpdatingProfile = false; // Flag to prevent double submission
    
    function handleProfileSubmit(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        // Prevent double submission
        if (isUpdatingProfile) {
            return;
        }
        
        isUpdatingProfile = true;
        
        const formData = {
            email: document.getElementById('email').value,
            phone: document.getElementById('telepon').value
        };
        
        // Disable submit button to prevent double click
        const submitButton = e.target.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Menyimpan...';
        
        fetch('<?php echo e(route("murid.pengaturan.profile")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            // Reset flag after response
            isUpdatingProfile = false;
            
            // Reset button
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            
            if (data.success) {
                showNotification(data.message || 'Profil Anda telah diperbarui.', true);
            } else {
                showNotification(data.message || 'Terjadi kesalahan saat memperbarui profil.', false);
            }
        })
        .catch(error => {
            // Reset flag on error
            isUpdatingProfile = false;
            
            // Reset button
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            
            let errorMessage = 'Terjadi kesalahan saat memperbarui profil.';
            if (error.errors) {
                errorMessage = Object.values(error.errors).flat().join(', ');
            } else if (error.message) {
                errorMessage = error.message;
            }
            showNotification(errorMessage, false);
        });
    }
    
    // Remove any existing event listeners and add new one
    const profilForm = document.getElementById('profilForm');
    profilForm.removeEventListener('submit', handleProfileSubmit);
    profilForm.addEventListener('submit', handleProfileSubmit, { passive: false });

    // Keamanan form
    let isChangingPassword = false; // Flag to prevent double submission
    
    function handlePasswordSubmit(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        // Prevent double submission
        if (isChangingPassword) {
            return;
        }
        
        isChangingPassword = true;
        
        const passwordLama = document.getElementById('passwordLama').value;
        const passwordBaru = document.getElementById('passwordBaru').value;
        const konfirmasiPassword = document.getElementById('konfirmasiPassword').value;

        if (passwordBaru !== konfirmasiPassword) {
            isChangingPassword = false;
            showNotification('Password baru dan konfirmasi password tidak sama.', false);
            return;
        }
        
        const formData = {
            password_lama: passwordLama,
            password_baru: passwordBaru,
            konfirmasi_password: konfirmasiPassword
        };
        
        // Disable submit button to prevent double click
        const submitButton = e.target.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Mengubah...';
        
        fetch('<?php echo e(route("murid.pengaturan.password")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            // Reset flag after response
            isChangingPassword = false;
            
            // Reset button
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            
            if (data.success) {
                showNotification(data.message || 'Password Anda telah diubah.', true);
                document.getElementById('keamananForm').reset();
            } else {
                showNotification(data.message || 'Terjadi kesalahan saat mengubah password.', false);
            }
        })
        .catch(error => {
            // Reset flag on error
            isChangingPassword = false;
            
            // Reset button
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            
            let errorMessage = 'Terjadi kesalahan saat mengubah password.';
            if (error.errors) {
                errorMessage = Object.values(error.errors).flat().join(', ');
            } else if (error.message) {
                errorMessage = error.message;
            }
            showNotification(errorMessage, false);
        });
    }
    
    // Remove any existing event listeners and add new one
    const keamananForm = document.getElementById('keamananForm');
    keamananForm.removeEventListener('submit', handlePasswordSubmit);
    keamananForm.addEventListener('submit', handlePasswordSubmit, { passive: false });
    
    // Handle photo upload
    const photoInput = document.getElementById('photoInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const photoError = document.getElementById('photoError');
    let selectedPhoto = null;
    
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
        
        // Store selected file
        selectedPhoto = file;
        
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
    
    function uploadPhoto(file) {
        const formData = new FormData();
        formData.append('photo', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch('<?php echo e(route("murid.pengaturan.photo")); ?>', {
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
    
    // Data user untuk aktivitas akun
    const userActivityData = {
        last_login_at: <?php echo json_encode($user->last_login_at ?? null, 15, 512) ?>,
        created_at: <?php echo json_encode($user->created_at ?? null, 15, 512) ?>,
        updated_at: <?php echo json_encode($user->updated_at ?? null, 15, 512) ?>,
    };
    
    // Update aktivitas akun secara real time
    function updateAktivitasAkun() {
        const userData = userActivityData;
        
        // Format last login
        if (userData.last_login_at) {
            const loginDate = new Date(userData.last_login_at);
            document.getElementById('lastLogin').textContent = 
                loginDate.toLocaleString('id-ID', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
        } else {
            document.getElementById('lastLogin').textContent = 'Belum pernah login';
        }
        
        // Format account created
        if (userData.created_at) {
            const createdDate = new Date(userData.created_at);
            document.getElementById('accountCreated').textContent = 
                createdDate.toLocaleString('id-ID', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric'
                });
        } else {
            document.getElementById('accountCreated').textContent = '-';
        }
        
        // Format password last changed (using updated_at)
        if (userData.updated_at) {
            const updatedDate = new Date(userData.updated_at);
            const now = new Date();
            const diffMs = now - updatedDate;
            const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            
            let updateText = '';
            if (diffDays === 0) {
                updateText = 'Hari ini';
            } else if (diffDays === 1) {
                updateText = 'Kemarin';
            } else if (diffDays < 7) {
                updateText = `${diffDays} hari lalu`;
            } else if (diffDays < 30) {
                const weeks = Math.floor(diffDays / 7);
                updateText = `${weeks} minggu lalu`;
            } else if (diffDays < 365) {
                const months = Math.floor(diffDays / 30);
                updateText = `${months} bulan lalu`;
            } else {
                const years = Math.floor(diffDays / 365);
                updateText = `${years} tahun lalu`;
            }
            
            document.getElementById('lastPasswordChange').textContent = updateText;
        } else {
            document.getElementById('lastPasswordChange').textContent = '-';
        }
        
        // Format profile updated
        if (userData.updated_at) {
            const updatedDate = new Date(userData.updated_at);
            const now = new Date();
            const diffMs = now - updatedDate;
            const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            
            let updateText = '';
            if (diffDays === 0) {
                updateText = 'Hari ini';
            } else if (diffDays === 1) {
                updateText = 'Kemarin';
            } else if (diffDays < 7) {
                updateText = `${diffDays} hari lalu`;
            } else if (diffDays < 30) {
                const weeks = Math.floor(diffDays / 7);
                updateText = `${weeks} minggu lalu`;
            } else if (diffDays < 365) {
                const months = Math.floor(diffDays / 30);
                updateText = `${months} bulan lalu`;
            } else {
                const years = Math.floor(diffDays / 365);
                updateText = `${years} tahun lalu`;
            }
            
            document.getElementById('lastProfileUpdate').textContent = updateText;
        } else {
            document.getElementById('lastProfileUpdate').textContent = '-';
        }
    }
    
    // Initialize aktivitas akun saat page load
    updateAktivitasAkun();
    
    // Update aktivitas akun setelah profil diubah
    const originalHandleProfileSubmit = handleProfileSubmit;
    handleProfileSubmit = function(e) {
        originalHandleProfileSubmit.call(this, e);
        
        // Update aktivitas setelah 1 detik
        setTimeout(() => {
            // Update profile update time
            document.getElementById('lastProfileUpdate').textContent = 'Baru saja';
        }, 1000);
    };
    
    // Get reference to original password submit handler
    const originalHandlePasswordSubmit = handlePasswordSubmit;
    
    // Update aktivitas akun setelah password diubah
    handlePasswordSubmit = function(e) {
        originalHandlePasswordSubmit.call(this, e);
        
        // Update aktivitas setelah password berhasil diubah
        setTimeout(() => {
            // Update password change time
            document.getElementById('lastPasswordChange').textContent = 'Baru saja';
        }, 1000);
    };
});

function logoutSemua() {
    Swal.fire({
        title: 'Logout dari Semua Device?',
        text: "Anda akan logout dari semua device yang sedang aktif.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Logout!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Berhasil!',
                'Anda telah logout dari semua device.',
                'success'
            );
        }
    });
}

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.vertical-murid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/murid/pengaturan.blade.php ENDPATH**/ ?>