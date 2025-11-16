// Pastikan QRCode library sudah ter-load
document.addEventListener('DOMContentLoaded', function() {
    // Check if timetable_id is in URL (for delegation)
    const urlParams = new URLSearchParams(window.location.search);
    const timetableId = urlParams.get('timetable_id');
    
    if (timetableId) {
        // Find the matching dropdown item and trigger click
        const dropdownItems = document.querySelectorAll('.dropdown-item[data-timetable-id]');
        const targetItem = Array.from(dropdownItems).find(item => 
            item.getAttribute('data-timetable-id') === timetableId
        );
        
        if (targetItem) {
            // Wait a bit for the DOM to fully initialize
            setTimeout(() => {
                targetItem.click();
            }, 500);
        } else {
            alert('Jadwal yang dipilih tidak tersedia atau bukan jadwal hari ini.');
        }
    }
    
    // Cek apakah qrcode-generator library tersedia
    if (typeof qrcode !== 'undefined') {
        window.QRCodeLoaded = true;
        window.QRCodeType = 'qrcode-generator';
    } else {
        window.QRCodeLoaded = false;
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Pastikan tombol stop session tersembunyi saat halaman dimuat
    const stopSessionBtn = document.getElementById('stopSession');
    if (stopSessionBtn) {
        stopSessionBtn.classList.remove('show');
    }
    
    const dropdownmapel = document.getElementById('dropdownmapel');
    const dropdownItems = document.querySelectorAll('#dropdownmapel + .dropdown-menu .dropdown-item[data-timetable-id]');
    
    dropdownItems.forEach(function (item) {
        item.addEventListener('click', function () {
            const subjectName = item.getAttribute('data-subject-name');
            const className = item.getAttribute('data-class-name');
            const timetableId = item.getAttribute('data-timetable-id');
            const time = item.getAttribute('data-time');

            // Update teks pada tombol dropdown
            dropdownmapel.textContent = `${subjectName} - ${className}`;

            // Simpan ID jadwal ke hidden input
            const timetableIdInput = document.getElementById('timetable_id');
            timetableIdInput.value = timetableId;

            // Panggil fungsi generate QR Code
            generateQRCode(timetableId, subjectName, className, time);
        });
    });

    // Event listener untuk tombol konfirmasi stop session
    const confirmStopBtn = document.getElementById('confirmStopSessionButton');
    if (confirmStopBtn) {
        confirmStopBtn.addEventListener('click', function() {
            const sessionToken = document.getElementById('stopSessionToken').value;
            
            if (sessionToken) {
                stopAttendanceSession(sessionToken);
            }
        });
    }

    // Event listener untuk modal notifikasi
    const notificationModalEl = document.getElementById('notificationModal');
    if (notificationModalEl) {
        const notificationModal = new bootstrap.Modal(notificationModalEl);
        
        // Ensure close buttons work - remove focus before hiding to prevent accessibility warning
        const closeBtn = notificationModalEl.querySelector('.btn-close');
        const dismissBtn = notificationModalEl.querySelector('.btn-light');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', (e) => {
                e.target.blur(); // Remove focus before hiding
                notificationModal.hide();
            });
        }
        
        if (dismissBtn) {
            dismissBtn.addEventListener('click', (e) => {
                e.target.blur(); // Remove focus before hiding
                notificationModal.hide();
            });
        }
        
        // Also handle when modal is hidden via Bootstrap events
        notificationModalEl.addEventListener('hidden.bs.modal', function() {
            // Remove focus from any focused element inside modal
            const focusedElement = this.querySelector(':focus');
            if (focusedElement) {
                focusedElement.blur();
            }
        });
    }
});

