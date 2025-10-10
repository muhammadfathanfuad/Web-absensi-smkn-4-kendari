/*
Template Name: Taplox- Responsive Bootstrap 5 Admin Dashboard
Author: Stackbros
File: form - File Upload js
*/

// Handle import jadwal form
document.addEventListener('DOMContentLoaded', function() {
    const importForm = document.getElementById('importJadwalForm');
    if (importForm) {
        importForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;

            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.textContent = 'Mengimport...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification(data.message, 'success');
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                    modal.hide();
                    // Reset form
                    this.reset();
                    // Optionally reload page or update table
                    location.reload();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat mengimport.', 'error');
            })
            .finally(() => {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
    }
});

function showNotification(message, type) {
    const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
    const notificationMessage = document.getElementById('notificationMessage');
    notificationMessage.textContent = message;
    notificationMessage.className = type === 'success' ? 'text-success' : 'text-danger';
    notificationModal.show();
}
