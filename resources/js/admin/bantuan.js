// Admin Bantuan JavaScript
// This file handles all help/assistance functionality including contact form submission and utility functions

(function () {
    "use strict";

    document.addEventListener('DOMContentLoaded', function() {
        // Contact form submission
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const subject = document.getElementById('contact_subject').value;
                const message = document.getElementById('contact_message').value;
                
                if (!subject || !message) {
                    showAlert('error', 'Mohon lengkapi semua field');
                    return;
                }
                
                showLoading('Mengirim pesan...');
                
                fetch('/admin/bantuan/send-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        subject: subject,
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showAlert('success', 'Pesan berhasil dikirim');
                        contactForm.reset();
                        const contactModal = bootstrap.Modal.getInstance(document.getElementById('contactModal'));
                        if (contactModal) {
                            contactModal.hide();
                        }
                    } else {
                        showAlert('error', 'Gagal mengirim pesan');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan saat mengirim pesan');
                });
            });
        }
    });

    // Utility functions
    function showLoading(message) {
        const loadingHtml = `
            <div class="d-flex align-items-center">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                ${message}
            </div>
        `;
        showAlert('info', loadingHtml);
    }

    function hideLoading() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.innerHTML.includes('spinner-border')) {
                alert.remove();
            }
        });
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 
                          type === 'info' ? 'alert-info' : 'alert-warning';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const content = document.querySelector('.page-content .container-fluid');
        if (content) {
            content.insertAdjacentHTML('afterbegin', alertHtml);
            
            if (type !== 'info') {
                setTimeout(() => {
                    const alert = content.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            }
        }
    }

    // Make functions globally available if needed
    window.showAlert = showAlert;
    window.showLoading = showLoading;
    window.hideLoading = hideLoading;
})();

