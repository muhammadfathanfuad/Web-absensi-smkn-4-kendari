// QR Absensi Scanner JavaScript
// This file handles QR code scanning functionality for student attendance

// CRITICAL: Prevent duplicate script execution
if (window._qrScannerScriptExecuted) {
    // Reuse existing variables if they exist
    if (typeof window._qrScannerVars !== 'undefined') {
        var html5QrcodeScanner = window._qrScannerVars.html5QrcodeScanner;
        var html5Qrcode = window._qrScannerVars.html5Qrcode;
        var isScanning = window._qrScannerVars.isScanning;
    }
} else {
    window._qrScannerScriptExecuted = true;
    
    // CRITICAL: Prevent duplicate declarations - use window-level variables
    // Initialize only once, reuse if script runs multiple times
    if (typeof window._qrScannerVars === 'undefined') {
        window._qrScannerVars = {
            html5QrcodeScanner: null,
            html5Qrcode: null,
            isScanning: false,
        };
    }
    
    // Use local references for convenience, but always sync with window
    var html5QrcodeScanner = window._qrScannerVars.html5QrcodeScanner;
    var html5Qrcode = window._qrScannerVars.html5Qrcode;
    var isScanning = window._qrScannerVars.isScanning;

    // Check if HTTPS is required
    function isSecureContext() {
        // HTTPS is required except for localhost
        return window.isSecureContext || 
               location.protocol === 'https:' || 
               location.hostname === 'localhost' || 
               location.hostname === '127.0.0.1' ||
               location.hostname === '[::1]' ||
               location.hostname.endsWith('.local');
    }

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Check HTTPS requirement first
        if (!isSecureContext()) {
            updateCameraStatus('HTTPS diperlukan untuk akses kamera. Silakan gunakan HTTPS atau localhost.', 'danger');
            const readerElement = document.getElementById('reader');
            if (readerElement) {
                readerElement.innerHTML = `
                    <div class="text-center py-5" style="min-height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div class="mb-4">
                            <i class="bx bx-error-circle fs-64 text-danger d-block"></i>
                        </div>
                        <h5 class="text-danger mb-3">HTTPS Diperlukan</h5>
                        <p class="text-muted mb-0" style="max-width: 500px;">
                            Browser memerlukan koneksi HTTPS untuk mengakses kamera. 
                            Silakan akses halaman ini melalui HTTPS atau gunakan localhost untuk pengembangan.
                        </p>
                        <div class="alert alert-warning mt-3" style="max-width: 500px;">
                            <strong>Solusi:</strong><br>
                            1. Gunakan HTTPS (https://) untuk mengakses website<br>
                            2. Atau gunakan localhost untuk pengembangan lokal<br>
                            3. Hubungi administrator untuk mengaktifkan SSL/HTTPS
                        </div>
                    </div>
                `;
            }
            hideStartButton();
            return;
        }
        
        // Add event listeners for buttons
        const startBtn = document.getElementById('startCameraBtn');
        const stopBtn = document.getElementById('stopCameraBtn');
        const retryBtn = document.getElementById('retryCameraBtn');
        const refreshBtn = document.getElementById('refreshCameraBtn');
        
        if (startBtn) startBtn.addEventListener('click', startCamera);
        if (stopBtn) stopBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            stopCamera();
        });
        if (retryBtn) retryBtn.addEventListener('click', retryCamera);
        if (refreshBtn) refreshBtn.addEventListener('click', loadCameraList);
        
        // Load attendance history
        loadAttendanceHistory();
        
        // Check if we have camera access
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            updateCameraStatus('Memuat daftar kamera...', 'info');
            
            // Load camera list with a small delay to ensure page is fully loaded
            setTimeout(() => {
                loadCameraList();
            }, 500);
            
            // Check if QR library is loaded after a delay
            setTimeout(() => {
                if (typeof Html5QrcodeScanner === 'undefined' && typeof Html5Qrcode === 'undefined') {
                    updateCameraStatus('Library QR scanner sedang dimuat...', 'warning');
                } else {
                    updateCameraStatus('Library QR scanner siap', 'success');
                }
            }, 2000);
            
            // Initialize reader container with placeholder
            const readerElement = document.getElementById('reader');
            if (readerElement && readerElement.innerHTML.trim() === '') {
                readerElement.innerHTML = `
                    <div class="text-muted text-center" style="width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 400px;">
                        <i class="bx bx-camera fs-48 d-block mb-2" style="display: block; margin: 0 auto;"></i>
                        <div style="text-align: center; width: 100%;">Kamera akan dimuat di sini...</div>
                        <small class="text-muted" style="text-align: center; width: 100%; display: block; margin-top: 8px;">Klik "Mulai Kamera" untuk memulai pemindaian</small>
                    </div>
                `;
            }
        } else {
            updateCameraStatus('Browser tidak mendukung akses kamera', 'danger');
            hideStartButton();
            showRetryButton();
        }
    });

    // Load camera list function
    async function loadCameraList() {
        try {
            updateCameraStatus('Memuat daftar kamera...', 'info');
            
            // First, request permission to access media devices
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                try {
                    // Request permission first
                    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                    stream.getTracks().forEach(track => track.stop());
                } catch (permissionError) {
                    // Continue anyway, some browsers allow enumerateDevices without permission
                }
            }
            
            // Now enumerate devices
            const devices = await navigator.mediaDevices.enumerateDevices();
            
            const videoDevices = devices.filter(device => device.kind === 'videoinput');
            
            const cameraSelect = document.getElementById('cameraSelect');
            if (cameraSelect) {
                cameraSelect.innerHTML = '<option value="">Pilih kamera...</option>';
                
                if (videoDevices.length === 0) {
                    cameraSelect.innerHTML = '<option value="">Tidak ada kamera ditemukan</option>';
                    updateCameraStatus('Tidak ada kamera ditemukan. Pastikan kamera terhubung.', 'warning');
                    return;
                }
                
                videoDevices.forEach((device, index) => {
                    const option = document.createElement('option');
                    option.value = device.deviceId;
                    // Use device label if available, otherwise use generic name
                    const deviceName = device.label || `Kamera ${index + 1}`;
                    option.textContent = deviceName;
                    cameraSelect.appendChild(option);
                });
                
                // Select first camera by default
                if (videoDevices.length > 0) {
                    cameraSelect.value = videoDevices[0].deviceId;
                }
                
                updateCameraStatus(`Daftar kamera dimuat (${videoDevices.length} kamera)`, 'success');
            }
        } catch (error) {
            updateCameraStatus('Gagal memuat daftar kamera: ' + error.message, 'danger');
            
            // Fallback: add a generic option
            const cameraSelect = document.getElementById('cameraSelect');
            if (cameraSelect) {
                cameraSelect.innerHTML = '<option value="default">Kamera Default</option>';
            }
        }
    }

    // Start camera function - Disederhanakan untuk mencegah duplikat
    async function startCamera() {
        if (isScanning) {
            return;
        }
        
        if (!isSecureContext()) {
            updateCameraStatus('HTTPS diperlukan untuk akses kamera', 'danger');
            alert('Browser memerlukan koneksi HTTPS untuk mengakses kamera. Silakan gunakan HTTPS atau localhost.');
            return;
        }
        
        try {
            updateCameraStatus('Memulai kamera...', 'info');
            
            // 1. Hentikan instance yang mungkin masih ada
            if (html5QrcodeScanner) {
                await html5QrcodeScanner.clear().catch(() => {});
                html5QrcodeScanner = null;
                window._qrScannerVars.html5QrcodeScanner = null;
            }
            if (html5Qrcode) {
                await html5Qrcode.stop().catch(() => {});
                html5Qrcode.clear();
                html5Qrcode = null;
                window._qrScannerVars.html5Qrcode = null;
            }
            
            // 2. Bersihkan container
            const readerElement = document.getElementById('reader');
            if (readerElement) {
                readerElement.innerHTML = ''; // Pastikan bersih
            }
            
            // Tunggu DOM bersih
            await new Promise(resolve => setTimeout(resolve, 50));
            
            // 3. Cek library
            if (typeof Html5Qrcode === 'undefined') {
                updateCameraStatus('Library scanner belum dimuat. Coba refresh halaman.', 'danger');
                return;
            }
            
            // 4. Buat instance baru
            const cameraSelect = document.getElementById('cameraSelect');
            const selectedDeviceId = cameraSelect ? cameraSelect.value : null;
            
            const html5QrCodeInstance = new Html5Qrcode("reader");
            window._qrScannerVars.html5Qrcode = html5QrCodeInstance;
            html5Qrcode = html5QrCodeInstance;
            
            const config = {
                fps: 10,
                qrbox: function(viewfinderWidth, viewfinderHeight) {
                    const minEdgePercentage = 0.7;
                    const minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                    const qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                    return { width: qrboxSize, height: qrboxSize };
                },
                aspectRatio: 1.0,
                disableFlip: false,
                videoConstraints: {
                    facingMode: "environment"
                }
            };
            
            let cameraId = (selectedDeviceId && selectedDeviceId !== '' && selectedDeviceId !== 'default') 
                ? selectedDeviceId 
                : { facingMode: "environment" };
            
            // 5. Mulai kamera
            await html5QrCodeInstance.start(
                cameraId,
                config,
                onScanSuccess,
                onScanFailure
            );
            
            // 6. Update status
            isScanning = true;
            window._qrScannerVars.isScanning = true;
            
            updateCameraStatus('Kamera aktif - Arahkan ke QR Code', 'success');
            updateScanStatus('Scanner aktif - Arahkan kamera ke QR Code', 'info');
            hideStartButton();
            showStopButton();
            hideRetryButton();
            
            const startBtn = document.getElementById('startCameraBtn');
            const stopBtn = document.getElementById('stopCameraBtn');
            const retryBtn = document.getElementById('retryCameraBtn');
            if (startBtn) startBtn.disabled = true;
            if (stopBtn) stopBtn.disabled = false;
            if (retryBtn) retryBtn.disabled = true;
            
        } catch (err) {
            updateCameraStatus('Gagal memulai kamera: ' + err.message, 'danger');
            showRetryButton();
            showStartButton();
            hideStopButton();
            isScanning = false;
            window._qrScannerVars.isScanning = false;
        }
    }
            
    // Stop camera function - Disederhanakan
    function stopCamera() {
        if (!isScanning) {
            return;
        }
        
        // HAPUS SEMUA REFERENSI KE 'firstVideoElement', 'duplicateRemovalInterval', dan 'Observer'
        isScanning = false;
        window._qrScannerVars.isScanning = false;
        
        // Ambil instance dari window._qrScannerVars untuk konsistensi
        html5Qrcode = window._qrScannerVars.html5Qrcode;
        html5QrcodeScanner = window._qrScannerVars.html5QrcodeScanner;
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().catch(err => {});
            html5QrcodeScanner = null;
            window._qrScannerVars.html5QrcodeScanner = null;
        }
        if (html5Qrcode) {
            html5Qrcode.stop().then(() => {
                html5Qrcode.clear();
            }).catch(err => {});
            html5Qrcode = null;
            window._qrScannerVars.html5Qrcode = null;
        }
        if (window.basicCameraStream) {
            window.basicCameraStream.getTracks().forEach(track => track.stop());
            window.basicCameraStream = null;
        }
        
        // Reset UI
        const readerElement = document.getElementById('reader');
        if (readerElement) {
            readerElement.innerHTML = `
                <div class="text-muted text-center" style="width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <i class="bx bx-camera fs-48 d-block mb-2" style="display: block; margin: 0 auto;"></i>
                    <div style="text-align: center; width: 100%;">Kamera akan dimuat di sini...</div>
                    <small class="text-muted" style="text-align: center; width: 100%; display: block; margin-top: 8px;">Klik "Mulai Kamera" untuk memulai pemindaian</small>
                </div>
            `;
        }
        
        updateCameraStatus('Kamera dihentikan', 'warning');
        updateScanStatus('Klik "Mulai Kamera" untuk memulai pemindaian', 'info');
        
        showStartButton();
        hideStopButton();
        hideRetryButton();
        
        const startBtn = document.getElementById('startCameraBtn');
        const stopBtn = document.getElementById('stopCameraBtn');
        const retryBtn = document.getElementById('retryCameraBtn');
        if (startBtn) startBtn.disabled = false;
        if (stopBtn) stopBtn.disabled = true;
        if (retryBtn) retryBtn.disabled = false;
    }

    // Retry camera function
    function retryCamera() {
        stopCamera();
        setTimeout(() => {
            startCamera();
        }, 1000);
    }

    // Success callback
    function onScanSuccess(decodedText, decodedResult) {
        
        // Update UI immediately for better UX
        updateScanStatus('QR Code terdeteksi! Memproses...', 'success');
        
        // Stop scanning to prevent multiple submissions (async, don't wait)
        // Stop camera in background so it doesn't block the submission
        setTimeout(() => {
            stopCamera();
        }, 100);
        
        // Parse QR code data immediately
        try {
            // Handle case where decodedText might be a string representation of an object
            let qrData;
            if (typeof decodedText === 'string') {
                // Try to parse as JSON
                try {
                    qrData = JSON.parse(decodedText);
                } catch (parseError) {
                    // If parsing fails, try to extract JSON from the string
                    // Sometimes QR codes might have extra whitespace or characters
                    const cleanedText = decodedText.trim();
                    qrData = JSON.parse(cleanedText);
                }
            } else if (typeof decodedText === 'object') {
                // Already an object
                qrData = decodedText;
            } else {
                throw new Error('QR code data is not in a valid format');
            }
            
            // Validate QR data format
            if (validateQRData(qrData)) {
                // Update status immediately
                updateScanStatus('Mengirim data absensi...', 'info');
                // Submit attendance immediately
                submitAttendance(qrData);
            } else {
                updateScanStatus('Format QR Code tidak valid atau bukan QR guru. Pastikan QR code dari guru yang benar.', 'danger');
                // Stop camera immediately on validation failure
                stopCamera();
                // After validation fails, ensure buttons are reset properly
                setTimeout(() => {
                    resetButtonsToInitialState();
                }, 1500);
            }
            
        } catch (error) {
            updateScanStatus('Format QR Code tidak valid. Pastikan QR code dari guru yang benar.', 'danger');
            // Stop camera immediately on parsing error
            stopCamera();
            // After parsing error, ensure buttons are reset properly
            setTimeout(() => {
                resetButtonsToInitialState();
            }, 1500);
        }
    }

    // Validate QR data format
    function validateQRData(qrData) {
        // Check if it's a teacher QR format (simplified version)
        const requiredFields = ['timetable_id', 'session_id', 'teacher_id', 'checksum'];
        const hasRequiredFields = requiredFields.every(field => qrData.hasOwnProperty(field));
        
        if (!hasRequiredFields) {
            return false;
        }
        
        // Check data types - lebih fleksibel untuk menerima string atau number
        // Convert to number jika perlu
        if (qrData.timetable_id === null || qrData.timetable_id === undefined || qrData.timetable_id === '') {
            return false;
        }
        
        // Convert to number untuk memastikan konsistensi
        const timetableId = Number(qrData.timetable_id);
        if (isNaN(timetableId) || timetableId <= 0) {
            return false;
        }
        
        if (typeof qrData.session_id !== 'string' || qrData.session_id.length === 0) {
            return false;
        }
        
        // Convert to number untuk memastikan konsistensi
        const teacherId = Number(qrData.teacher_id);
        if (isNaN(teacherId) || teacherId <= 0) {
            return false;
        }
        
        if (typeof qrData.checksum !== 'string' || qrData.checksum.length === 0) {
            return false;
        }
        
        // Normalize data untuk konsistensi
        qrData.timetable_id = timetableId;
        qrData.teacher_id = teacherId;
        
        return true;
    }

    // Failure callback
    let scanFailureCount = 0;
    let lastErrorLogTime = 0;
    
    function onScanFailure(error) {
        // This function is called very frequently (every frame when no QR code is found)
        // Don't log every attempt to avoid console spam
        
        scanFailureCount++;
        
        // Only log errors that are NOT normal "not found" errors
        // And only log once per second to avoid spam
        const now = Date.now();
        const timeSinceLastLog = now - lastErrorLogTime;
        
        if (error) {
            const errorStr = error.toString ? error.toString() : String(error);
            const errorMessage = error.message || errorStr;
            
            // Check if it's a real error (not just "no QR found")
            const isRealError = errorMessage && 
                !errorMessage.includes('No QR code found') &&
                !errorMessage.includes('NotFoundException') &&
                !errorMessage.includes('QR code parse error') && // Common parsing errors are normal
                !errorMessage.includes('No MultiFormat'); // This is a common error when QR format not supported
            
            // Only log real errors, and only once per second
            if (isRealError && timeSinceLastLog > 1000) {
                lastErrorLogTime = now;
            }
        }
        
        // Don't update UI on every failure - it's normal for scanner to fail when no QR is visible
        // The scanner tries to read QR code every frame, so failures are expected
    }

    // Submit attendance function
    function submitAttendance(qrData) {
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        // Validate QR data format first
        if (!qrData.timetable_id) {
            updateScanStatus('Format QR Code tidak valid: timetable_id tidak ditemukan', 'danger');
            showRetryButton();
            return;
        }
        
        // Prepare request data - format sesuai dengan yang diharapkan controller
        // Pastikan timetable_id adalah number
        const requestData = {
            timetable_id: Number(qrData.timetable_id), // Pastikan adalah number
            session_token: qrData.session_id || qrData.session_token, // Handle both formats
            timestamp: new Date().toISOString()
        };
        
        // Validasi akhir sebelum submit
        if (isNaN(requestData.timetable_id) || requestData.timetable_id <= 0) {
            updateScanStatus('Format QR Code tidak valid: timetable_id tidak valid', 'danger');
            showRetryButton();
            return;
        }
        
        // Submit to server immediately
        const startTime = Date.now();
        
        // Get fresh CSRF token
        const freshCsrfToken = document.querySelector('meta[name="csrf-token"]');
        const csrfTokenValue = freshCsrfToken ? freshCsrfToken.getAttribute('content') : '';
        
        if (!csrfTokenValue) {
            updateScanStatus('Error: CSRF token tidak ditemukan. Silakan refresh halaman.', 'danger');
            stopCamera();
            return;
        }
        
        // Get route from window object
        const scanRoute = window.qrAbsensiRoutes && window.qrAbsensiRoutes.scan;
        if (!scanRoute) {
            updateScanStatus('Error: Route tidak ditemukan. Silakan refresh halaman.', 'danger');
            stopCamera();
            return;
        }
        
        // Add timeout to prevent hanging (30 seconds max)
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
        
        fetch(scanRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfTokenValue,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(requestData),
            signal: controller.signal,
            credentials: 'same-origin' // Include cookies for session
        })
        .then(response => {
            clearTimeout(timeoutId);
            const responseTime = Date.now() - startTime;
            
            // Handle 403 Forbidden specifically
            if (response.status === 403) {
                return response.json().then(data => {
                    throw new Error(`Akses ditolak (403): ${data.message || 'Anda tidak memiliki izin untuk melakukan aksi ini. Pastikan Anda login sebagai murid dan session belum expired.'}`);
                }).catch(() => {
                    throw new Error('Akses ditolak (403). Silakan refresh halaman dan login kembali.');
                });
            }
            
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(`HTTP error! status: ${response.status}, message: ${data.message || 'Unknown error'}`);
                }).catch(err => {
                    if (err.message.includes('HTTP error')) {
                        throw err;
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            const totalTime = Date.now() - startTime;
            
            // Update UI immediately - show results right away
            if (data.success) {
                // Update status immediately
                updateScanStatus('Absensi berhasil! ' + (data.message || ''), 'success');
                
                // Show notification asynchronously (don't block)
                setTimeout(() => {
                    showNotification(data.message || 'Absensi berhasil dicatat!', true);
                }, 100);
                
                // Refresh attendance history immediately (no delay)
                loadAttendanceHistory();
                
                // Reset buttons after successful attendance (shorter delay)
                setTimeout(() => {
                    resetButtonsToInitialState();
                }, 1200);
            } else {
                // Update status immediately
                updateScanStatus('Gagal absensi: ' + (data.message || 'Terjadi kesalahan'), 'danger');
                
                // Show notification asynchronously (don't block)
                setTimeout(() => {
                    showNotification(data.message || 'Terjadi kesalahan', false);
                }, 100);
                
                // Reset buttons after failed attendance (shorter delay)
                setTimeout(() => {
                    resetButtonsToInitialState();
                }, 1200);
            }
        })
        .catch(error => {
            clearTimeout(timeoutId);
            const totalTime = Date.now() - startTime;
            
            let errorMessage = 'Terjadi kesalahan saat mengirim data';
            
            if (error.name === 'AbortError') {
                errorMessage = 'Request timeout (lebih dari 30 detik). Server mungkin sedang sibuk atau ada masalah koneksi.';
            } else if (error.message.includes('403')) {
                errorMessage = 'Akses ditolak. Silakan refresh halaman dan pastikan Anda login sebagai murid.';
            } else if (error.message.includes('401')) {
                errorMessage = 'Session expired. Silakan refresh halaman dan login kembali.';
            } else if (error.message.includes('500')) {
                errorMessage = 'Error server. Silakan coba lagi atau hubungi administrator.';
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            updateScanStatus(errorMessage, 'danger');
            showNotification(errorMessage, false);
            
            // Reset buttons after error
            setTimeout(() => {
                resetButtonsToInitialState();
            }, 2000);
        });
    }

    // UI Helper functions
    function updateCameraStatus(message, type) {
        const statusElement = document.getElementById('cameraStatus');
        
        // Check if element exists before trying to update it
        if (!statusElement) {
            return;
        }
        
        const alertElement = statusElement.closest('.alert');
        
        statusElement.textContent = message;
        
        // Update alert class if alert element exists
        if (alertElement) {
            alertElement.className = `alert alert-${type} d-flex align-items-center justify-content-between`;
        }
    }

    function updateScanStatus(message, type) {
        const statusElement = document.getElementById('scanStatus');
        
        // Check if element exists before trying to update it
        if (!statusElement) {
            return;
        }
        
        const iconElement = statusElement.querySelector('i');
        
        statusElement.textContent = message;
        statusElement.className = `alert alert-${type}`;
        
        // Update icon based on type - only if icon element exists
        const iconMap = {
            'info': 'bx-info-circle',
            'success': 'bx-check-circle',
            'warning': 'bx-error-circle',
            'danger': 'bx-x-circle'
        };
        
        if (iconMap[type] && iconElement) {
            iconElement.className = `bx ${iconMap[type]} me-2`;
        } else if (iconMap[type] && !iconElement) {
            // If no icon element exists, create one
            const newIcon = document.createElement('i');
            newIcon.className = `bx ${iconMap[type]} me-2`;
            statusElement.insertBefore(newIcon, statusElement.firstChild);
        }
    }

    function showStartButton() {
        const btn = document.getElementById('startCameraBtn');
        if (btn) btn.classList.remove('d-none');
    }

    function hideStartButton() {
        const btn = document.getElementById('startCameraBtn');
        if (btn) btn.classList.add('d-none');
    }

    function showStopButton() {
        const btn = document.getElementById('stopCameraBtn');
        if (btn) btn.classList.remove('d-none');
    }

    function hideStopButton() {
        const btn = document.getElementById('stopCameraBtn');
        if (btn) btn.classList.add('d-none');
    }

    function showRetryButton() {
        const btn = document.getElementById('retryCameraBtn');
        if (btn) btn.classList.remove('d-none');
    }

    function hideRetryButton() {
        const btn = document.getElementById('retryCameraBtn');
        if (btn) btn.classList.add('d-none');
    }

    // Helper function to reset all buttons to initial state
    function resetButtonsToInitialState() {
        // Check if this is a manual stop - if so, don't interfere
        if (window.manualStop) {
            return;
        }
        
        // Show start button, hide stop and retry buttons
        showStartButton();
        hideStopButton();
        hideRetryButton();
        
        // Reset disabled states
        const startBtn = document.getElementById('startCameraBtn');
        const stopBtn = document.getElementById('stopCameraBtn');
        const retryBtn = document.getElementById('retryCameraBtn');
        if (startBtn) startBtn.disabled = false;
        if (stopBtn) stopBtn.disabled = true;
        if (retryBtn) retryBtn.disabled = false;
        
        // Reset camera status
        updateCameraStatus('Siap untuk memulai kamera', 'info');
        
        // Only update scan status if element exists
        const scanStatusElement = document.getElementById('scanStatus');
        if (scanStatusElement) {
            updateScanStatus('Klik "Mulai Kamera" untuk memulai pemindaian', 'info');
        }
    }

    // Basic camera access fallback function
    function tryBasicCameraAccess() {
        updateCameraStatus('Mencoba akses kamera dasar...', 'info');
        
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ 
                video: {
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: "environment"
                },
                audio: false 
            })
            .then(function(stream) {
                
                // Display video directly
                const readerElement = document.getElementById('reader');
                if (readerElement) {
                    readerElement.innerHTML = '<video id="basicVideo" autoplay muted playsinline style="width: 100%; max-width: 500px; height: auto; border-radius: 8px; transform: scaleX(-1);"></video>';
                    
                    const video = document.getElementById('basicVideo');
                    if (video) {
                        video.srcObject = stream;
                    }
                }
                
                // Hide start button, show stop button
                hideStartButton();
                showStopButton();
                
                updateCameraStatus('Kamera aktif (Mode Dasar) - Arahkan ke QR Code', 'success');
                updateScanStatus('Kamera aktif dalam mode dasar. QR scanning tidak tersedia.', 'warning');
                
                // Store stream for cleanup
                window.basicCameraStream = stream;
                isScanning = true;
                window._qrScannerVars.isScanning = true; // Sync with window
            })
            .catch(function(error) {
                let errorMessage = 'Gagal akses kamera: ';
                if (error.name === 'NotAllowedError') {
                    errorMessage += 'Permission denied';
                } else if (error.name === 'NotFoundError') {
                    errorMessage += 'No camera found';
                } else if (error.name === 'NotReadableError') {
                    errorMessage += 'Camera in use';
                } else {
                    errorMessage += error.message;
                }
                updateCameraStatus(errorMessage, 'danger');
                showRetryButton();
            });
        } else {
            updateCameraStatus('Browser tidak mendukung akses kamera', 'danger');
            showRetryButton();
        }
    }

    // Basic camera access with specific device
    function tryBasicCameraAccessWithDevice(deviceId) {
        updateCameraStatus('Mencoba akses kamera dengan device yang dipilih...', 'info');
        
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            // Use device ID or default to environment facing mode
            let constraints;
            if (deviceId && deviceId !== 'default') {
                constraints = {
                    video: {
                        deviceId: { exact: deviceId },
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false 
                };
            } else {
                constraints = {
                    video: {
                        facingMode: "environment",
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false 
                };
            }
            
            navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                
                // Display video directly
                const readerElement = document.getElementById('reader');
                if (readerElement) {
                    readerElement.innerHTML = '<video id="basicVideo" autoplay muted playsinline style="width: 100%; max-width: 500px; height: auto; border-radius: 8px; transform: scaleX(-1);"></video>';
                    
                    const video = document.getElementById('basicVideo');
                    if (video) {
                        video.srcObject = stream;
                    }
                }
                
                // Hide start button, show stop button
                hideStartButton();
                showStopButton();
                
                updateCameraStatus('Kamera aktif (Mode Dasar) - Arahkan ke QR Code', 'success');
                updateScanStatus('Kamera aktif dalam mode dasar. QR scanning tidak tersedia.', 'warning');
                
                // Store stream for cleanup
                window.basicCameraStream = stream;
                isScanning = true;
                window._qrScannerVars.isScanning = true; // Sync with window
            })
            .catch(function(error) {
                let errorMessage = 'Gagal akses kamera: ';
                if (error.name === 'NotAllowedError') {
                    errorMessage += 'Permission denied';
                } else if (error.name === 'NotFoundError') {
                    errorMessage += 'Camera not found';
                } else if (error.name === 'NotReadableError') {
                    errorMessage += 'Camera in use';
                } else if (error.name === 'OverconstrainedError') {
                    errorMessage += 'Camera constraints not supported';
                } else {
                    errorMessage += error.message;
                }
                updateCameraStatus(errorMessage, 'danger');
                showRetryButton();
            });
        } else {
            updateCameraStatus('Browser tidak mendukung akses kamera', 'danger');
            showRetryButton();
        }
    }

    // Modal notification function
    function showNotification(message, isSuccess = true) {
        const modalElement = document.getElementById('notificationModal');
        if (!modalElement) return;
        
        const modal = new bootstrap.Modal(modalElement);
        const modalLabel = document.getElementById('notificationModalLabel');
        const modalMessage = document.getElementById('notificationMessage');
        const modalIcon = document.getElementById('notificationIcon');
        
        // Set title and icon based on success/failure
        if (isSuccess) {
            if (modalLabel) modalLabel.textContent = 'Berhasil';
            if (modalIcon) {
                modalIcon.setAttribute('icon', 'solar:check-circle-outline');
                modalIcon.className = 'fs-48 text-success';
            }
        } else {
            if (modalLabel) modalLabel.textContent = 'Gagal';
            if (modalIcon) {
                modalIcon.setAttribute('icon', 'solar:danger-circle-outline');
                modalIcon.className = 'fs-48 text-danger';
            }
        }
        
        if (modalMessage) modalMessage.textContent = message;
        modal.show();
    }

    // Load attendance history function
    function loadAttendanceHistory() {
        const historyRoute = window.qrAbsensiRoutes && window.qrAbsensiRoutes.history;
        if (!historyRoute) {
            console.error('Attendance history route not found');
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const csrfTokenValue = csrfToken ? csrfToken.getAttribute('content') : '';
        
        fetch(historyRoute, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfTokenValue
            }
        })
        .then(response => response.json())
        .then(data => {
            updateAttendanceHistoryTable(data.attendances || []);
            updateAttendanceSummary(data.summary || {});
        })
        .catch(error => {
            console.error('Error loading attendance history:', error);
            updateAttendanceHistoryTable([]);
        });
    }

    // Update attendance history table
    function updateAttendanceHistoryTable(attendances) {
        const tbody = document.getElementById('attendanceHistoryBody');
        if (!tbody) return;
        
        if (attendances.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-muted text-center">
                            <i class="bx bx-history fs-48 d-block mx-auto mb-2"></i>
                            Belum ada riwayat kehadiran hari ini.
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = attendances.map((attendance, index) => {
            return `
                <tr>
                    <td>${index + 1}</td>
                    <td>${attendance.subject_name || '-'}</td>
                    <td>${attendance.class_name || '-'}</td>
                    <td>${attendance.time_range || '-'}</td>
                    <td>
                        ${getStatusBadge(attendance.status)}
                    </td>
                    <td>${attendance.check_in_time || '-'}</td>
                    <td>${attendance.check_out_time || '-'}</td>
                    <td>${formatAttendanceNotes(attendance)}</td>
                </tr>
            `;
        }).join('');
    }

    // Format attendance notes with better time information
    function formatAttendanceNotes(attendance) {
        let notes = '';
        
        // Format based on status
        if (attendance.status === 'H') {
            // Hadir - show scan time
            if (attendance.check_in_time && attendance.check_in_time !== '-') {
                notes = `Hadir tepat waktu (Scan: ${attendance.check_in_time})`;
            } else {
                notes = 'Hadir tepat waktu';
            }
        } else if (attendance.status === 'T') {
            // Terlambat - show late time and scan time
            const lateMinutes = Math.abs(Math.round(attendance.late_minutes || 0));
            const timeFormat = formatLateTime(lateMinutes);
            
            if (attendance.check_in_time && attendance.check_in_time !== '-') {
                notes = `Terlambat ${timeFormat} (Scan: ${attendance.check_in_time})`;
            } else {
                notes = `Terlambat ${timeFormat}`;
            }
        } else if (attendance.status === 'A') {
            notes = 'Tidak hadir - tidak melakukan scan';
        } else if (attendance.status === 'I') {
            notes = 'Izin';
        } else if (attendance.status === 'S') {
            notes = 'Sakit';
        } else {
            // Fallback to original notes if available
            notes = attendance.notes || '-';
        }
        
        // Add check-out time if available
        if (attendance.check_out_time && attendance.check_out_time !== '-') {
            notes += ` (Keluar: ${attendance.check_out_time})`;
        }
        
        return notes;
    }

    // Format late time to show hours and minutes properly
    function formatLateTime(minutes) {
        // Ensure we have a valid number
        if (!minutes || minutes < 0) {
            return '0 menit';
        }
        
        const roundedMinutes = Math.round(minutes);
        
        if (roundedMinutes === 0) {
            return '0 menit';
        } else if (roundedMinutes < 60) {
            return `${roundedMinutes} menit`;
        } else {
            const hours = Math.floor(roundedMinutes / 60);
            const remainingMinutes = roundedMinutes % 60;
            if (remainingMinutes === 0) {
                return `${hours} jam`;
            } else {
                return `${hours} jam ${remainingMinutes} menit`;
            }
        }
    }

    // Update attendance summary
    function updateAttendanceSummary(summary) {
        // Update summary cards if they exist
        const hadirElement = document.querySelector('.card.border-success h3');
        const terlambatElement = document.querySelector('.card.border-warning h3');
        const tidakHadirElement = document.querySelector('.card.border-danger h3');
        const totalElement = document.querySelector('.card.border-primary h3');

        if (hadirElement) hadirElement.textContent = summary.hadir || 0;
        if (terlambatElement) terlambatElement.textContent = summary.terlambat || 0;
        if (tidakHadirElement) tidakHadirElement.textContent = summary.tidak_hadir || 0;
        if (totalElement) totalElement.textContent = summary.total || 0;
    }

    // Get status badge HTML
    function getStatusBadge(status) {
        const statusMap = {
            'H': { class: 'bg-success', text: 'Hadir' },
            'T': { class: 'bg-warning', text: 'Terlambat' },
            'A': { class: 'bg-danger', text: 'Tidak Hadir' },
            'I': { class: 'bg-info', text: 'Izin' },
            'S': { class: 'bg-warning', text: 'Sakit' }
        };
        
        const statusInfo = statusMap[status] || { class: 'bg-secondary', text: status };
        return `<span class="badge ${statusInfo.class}">${statusInfo.text}</span>`;
    }

    // Clean up when page unloads
    window.addEventListener('beforeunload', function() {
        if (html5QrcodeScanner && isScanning) {
            html5QrcodeScanner.clear().catch(err => {
                // Silent error handling
            });
        }
        // Reset flags
        if (window._qrScannerVars) {
            window._qrScannerVars.html5QrcodeScanner = null;
            window._qrScannerVars.isScanning = false;
        }
    });
    
    // Make functions globally accessible for onclick handlers
    window.startCamera = startCamera;
    window.stopCamera = stopCamera;
    window.retryCamera = retryCamera;
    window.loadCameraList = loadCameraList;
} // End of else block for duplicate script prevention

