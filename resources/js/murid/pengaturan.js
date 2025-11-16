// Pengaturan JavaScript
// This file handles settings functionality for students

document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('notificationModal');
    if (!modalElement) return;
    
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
    
    // Listen for modal hidden event to ensure cleanup and reload page
    modalElement.addEventListener('hidden.bs.modal', function() {
        cleanupModal();
        isModalOpen = false;
        // Reload page when notification modal is closed
        window.location.reload();
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
    
    // Get routes from window object
    const routes = window.pengaturanMuridRoutes || {};
    
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
        
        const emailEl = document.getElementById('email');
        const phoneEl = document.getElementById('phone');
        
        if (!emailEl) {
            console.error('Email field not found!');
            isUpdatingProfile = false;
            return;
        }
        
        const formData = {
            email: emailEl.value,
            phone: phoneEl ? phoneEl.value : null
        };
        
        // Disable submit button to prevent double click
        const submitButton = e.target.querySelector('button[type="submit"]');
        if (!submitButton) {
            isUpdatingProfile = false;
            return;
        }
        
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Menyimpan...';
        
        const profileRoute = routes.profile;
        if (!profileRoute) {
            isUpdatingProfile = false;
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            showNotification('Error: Route tidak ditemukan.', false);
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        fetch(profileRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            // Check if response is ok
            if (!response.ok) {
                // Try to parse error response
                return response.text().then(text => {
                    try {
                        const json = JSON.parse(text);
                        throw json;
                    } catch (e) {
                        // If not JSON, throw with status
                        if (e && typeof e === 'object' && e.errors !== undefined) {
                            throw e;
                        }
                        throw {
                            status: response.status,
                            message: response.status === 422 ? 'Validasi gagal' : 'Terjadi kesalahan pada server',
                            errors: response.status === 422 ? {} : null
                        };
                    }
                });
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
    if (profilForm) {
        profilForm.removeEventListener('submit', handleProfileSubmit);
        profilForm.addEventListener('submit', handleProfileSubmit, { passive: false });
    }

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
        
        const currentPasswordEl = document.getElementById('current_password');
        const newPasswordEl = document.getElementById('new_password');
        const confirmPasswordEl = document.getElementById('new_password_confirmation');
        
        if (!currentPasswordEl || !newPasswordEl || !confirmPasswordEl) {
            console.error('Password fields not found!', {
                current_password: !!currentPasswordEl,
                new_password: !!newPasswordEl,
                new_password_confirmation: !!confirmPasswordEl
            });
            isChangingPassword = false;
            return;
        }
        
        const currentPassword = currentPasswordEl.value;
        const newPassword = newPasswordEl.value;
        const confirmPassword = confirmPasswordEl.value;

        if (newPassword !== confirmPassword) {
            isChangingPassword = false;
            showNotification('Password baru dan konfirmasi password tidak sama.', false);
            return;
        }
        
        const formData = {
            password_lama: currentPassword,
            password_baru: newPassword,
            konfirmasi_password: confirmPassword
        };
        
        // Disable submit button to prevent double click
        const submitButton = e.target.querySelector('button[type="submit"]');
        if (!submitButton) {
            isChangingPassword = false;
            return;
        }
        
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Mengubah...';
        
        const passwordRoute = routes.password;
        if (!passwordRoute) {
            isChangingPassword = false;
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            showNotification('Error: Route tidak ditemukan.', false);
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        fetch(passwordRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            // Check if response is ok
            if (!response.ok) {
                // Try to parse error response
                return response.text().then(text => {
                    try {
                        const json = JSON.parse(text);
                        throw json;
                    } catch (e) {
                        // If not JSON, throw with status
                        if (e && typeof e === 'object' && e.errors !== undefined) {
                            throw e;
                        }
                        throw {
                            status: response.status,
                            message: response.status === 422 ? 'Validasi gagal' : 'Terjadi kesalahan pada server',
                            errors: response.status === 422 ? {} : null
                        };
                    }
                });
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
                const keamananForm = document.getElementById('keamananForm');
                if (keamananForm) {
                    keamananForm.reset();
                }
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
                // Prioritize specific error messages
                if (error.errors.password_lama) {
                    errorMessage = Array.isArray(error.errors.password_lama) 
                        ? error.errors.password_lama[0] 
                        : error.errors.password_lama;
                } else if (error.errors.konfirmasi_password) {
                    errorMessage = Array.isArray(error.errors.konfirmasi_password) 
                        ? error.errors.konfirmasi_password[0] 
                        : error.errors.konfirmasi_password;
                } else {
                    // Get first error message
                    const firstError = Object.values(error.errors)[0];
                    errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                }
            } else if (error.message) {
                errorMessage = error.message;
            }
            showNotification(errorMessage, false);
        });
    }
    
    // Remove any existing event listeners and add new one
    const keamananForm = document.getElementById('keamananForm');
    if (keamananForm) {
        keamananForm.removeEventListener('submit', handlePasswordSubmit);
        keamananForm.addEventListener('submit', handlePasswordSubmit, { passive: false });
        console.log('Password form event listener attached');
    } else {
        console.error('Password form not found! Form ID: keamananForm');
    }
    
    // Initialize photo upload button
    const photoUploadBtn = document.getElementById('photoUploadBtn');
    const photoInput = document.getElementById('photoInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const photoError = document.getElementById('photoError');
    let selectedPhoto = null;
    
    if (photoUploadBtn && photoInput) {
        photoUploadBtn.addEventListener('click', function() {
            photoInput.click();
        });
    }
    
    if (photoInput && avatarPreview && photoError) {
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
    }
    
    function uploadPhoto(file) {
        const photoRoute = routes.photo;
        if (!photoRoute) {
            showNotification('Error: Route tidak ditemukan.', false);
            return;
        }
        
        const formData = new FormData();
        formData.append('photo', file);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        formData.append('_token', csrfToken);
        
        fetch(photoRoute, {
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
                if (avatarPreview) {
                    avatarPreview.src = '/images/users/avatar-1.jpg';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat mengunggah foto.', false);
            // Revert to original photo on error
            if (avatarPreview) {
                avatarPreview.src = '/images/users/avatar-1.jpg';
            }
        });
    }
    
    // Get user activity data from window object
    const userActivityData = window.pengaturanMuridData?.userActivity || {};
    
    // Update aktivitas akun secara real time
    function updateAktivitasAkun() {
        const userData = userActivityData;
        
        // Format last login
        const lastLoginEl = document.getElementById('lastLogin');
        if (lastLoginEl) {
            if (userData.last_login_at) {
                const loginDate = new Date(userData.last_login_at);
                lastLoginEl.textContent = 
                    loginDate.toLocaleString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
            } else {
                lastLoginEl.textContent = 'Belum pernah login';
            }
        }
        
        // Format account created
        const accountCreatedEl = document.getElementById('accountCreated');
        if (accountCreatedEl) {
            if (userData.created_at) {
                const createdDate = new Date(userData.created_at);
                accountCreatedEl.textContent = 
                    createdDate.toLocaleString('id-ID', { 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric'
                    });
            } else {
                accountCreatedEl.textContent = '-';
            }
        }
        
        // Format password last changed (using updated_at)
        const lastPasswordChangeEl = document.getElementById('lastPasswordChange');
        if (lastPasswordChangeEl) {
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
                
                lastPasswordChangeEl.textContent = updateText;
            } else {
                lastPasswordChangeEl.textContent = '-';
            }
        }
        
        // Format profile updated
        const lastProfileUpdateEl = document.getElementById('lastProfileUpdate');
        if (lastProfileUpdateEl) {
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
                
                lastProfileUpdateEl.textContent = updateText;
            } else {
                lastProfileUpdateEl.textContent = '-';
            }
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
            const lastProfileUpdateEl = document.getElementById('lastProfileUpdate');
            if (lastProfileUpdateEl) {
                lastProfileUpdateEl.textContent = 'Baru saja';
            }
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
            const lastPasswordChangeEl = document.getElementById('lastPasswordChange');
            if (lastPasswordChangeEl) {
                lastPasswordChangeEl.textContent = 'Baru saja';
            }
        }, 1000);
    };
});

function logoutSemua() {
    if (typeof Swal === 'undefined') {
        alert('Logout dari Semua Device?');
        return;
    }
    
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

// Expose function to global scope for onclick handlers
window.logoutSemua = logoutSemua;