// Fungsi untuk reset dropdown
function resetDropdown() {
    const dropdownmapel = document.getElementById('dropdownmapel');
    const timetableIdInput = document.getElementById('timetable_id');
    const qrCodeContainer = document.getElementById('qrcode');
    const stopSessionBtn = document.getElementById('stopSession');
    const qrInfoText = document.getElementById('qrInfoText');

    // Hentikan polling jika ada
    if (window.scanResultsInterval) {
        clearInterval(window.scanResultsInterval);
        window.scanResultsInterval = null;
    }

    // Reset teks dropdown
    if (dropdownmapel) {
        dropdownmapel.textContent = 'Pilih Mata Pelajaran';
    }
    
    // Reset hidden input
    if (timetableIdInput) {
        timetableIdInput.value = '';
    }
    
    // Sembunyikan QR Code
    if (qrCodeContainer) {
        qrCodeContainer.style.display = 'none';
        qrCodeContainer.innerHTML = `
            <div class="text-muted text-center">
                <iconify-icon icon="solar:qr-code-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                QR Code akan muncul di sini...
            </div>
        `;
    }
    
    // Sembunyikan tombol stop session
    if (stopSessionBtn) {
        stopSessionBtn.classList.remove('show');
    }
    
    // Reset info text
    if (qrInfoText) {
        qrInfoText.textContent = '';
    }
    
    // Reset session token
    window.currentSessionToken = null;
    
    // Reset tombol konfirmasi stop session
    const confirmStopBtn = document.getElementById('confirmStopSessionButton');
    if (confirmStopBtn) {
        confirmStopBtn.innerHTML = `
            <iconify-icon icon="solar:stop-circle-outline" class="fs-16 me-2"></iconify-icon>
            Ya, Hentikan Sesi
        `;
        confirmStopBtn.disabled = false;
    }
    
    // Reset hidden input session token
    const stopSessionTokenInput = document.getElementById('stopSessionToken');
    if (stopSessionTokenInput) {
        stopSessionTokenInput.value = '';
    }
    
    // Reset tabel hasil scan
    const tbody = document.querySelector('#scan-results-table tbody');
    if (tbody) {
        tbody.innerHTML = `
            <tr id="initial-message-row">
                <td colspan="5" class="text-center py-4">
                    <div class="text-muted text-center">
                        <iconify-icon icon="solar:list-check-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                        Silakan pilih mata pelajaran untuk melihat data.
                    </div>
                </td>
            </tr>
        `;
    }
}

