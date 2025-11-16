document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('notificationModal');
    if (!modalElement) return;
    
    const notificationModal = new bootstrap.Modal(modalElement);

    // Ensure close buttons work
    const closeButton = modalElement.querySelector('.btn-close');
    const closeFooterButton = modalElement.querySelector('.btn-light');
    
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            notificationModal.hide();
        });
    }
    
    if (closeFooterButton) {
        closeFooterButton.addEventListener('click', () => {
            notificationModal.hide();
        });
    }
    
    // Reload page when notification modal is closed
    modalElement.addEventListener('hidden.bs.modal', function() {
        window.location.reload();
    });

    function showNotification(message, isSuccess = true) {
        document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
        document.getElementById('notificationMessage').innerText = message;
        notificationModal.show();
    }

    // Initialize photo upload button
    const photoUploadBtn = document.getElementById('photoUploadBtn');
    const photoInput = document.getElementById('photoInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const photoError = document.getElementById('photoError');
    
    if (photoUploadBtn && photoInput) {
        photoUploadBtn.addEventListener('click', function() {
            photoInput.click();
        });
    }
    
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
        
        fetch(window.pengaturanGuruRoutes.photo, {
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
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                // Check if response is ok
                if (!response.ok) {
                    // Try to parse error response
                    return response.text().then(text => {
                        try {
                            const json = JSON.parse(text);
                            // Ensure errors object exists
                            if (response.status === 422 && !json.errors) {
                                json.errors = {};
                            }
                            throw json;
                        } catch (e) {
                            // If parsing failed or e is not the parsed JSON, check if e is already an object
                            if (e && typeof e === 'object' && e.errors !== undefined) {
                                throw e;
                            }
                            // If not JSON, throw with status
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
                if (data.success) {
                    showNotification(data.message, true);
                    // Reset form if needed
                    if (data.reset_form) {
                        this.reset();
                    }
                } else {
                    // Show error message from server
                    let errorMessage = data.message || 'Terjadi kesalahan saat memperbarui profil.';
                    if (data.errors) {
                        // Get first error message
                        const firstError = Object.values(data.errors)[0];
                        errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                    }
                    showNotification(errorMessage, false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'Terjadi kesalahan saat memproses permintaan.';
                if (error.errors) {
                    // Get first error message
                    const firstError = Object.values(error.errors)[0];
                    errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                } else if (error.message) {
                    errorMessage = error.message;
                }
                showNotification(errorMessage, false);
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
            e.stopImmediatePropagation();
            
            console.log('Password form submitted');
            
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
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                // Check if response is ok
                if (!response.ok) {
                    // Try to parse error response
                    return response.text().then(text => {
                        try {
                            const json = JSON.parse(text);
                            // Ensure errors object exists
                            if (response.status === 422 && !json.errors) {
                                json.errors = {};
                            }
                            throw json;
                        } catch (e) {
                            // If parsing failed or e is not the parsed JSON, check if e is already an object
                            if (e && typeof e === 'object' && e.errors !== undefined) {
                                throw e;
                            }
                            // If not JSON, throw with status
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
                if (data.success) {
                    showNotification(data.message, true);
                    // Reset form
                    this.reset();
                } else {
                    // Show error message from server
                    let errorMessage = 'Terjadi kesalahan saat mengubah password.';
                    
                    // Prioritize errors object over message
                    if (data.errors && Object.keys(data.errors).length > 0) {
                        // Prioritize specific error messages
                        if (data.errors.current_password) {
                            errorMessage = Array.isArray(data.errors.current_password) 
                                ? data.errors.current_password[0] 
                                : data.errors.current_password;
                        } else if (data.errors.new_password) {
                            errorMessage = Array.isArray(data.errors.new_password) 
                                ? data.errors.new_password[0] 
                                : data.errors.new_password;
                        } else if (data.errors.password) {
                            errorMessage = Array.isArray(data.errors.password) 
                                ? data.errors.password[0] 
                                : data.errors.password;
                        } else {
                            // Get first error message
                            const firstError = Object.values(data.errors)[0];
                            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                        }
                    } else if (data.message) {
                        // Use message if errors object is not available
                        errorMessage = data.message;
                    }
                    
                    showNotification(errorMessage, false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'Terjadi kesalahan saat memproses permintaan.';
                if (error.errors) {
                    // Prioritize specific error messages
                    if (error.errors.current_password) {
                        errorMessage = Array.isArray(error.errors.current_password) 
                            ? error.errors.current_password[0] 
                            : error.errors.current_password;
                    } else if (error.errors.new_password) {
                        errorMessage = Array.isArray(error.errors.new_password) 
                            ? error.errors.new_password[0] 
                            : error.errors.new_password;
                    } else if (error.errors.password) {
                        errorMessage = Array.isArray(error.errors.password) 
                            ? error.errors.password[0] 
                            : error.errors.password;
                    } else {
                        // Get first error message
                        const firstError = Object.values(error.errors)[0];
                        errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                    }
                } else if (error.message) {
                    errorMessage = error.message;
                }
                showNotification(errorMessage, false);
            })
            .finally(() => {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
        console.log('Password form event listener attached');
    } else {
        console.error('Password form not found! Form ID: passwordForm');
    }

    // Update Aktivitas Akun
    function updateAktivitasAkun() {
        const userData = window.pengaturanGuruData?.userActivity || {};
        
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
        
        // Format last profile update (using updated_at)
        const lastProfileUpdateEl = document.getElementById('lastProfileUpdate');
        if (lastProfileUpdateEl) {
            if (userData.updated_at) {
                const updatedDate = new Date(userData.updated_at);
                lastProfileUpdateEl.textContent = 
                    updatedDate.toLocaleString('id-ID', { 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
            } else {
                lastProfileUpdateEl.textContent = '-';
            }
        }
    }

    // Initialize aktivitas akun on page load
    updateAktivitasAkun();
});

