// Admin Pengaturan JavaScript
// This file handles all settings functionality including profile updates, photo uploads, and database statistics

(function () {
    "use strict";

    // Initialize notification modal
    let notificationModalInstance = null;
    
    function getNotificationModal() {
        if (!notificationModalInstance) {
            const modalElement = document.getElementById('notificationModal');
            if (modalElement) {
                notificationModalInstance = new bootstrap.Modal(modalElement);
            }
        }
        return notificationModalInstance;
    }

    // Load database statistics on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadDatabaseStats();
        
        // Initialize notification modal
        const modalElement = document.getElementById('notificationModal');
        if (modalElement) {
            getNotificationModal();
            
            // Reload page when notification modal is closed
            modalElement.addEventListener('hidden.bs.modal', function() {
                window.location.reload();
            });
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
        
        // Profile form functions
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                updateProfile();
            });
        }
        
        // Add real-time password confirmation validation
        const newPasswordConfirmation = document.getElementById('new_password_confirmation');
        const newPassword = document.getElementById('new_password');
        
        if (newPasswordConfirmation) {
            newPasswordConfirmation.addEventListener('input', function() {
                validatePasswordConfirmation();
            });
        }
        
        if (newPassword) {
            newPassword.addEventListener('input', function() {
                validatePasswordConfirmation();
            });
        }
        
        // Reset form button
        const resetFormBtn = document.getElementById('resetFormBtn');
        if (resetFormBtn) {
            resetFormBtn.addEventListener('click', function() {
                resetForm();
            });
        }
    });

    function uploadPhoto(file) {
        const formData = new FormData();
        formData.append('photo', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        const avatarPreview = document.getElementById('avatarPreview');
        
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
                if (avatarPreview) {
                    avatarPreview.src = '/images/users/avatar-1.jpg';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat mengunggah foto.');
            // Revert to original photo on error
            if (avatarPreview) {
                avatarPreview.src = '/images/users/avatar-1.jpg';
            }
        });
    }

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
                const totalUsersEl = document.getElementById('totalUsers');
                const totalTeachersEl = document.getElementById('totalTeachers');
                const totalStudentsEl = document.getElementById('totalStudents');
                const totalAnnouncementsEl = document.getElementById('totalAnnouncements');
                
                if (totalUsersEl) totalUsersEl.textContent = data.stats.users || 0;
                if (totalTeachersEl) totalTeachersEl.textContent = data.stats.teachers || 0;
                if (totalStudentsEl) totalStudentsEl.textContent = data.stats.students || 0;
                if (totalAnnouncementsEl) totalAnnouncementsEl.textContent = data.stats.announcements || 0;
            }
        })
        .catch(error => {
            console.error('Error loading database stats:', error);
            // Set default values if API fails
            const totalUsersEl = document.getElementById('totalUsers');
            const totalTeachersEl = document.getElementById('totalTeachers');
            const totalStudentsEl = document.getElementById('totalStudents');
            const totalAnnouncementsEl = document.getElementById('totalAnnouncements');
            
            if (totalUsersEl) totalUsersEl.textContent = '0';
            if (totalTeachersEl) totalTeachersEl.textContent = '0';
            if (totalStudentsEl) totalStudentsEl.textContent = '0';
            if (totalAnnouncementsEl) totalAnnouncementsEl.textContent = '0';
        });
    }

    function validatePasswordConfirmation() {
        const newPasswordEl = document.getElementById('new_password');
        const confirmPasswordEl = document.getElementById('new_password_confirmation');
        const errorElement = document.getElementById('new_password_confirmation_error');

        if (!newPasswordEl || !confirmPasswordEl) {
            return;
        }

        const newPassword = newPasswordEl.value;
        const confirmPassword = confirmPasswordEl.value;

        if (newPassword && confirmPassword) {
            if (newPassword !== confirmPassword) {
                confirmPasswordEl.classList.add('is-invalid');
                if (errorElement) {
                    errorElement.textContent = 'Konfirmasi password tidak sesuai';
                }
            } else {
                confirmPasswordEl.classList.remove('is-invalid');
                if (errorElement) {
                    errorElement.textContent = '';
                }
            }
        } else {
            confirmPasswordEl.classList.remove('is-invalid');
            if (errorElement) {
                errorElement.textContent = '';
            }
        }
    }

    function updateProfile() {
        const form = document.getElementById('profileForm');
        if (!form) {
            return;
        }
        
        const formData = new FormData(form);
        
        // Validate password confirmation before submit
        const newPasswordEl = document.getElementById('new_password');
        const confirmPasswordEl = document.getElementById('new_password_confirmation');
        
        if (newPasswordEl && confirmPasswordEl) {
            const newPassword = newPasswordEl.value;
            const confirmPassword = confirmPasswordEl.value;
            
            if (newPassword && confirmPassword && newPassword !== confirmPassword) {
                showAlert('error', 'Konfirmasi password tidak sesuai');
                return;
            }
        }
        
        // Clear previous validation errors
        clearValidationErrors();
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...';
            submitBtn.disabled = true;
        }

        fetch('/admin/pengaturan/profile', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
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
                showAlert('success', data.message || 'Profil berhasil diperbarui');
                // Clear password fields
                const currentPasswordEl = document.getElementById('current_password');
                const newPasswordEl = document.getElementById('new_password');
                const confirmPasswordEl = document.getElementById('new_password_confirmation');
                
                if (currentPasswordEl) currentPasswordEl.value = '';
                if (newPasswordEl) newPasswordEl.value = '';
                if (confirmPasswordEl) confirmPasswordEl.value = '';
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
            
            // Handle validation errors
            if (error.errors) {
                showValidationErrors(error.errors);
            } else if (error.message) {
                showAlert('error', error.message);
            } else {
                showAlert('error', 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.');
            }
        })
        .finally(() => {
            // Reset button state
            if (submitBtn) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }

    function resetForm() {
        if (confirm('Apakah Anda yakin ingin mereset form ke nilai awal?')) {
            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.reset();
            }
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
        // Clear previous errors first
        clearValidationErrors();
        
        if (!errors || typeof errors !== 'object') {
            return;
        }
        
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            const errorElement = document.getElementById(field + '_error');
            
            if (input && errorElement) {
                input.classList.add('is-invalid');
                // Handle both array and string error messages
                const errorMessage = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                errorElement.textContent = errorMessage;
            }
        });
        
        // Show alert with first error message if available
        const firstError = Object.values(errors)[0];
        if (firstError) {
            const firstErrorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
            showAlert('error', firstErrorMessage);
        }
    }

    function showAlert(type, message) {
        const notificationModal = getNotificationModal();
        const notificationModalLabel = document.getElementById('notificationModalLabel');
        const notificationMessage = document.getElementById('notificationMessage');
        
        if (!notificationModal || !notificationModalLabel || !notificationMessage) {
            console.error('Notification modal elements not found');
            // Fallback to alert if modal not found
            alert(message);
            return;
        }
        
        // Set title based on type
        const title = type === 'success' ? 'Berhasil' : 'Gagal';
        notificationModalLabel.textContent = title;
        
        // Set message
        notificationMessage.textContent = message;
        
        // Show modal
        notificationModal.show();
    }

    // Make functions globally available
    window.resetForm = resetForm;
})();