// Fungsi helper untuk generate QR code dengan qrcode-generator
function generateQRCodeWithLibrary(container, data, options = {}) {
    if (typeof qrcode === 'undefined') {
        return Promise.reject(new Error('qrcode-generator library not available'));
    }
    
    try {
        // Pastikan data adalah object yang valid
        if (!data || typeof data !== 'object') {
            return Promise.reject(new Error('Data must be a valid object'));
        }
        
        // Stringify data dengan format yang konsisten
        const jsonString = JSON.stringify(data);
        
        const qr = qrcode(0, 'M');
        qr.addData(jsonString);
        qr.make();
        
        // Clear container
        container.innerHTML = '';
        
        // Create canvas
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const cellSize = options.cellSize || 4;
        const margin = options.margin || 2;
        
        const size = qr.getModuleCount() * cellSize + margin * 2;
        canvas.width = size;
        canvas.height = size;
        
        // Fill background
        ctx.fillStyle = options.lightColor || '#ffffff';
        ctx.fillRect(0, 0, size, size);
        
        // Draw QR code
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

// Fungsi untuk generate QR Code
function generateQRCode(timetableId, subjectName, className, time) {
    if (!timetableId) {
        return;
    }

    const qrCodeContainer = document.getElementById('qrcode');
    const qrInfoText = document.getElementById('qrInfoText');
    const stopSessionBtn = document.getElementById('stopSession');
    
    if (!qrCodeContainer || !qrInfoText) {
        return;
    }
    
    // Tampilkan loading state
    qrCodeContainer.style.display = 'flex';
    qrCodeContainer.innerHTML = `
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

    // Get routes from window object (injected from blade)
    const generateQRRoute = window.scanQRRoutes?.generateQR || '';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    if (!generateQRRoute) {
        qrCodeContainer.innerHTML = `
            <div class="text-center text-danger">
                <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                Route tidak ditemukan. Silakan refresh halaman.
            </div>
        `;
        return;
    }

    // Panggil endpoint server untuk generate QR Code
    fetch(generateQRRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
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
        
        // Simpan session token untuk stop session
        window.currentSessionToken = data.session_id;
        
        // Cek apakah qrcode-generator library tersedia
        if (!window.QRCodeLoaded || typeof qrcode === 'undefined') {
            qrCodeContainer.innerHTML = `
                <div class="text-center text-danger">
                    <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                    Library QR Code tidak tersedia. Silakan refresh halaman.
                </div>
            `;
            return;
        }
        
        // Pastikan data adalah object yang valid
        if (!data || typeof data !== 'object') {
            throw new Error('QR data is not a valid object');
        }
        
        // Pastikan semua field required ada
        if (!data.session_id || !data.timetable_id || !data.teacher_id || !data.checksum) {
            throw new Error('QR data is missing required fields');
        }
        
        // Generate QR code dengan data yang sudah divalidasi
        generateQRCodeWithLibrary(qrCodeContainer, data, {
            cellSize: 4,
            margin: 2,
            darkColor: '#000000',
            lightColor: '#ffffff'
        })
        .then(() => {
            // Hanya tampilkan tombol stop session jika QR code berhasil dibuat
            showStopSessionButtons(data.session_id, timetableId);
        })
        .catch((error) => {
            qrCodeContainer.innerHTML = `
                <div class="text-center text-danger">
                    <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                    Gagal menghasilkan QR Code: ${error.message}
                    <br><small class="text-muted">Silakan refresh halaman dan coba lagi</small>
                </div>
            `;
        });
    })
    .catch(error => {
        qrCodeContainer.innerHTML = `
            <div class="text-center text-danger">
                <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                ${error.message}
            </div>
        `;
    });
}

// Fungsi helper untuk menampilkan tombol stop session
function showStopSessionButtons(sessionId, timetableId) {
    // Validasi: pastikan sessionId dan timetableId ada
    if (!sessionId || !timetableId) {
        return;
    }
    
    const stopSessionBtn = document.getElementById('stopSession');
    
    // Tampilkan tombol stop session di bawah QR code
    if (stopSessionBtn) {
        stopSessionBtn.classList.add('show');
        
        stopSessionBtn.onclick = function() {
            showStopSessionModal(sessionId); // sessionId is actually the session token
        };
    } else {
        return;
    }
    
    // Mulai polling untuk update hasil scan
    startScanResultsPolling(timetableId);
}

// Fungsi untuk menampilkan modal konfirmasi stop session
function showStopSessionModal(sessionToken) {
    if (!sessionToken) {
        return;
    }

    // Set session token ke hidden input
    const hiddenInput = document.getElementById('stopSessionToken');
    if (hiddenInput) {
        hiddenInput.value = sessionToken;
    } else {
        return;
    }
    
    // Tampilkan modal
    const modalEl = document.getElementById('stopSessionModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

// Fungsi untuk stop attendance session
function stopAttendanceSession(sessionToken) {
    if (!sessionToken) {
        return;
    }

    // Tampilkan loading state pada tombol modal
    const confirmBtn = document.getElementById('confirmStopSessionButton');
    if (!confirmBtn) {
        return;
    }
    
    // Simpan original text dengan benar
    const originalText = `
        <iconify-icon icon="solar:stop-circle-outline" class="fs-16 me-2"></iconify-icon>
        Ya, Hentikan Sesi
    `;
    
    confirmBtn.innerHTML = `
        <div class="spinner-border spinner-border-sm me-2" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        Menghentikan...
    `;
    confirmBtn.disabled = true;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const stopSessionRoute = window.scanQRRoutes?.stopSession || '';

    if (!stopSessionRoute) {
        showNotification('Route tidak ditemukan. Silakan refresh halaman.', 'error');
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
        return;
    }

    const requestBody = {
        session_token: sessionToken
    };

    fetch(stopSessionRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(requestBody)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Reset tombol terlebih dahulu
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
            
            // Tutup modal
            const modalEl = document.getElementById('stopSessionModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            }
            
            // Tampilkan notifikasi sukses
            showNotification('Sesi absensi berhasil dihentikan', 'success');
            resetDropdown();
        } else {
            showNotification('Gagal menghentikan sesi: ' + (data.error || 'Unknown error'), 'error');
            // Reset tombol jika gagal
            if (confirmBtn) {
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            }
        }
    })
    .catch(error => {
        showNotification('Terjadi kesalahan saat menghentikan sesi: ' + error.message, 'error');
        // Reset tombol jika error
        if (confirmBtn) {
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        }
    });
}

// Fungsi untuk menampilkan notifikasi dengan modal (reuse instance agar tombol close/tutup selalu berfungsi)
function showNotification(message, type = 'info') {
    const modalEl = document.getElementById('notificationModal');
    if (!modalEl) {
        return;
    }
    
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const modalLabel = document.getElementById('notificationModalLabel');
    const modalMessage = document.getElementById('notificationMessage');
    const modalIcon = document.getElementById('notificationIcon');
    
    if (!modalLabel || !modalMessage || !modalIcon) {
        return;
    }
    
    // Set title berdasarkan type
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
    
    // Set message
    modalMessage.textContent = message;
    
    // Pastikan tombol Close/Tutup menutup instance yang sama
    // Remove focus before hiding to prevent accessibility warning
    const closeBtn = modalEl.querySelector('.btn-close');
    const dismissBtn = modalEl.querySelector('.btn-light[data-bs-dismiss="modal"]');
    if (closeBtn) {
        closeBtn.onclick = (e) => {
            e.target.blur(); // Remove focus before hiding
            modal.hide();
        };
    }
    if (dismissBtn) {
        dismissBtn.onclick = (e) => {
            e.target.blur(); // Remove focus before hiding
            modal.hide();
        };
    }
    
    // Handle when modal is hidden via Bootstrap events
    modalEl.addEventListener('hidden.bs.modal', function() {
        // Remove focus from any focused element inside modal
        const focusedElement = this.querySelector(':focus');
        if (focusedElement) {
            focusedElement.blur();
        }
    }, { once: false });

    modal.show();
}

// Fungsi untuk polling hasil scan
function startScanResultsPolling(timetableId) {
    // Hentikan polling sebelumnya jika ada
    if (window.scanResultsInterval) {
        clearInterval(window.scanResultsInterval);
    }

    const resultsRoute = window.scanQRRoutes?.results || '';
    if (!resultsRoute) {
        return;
    }

    // Polling setiap 3 detik
    window.scanResultsInterval = setInterval(function() {
        fetch(`${resultsRoute}/${timetableId}`)
        .then(response => response.json())
        .then(data => {
            updateScanResultsTable(data);
        })
        .catch(error => {
            // Silent error handling
        });
    }, 3000);
}

// Fungsi untuk update tabel hasil scan
function updateScanResultsTable(data) {
    const tbody = document.querySelector('#scan-results-table tbody');
    if (!tbody) {
        return;
    }
    
    if (data.length === 0) {
        tbody.innerHTML = `
            <tr id="initial-message-row">
                <td colspan="5" class="text-center py-4">
                    <div class="text-muted text-center">
                        <iconify-icon icon="solar:list-check-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                        Belum ada siswa yang melakukan absensi.
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = data.map((item, index) => `
        <tr>
            <td>${item.no}</td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="avatar-xs me-2">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                            ${item.student_name.charAt(0).toUpperCase()}
                        </span>
                    </div>
                    ${item.student_name}
                </div>
            </td>
            <td>
                <span class="fw-semibold">${item.check_in_time || '-'}</span>
            </td>
            <td>
                <span class="fw-semibold">${item.check_out_time || '-'}</span>
            </td>
            <td>
                ${getStatusBadge(item.status)}
            </td>
        </tr>
    `).join('');
}

// Fungsi untuk mendapatkan badge status
function getStatusBadge(status) {
    const statusMap = {
        'H': { class: 'bg-success-subtle text-success', text: 'Hadir', icon: 'bx bxs-circle text-success' },
        'T': { class: 'bg-warning-subtle text-warning', text: 'Terlambat', icon: 'bx bxs-circle text-warning' },
        'I': { class: 'bg-info-subtle text-info', text: 'Izin', icon: 'bx bxs-circle text-info' },
        'S': { class: 'bg-warning-subtle text-warning', text: 'Sakit', icon: 'bx bxs-circle text-warning' },
        'A': { class: 'bg-danger-subtle text-danger', text: 'Alpa', icon: 'bx bxs-circle text-danger' }
    };
    
    const statusInfo = statusMap[status] || { class: 'bg-secondary-subtle text-secondary', text: status, icon: 'bx bxs-circle text-secondary' };
    
    return `<span class="badge ${statusInfo.class} py-1 px-2">
        <i class="${statusInfo.icon} me-1"></i>${statusInfo.text}
    </span>`;
}

