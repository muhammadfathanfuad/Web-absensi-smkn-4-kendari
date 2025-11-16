// Delegasi JavaScript
// This file handles delegation functionality for students

document.addEventListener('DOMContentLoaded', function() {
    // Check if QRCode library is loaded
    if (typeof qrcode !== 'undefined') {
        window.QRCodeLoaded = true;
        window.QRCodeType = 'qrcode-generator';
    } else {
        window.QRCodeLoaded = false;
    }

    // Event listener untuk tombol konfirmasi stop session
    const confirmStopBtn = document.getElementById('confirmStopSessionBtnDelegasi');
    if (confirmStopBtn) {
        confirmStopBtn.addEventListener('click', function() {
            const sessionToken = document.getElementById('stopSessionTokenDelegasi').value;
            if (sessionToken) {
                stopAttendanceSessionDelegasi(sessionToken);
            }
        });
    }

    // Ensure close buttons work for notification modal
    const notificationModalDelegasi = document.getElementById('notificationModalDelegasi');
    const notificationModalDelegasiInstance = notificationModalDelegasi ? new bootstrap.Modal(notificationModalDelegasi) : null;
    
    if (notificationModalDelegasi) {
        const closeBtn = notificationModalDelegasi.querySelector('.btn-close');
        const dismissBtn = notificationModalDelegasi.querySelector('.btn-light');
        if (closeBtn) {
            closeBtn.onclick = () => notificationModalDelegasiInstance?.hide();
        }
        if (dismissBtn) {
            dismissBtn.onclick = () => notificationModalDelegasiInstance?.hide();
        }
    }
});

// Fungsi untuk membuka modal QR
function openQRModal(timetableId, subjectName, className, time) {
    const modal = new bootstrap.Modal(document.getElementById('qrModalDelegasi'));
    modal.show();

    // Reset state
    const qrContainer = document.getElementById('qrCodeContainerDelegasi');
    const qrInfoText = document.getElementById('qrInfoTextDelegasi');
    const stopBtn = document.getElementById('stopSessionBtnDelegasi');

    qrContainer.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary mb-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="text-muted">Membuat QR Code...</div>
        </div>
    `;

    qrInfoText.innerHTML = `
        <div class="alert alert-info mb-0">
            <strong>${subjectName}</strong> - ${className}<br>
            <small>Waktu: ${time}</small>
        </div>
    `;

    stopBtn.style.display = 'none';

    // Generate QR Code
    generateQRCodeDelegasi(timetableId, subjectName, className, time);
}

// Fungsi untuk generate QR Code
function generateQRCodeDelegasi(timetableId, subjectName, className, time) {
    const qrContainer = document.getElementById('qrCodeContainerDelegasi');
    const stopBtn = document.getElementById('stopSessionBtnDelegasi');

    // Get route from window object
    const generateQRRoute = window.delegasiMuridRoutes && window.delegasiMuridRoutes.generateQR;
    if (!generateQRRoute) {
        qrContainer.innerHTML = `
            <div class="text-center text-danger">
                <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                Error: Route tidak ditemukan.
            </div>
        `;
        return;
    }

    fetch(generateQRRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            timetable_id: timetableId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }

        window.currentSessionTokenDelegasi = data.session_id;

        if (!window.QRCodeLoaded || typeof qrcode === 'undefined') {
            qrContainer.innerHTML = `
                <div class="text-center text-danger">
                    <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                    Library QR Code tidak tersedia. Silakan refresh halaman.
                </div>
            `;
            return;
        }

        generateQRCodeWithLibrary(qrContainer, data, {
            cellSize: 4,
            margin: 2,
            darkColor: '#000000',
            lightColor: '#ffffff'
        })
        .then(() => {
            stopBtn.style.display = 'flex';
            stopBtn.onclick = () => {
                showStopSessionModalDelegasi(data.session_id);
            };
        })
        .catch((error) => {
            qrContainer.innerHTML = `
                <div class="text-center text-danger">
                    <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                    Gagal menghasilkan QR Code: ${error.message}
                </div>
            `;
        });
    })
    .catch(error => {
        qrContainer.innerHTML = `
            <div class="text-center text-danger">
                <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                ${error.message}
            </div>
        `;
    });
}

// Fungsi helper untuk generate QR code dengan library
function generateQRCodeWithLibrary(container, data, options = {}) {
    if (typeof qrcode === 'undefined') {
        return Promise.reject(new Error('qrcode-generator library not available'));
    }
    
    try {
        const qr = qrcode(0, 'M');
        qr.addData(JSON.stringify(data));
        qr.make();
        
        container.innerHTML = '';
        
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const cellSize = options.cellSize || 4;
        const margin = options.margin || 2;
        
        const size = qr.getModuleCount() * cellSize + margin * 2;
        canvas.width = size;
        canvas.height = size;
        
        ctx.fillStyle = options.lightColor || '#ffffff';
        ctx.fillRect(0, 0, size, size);
        
        ctx.fillStyle = options.darkColor || '#000000';
        for (let row = 0; row < qr.getModuleCount(); row++) {
            for (let col = 0; col < qr.getModuleCount(); col++) {
                if (qr.isDark(row, col)) {
                    ctx.fillRect(
                        col * cellSize + margin,
                        row * cellSize + margin,
                        cellSize,
                        cellSize
                    );
                }
            }
        }
        
        container.appendChild(canvas);
        return Promise.resolve();
    } catch (error) {
        return Promise.reject(error);
    }
}

// Fungsi untuk menampilkan modal konfirmasi stop session
function showStopSessionModalDelegasi(sessionToken) {
    if (!sessionToken) return;

    const hiddenInput = document.getElementById('stopSessionTokenDelegasi');
    if (hiddenInput) {
        hiddenInput.value = sessionToken;
    }

    const modal = new bootstrap.Modal(document.getElementById('stopSessionModalDelegasi'));
    modal.show();
}

// Fungsi untuk stop attendance session
function stopAttendanceSessionDelegasi(sessionToken) {
    if (!sessionToken) return;

    const confirmBtn = document.getElementById('confirmStopSessionBtnDelegasi');
    if (!confirmBtn) return;

    const originalText = confirmBtn.innerHTML;

    confirmBtn.innerHTML = `
        <div class="spinner-border spinner-border-sm me-2" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        Menghentikan...
    `;
    confirmBtn.disabled = true;

    // Get route from window object
    const stopSessionRoute = window.delegasiMuridRoutes && window.delegasiMuridRoutes.stopSession;
    if (!stopSessionRoute) {
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
        showNotificationDelegasi('Error: Route tidak ditemukan.', 'error');
        return;
    }

    fetch(stopSessionRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            session_token: sessionToken
        })
    })
    .then(response => response.json())
    .then(data => {
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;

        const stopModal = bootstrap.Modal.getInstance(document.getElementById('stopSessionModalDelegasi'));
        stopModal.hide();

        if (data.success) {
            showNotificationDelegasi('Sesi absensi berhasil dihentikan', 'success');
            
            // Tutup modal QR juga
            const qrModal = bootstrap.Modal.getInstance(document.getElementById('qrModalDelegasi'));
            if (qrModal) {
                qrModal.hide();
            }
            
            // Reset state
            document.getElementById('qrCodeContainerDelegasi').innerHTML = `
                <div class="text-muted text-center">
                    <iconify-icon icon="solar:qr-code-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                    QR Code akan muncul di sini...
                </div>
            `;
            document.getElementById('stopSessionBtnDelegasi').style.display = 'none';
        } else {
            showNotificationDelegasi('Gagal menghentikan sesi: ' + (data.error || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
        showNotificationDelegasi('Terjadi kesalahan saat menghentikan sesi: ' + error.message, 'error');
    });
}

// Fungsi untuk menampilkan notifikasi
function showNotificationDelegasi(message, type = 'info') {
    const modalEl = document.getElementById('notificationModalDelegasi');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const modalLabel = document.getElementById('notificationModalDelegasiLabel');
    const modalMessage = document.getElementById('notificationMessageDelegasi');
    const modalIcon = document.getElementById('notificationIconDelegasi');
    
    if (type === 'success') {
        modalLabel.textContent = 'Berhasil';
        modalIcon.setAttribute('icon', 'solar:check-circle-outline');
        modalIcon.className = 'fs-48 text-success';
    } else if (type === 'error') {
        modalLabel.textContent = 'Gagal';
        modalIcon.setAttribute('icon', 'solar:danger-circle-outline');
        modalIcon.className = 'fs-48 text-danger';
    } else {
        modalLabel.textContent = 'Informasi';
        modalIcon.setAttribute('icon', 'solar:info-circle-outline');
        modalIcon.className = 'fs-48 text-info';
    }
    
    modalMessage.textContent = message;

    const closeBtn = modalEl.querySelector('.btn-close');
    const dismissBtn = modalEl.querySelector('.btn-light');
    if (closeBtn) {
        closeBtn.onclick = () => modal.hide();
    }
    if (dismissBtn) {
        dismissBtn.onclick = () => modal.hide();
    }

    modal.show();
}

// Expose functions to global scope for onclick handlers
window.openQRModal = openQRModal;

