<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Siswa', 'subtitle' => 'Scan QR Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title mb-4">Arahkan kamera ke QR Code guru</h5>
                    
                    <!-- Camera Status -->
                    <div class="mb-3">
                        <div class="alert alert-info d-flex align-items-center justify-content-between" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-camera me-2"></i>
                                <span id="cameraStatus">Memuat kamera...</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button id="startCameraBtn" class="btn btn-sm btn-success">
                                    <i class="bx bx-play me-1"></i>Mulai Kamera
                                </button>
                                <button id="stopCameraBtn" class="btn btn-sm btn-warning d-none">
                                    <i class="bx bx-stop me-1"></i>Stop Kamera
                                </button>
                                <button id="retryCameraBtn" class="btn btn-sm btn-outline-primary d-none">
                                    <i class="bx bx-refresh me-1"></i>Retry
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- QR Scanner Container -->
                    <div class="mb-3">
                        <div class="mb-3">
                            <div class="d-flex gap-2">
                                <select id="cameraSelect" class="form-select">
                                    <option value="">Memuat daftar kamera...</option>
                                </select>
                                <button id="refreshCameraBtn" class="btn btn-outline-secondary btn-sm" title="Refresh daftar kamera">
                                    <i class="bx bx-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div id="reader" style="width:100%; min-height:400px; border: 2px solid #dee2e6; border-radius: 8px; background-color: #000; position: relative; overflow: hidden;">
                        </div>
                    </div>
                    
                    <!-- Minimal CSS - only basic styling -->
                    <style>
                        /* Only basic styling for reader container */
                        #reader {
                            background: #000;
                            border: 2px solid #dee2e6;
                            border-radius: 8px;
                            min-height: 400px;
                        }
                    </style>

                    <!-- Status Display -->
                    <div id="scanStatus" class="alert alert-info" role="alert">
                        <i class="bx bx-info-circle me-2"></i>Menunggu pemindaian QR Code...
                    </div>

                    <!-- Instructions -->
                    <div class="mt-4">
                        <h6 class="text-muted">Cara menggunakan:</h6>
                        <ol class="text-start text-muted small">
                            <li>Pilih kamera dari dropdown jika ada beberapa kamera</li>
                            <li>Klik tombol "Mulai Kamera" untuk mengaktifkan scanner</li>
                            <li>Izinkan akses kamera di browser ketika diminta</li>
                            <li>Arahkan kamera ke QR Code yang ditampilkan guru</li>
                            <li><strong>Scan Pertama:</strong> Check-in (masuk ke kelas)</li>
                            <li><strong>Scan Kedua:</strong> Check-out (keluar dari kelas)</li>
                            <li>Data absensi akan dikirim secara otomatis setelah QR Code terdeteksi</li>
                        </ol>
                        
                        <div class="alert alert-warning mt-3" role="alert">
                            <i class="bx bx-info-circle me-2"></i>
                            <strong>Tips:</strong> Pastikan browser Anda mendukung akses kamera dan gunakan HTTPS untuk hasil terbaik.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-history me-2"></i>
                        Riwayat Kehadiran Hari Ini
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Jam</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Jam Masuk</th>
                                    <th scope="col">Jam Keluar</th>
                                    <th scope="col">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceHistoryBody">
                                
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted text-center">
                                            <i class="bx bx-history fs-48 d-block mx-auto mb-2"></i>
                                            Memuat riwayat kehadiran...
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-success">Hadir</h5>
                                    <h3 class="text-success">2</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-warning">Terlambat</h5>
                                    <h3 class="text-warning">1</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-danger">Tidak Hadir</h5>
                                    <h3 class="text-danger">2</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-primary">Total Pelajaran</h5>
                                    <h3 class="text-primary">5</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <!-- HTML5 QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    
    <script>
        // Global variables
        let html5QrcodeScanner = null;
        let isScanning = false;

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('QR Scanner page loaded');
            console.log('Html5QrcodeScanner available:', typeof Html5QrcodeScanner !== 'undefined');
            console.log('Html5Qrcode available:', typeof Html5Qrcode !== 'undefined');
            console.log('Navigator.mediaDevices:', !!navigator.mediaDevices);
            console.log('getUserMedia available:', !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia));
            
            // Add event listeners for buttons
            document.getElementById('startCameraBtn').addEventListener('click', startCamera);
            document.getElementById('stopCameraBtn').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Stop camera button clicked');
                stopCamera();
            });
            document.getElementById('retryCameraBtn').addEventListener('click', retryCamera);
            document.getElementById('refreshCameraBtn').addEventListener('click', loadCameraList);
            
            console.log('Event listeners attached to buttons');
            
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
                    console.log('Checking QR library after delay...');
                    console.log('Html5QrcodeScanner available:', typeof Html5QrcodeScanner !== 'undefined');
                    console.log('Html5Qrcode available:', typeof Html5Qrcode !== 'undefined');
                    
                    if (typeof Html5QrcodeScanner === 'undefined' && typeof Html5Qrcode === 'undefined') {
                        updateCameraStatus('Library QR scanner sedang dimuat...', 'warning');
                    } else {
                        updateCameraStatus('Library QR scanner siap', 'success');
                    }
                }, 2000);
            } else {
                updateCameraStatus('Browser tidak mendukung akses kamera', 'danger');
                hideStartButton();
                hideTestButton();
                showRetryButton();
            }
        });

        // Load camera list function
        async function loadCameraList() {
            try {
                console.log('Loading camera list...');
                updateCameraStatus('Memuat daftar kamera...', 'info');
                
                // First, request permission to access media devices
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    try {
                        // Request permission first
                        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                        console.log('Permission granted, stopping test stream...');
                        stream.getTracks().forEach(track => track.stop());
                    } catch (permissionError) {
                        console.log('Permission not granted yet:', permissionError);
                        // Continue anyway, some browsers allow enumerateDevices without permission
                    }
                }
                
                // Now enumerate devices
                const devices = await navigator.mediaDevices.enumerateDevices();
                console.log('All devices:', devices);
                
                const videoDevices = devices.filter(device => device.kind === 'videoinput');
                console.log('Video devices:', videoDevices);
                
                const cameraSelect = document.getElementById('cameraSelect');
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
                    console.log(`Added camera: ${deviceName} (${device.deviceId})`);
                });
                
                // Select first camera by default
                if (videoDevices.length > 0) {
                    cameraSelect.value = videoDevices[0].deviceId;
                    console.log('Selected default camera:', videoDevices[0].deviceId);
                }
                
                updateCameraStatus(`Daftar kamera dimuat (${videoDevices.length} kamera)`, 'success');
                
            } catch (error) {
                console.error('Error loading camera list:', error);
                updateCameraStatus('Gagal memuat daftar kamera: ' + error.message, 'danger');
                
                // Fallback: add a generic option
                const cameraSelect = document.getElementById('cameraSelect');
                cameraSelect.innerHTML = '<option value="default">Kamera Default</option>';
            }
        }

        // Start camera function - Exact copy from working test file
        function startCamera() {
            console.log('Starting camera...');
            
            if (isScanning) {
                console.log('Camera already scanning, ignoring start request');
                return;
            }
            
            try {
                updateCameraStatus('Memulai kamera...', 'info');
                
                // Clear the reader container first
                const readerElement = document.getElementById('reader');
                readerElement.innerHTML = '';
                
                // Check if library is loaded
                if (typeof Html5QrcodeScanner === 'undefined') {
                    console.error('QR Scanner library not loaded');
                    updateCameraStatus('Library scanner tidak tersedia', 'danger');
                    return;
                }
                
                console.log('Creating Html5QrcodeScanner...');
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader",
                    { 
                        fps: 10, 
                        qrbox: { width: 250, height: 250 },
                        aspectRatio: 1.0
                    },
                    false
                );

                console.log('Starting Html5QrcodeScanner render...');
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                isScanning = true;
                
                updateCameraStatus('Kamera aktif - Arahkan ke QR Code', 'success');
                updateScanStatus('Kamera siap untuk memindai QR Code', 'info');
                
                // Hide start button, show stop button
                hideStartButton();
                showStopButton();
                hideRetryButton();
                
                // Reset button disabled states
                document.getElementById('startCameraBtn').disabled = true;
                document.getElementById('stopCameraBtn').disabled = false;
                document.getElementById('retryCameraBtn').disabled = true;
                
                console.log('Html5QrcodeScanner started successfully');
                
            } catch (error) {
                console.error('Error starting camera:', error);
                updateCameraStatus('Gagal memulai kamera: ' + error.message, 'danger');
                showRetryButton();
                showStartButton();
                hideStopButton();
            }
        }
        
        // Stop camera function - using the same method as the working test file
        function stopCamera() {
            console.log('Stopping camera manually...');
            
            if (!isScanning) {
                console.log('Camera not scanning, ignoring stop request');
                return;
            }
            
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().catch(err => {
                    console.error("Error stopping scanner:", err);
                });
                html5QrcodeScanner = null;
            }
            
            isScanning = false;
            
            // Reset UI
            document.getElementById('reader').innerHTML = `
                <div class="text-muted text-center">
                    <i class="bx bx-camera fs-48 d-block mb-2"></i>
                    <div>Kamera akan dimuat di sini...</div>
                    <small class="text-muted">Klik "Mulai Kamera" untuk memulai pemindaian</small>
                </div>
            `;
            
            updateCameraStatus('Kamera dihentikan', 'warning');
            updateScanStatus('Klik "Mulai Kamera" untuk memulai pemindaian', 'info');
            
            // Reset button states
            showStartButton();
            hideStopButton();
            hideRetryButton();
            
            // Reset button disabled states
            document.getElementById('startCameraBtn').disabled = false;
            document.getElementById('stopCameraBtn').disabled = true;
            document.getElementById('retryCameraBtn').disabled = false;
            
            console.log('Camera stopped successfully');
        }
        
        // Helper function to reset UI after stopping camera
        function resetUIAfterStop() {
            try {
                // Reset UI
                const readerElement = document.getElementById('reader');
                if (readerElement) {
                    readerElement.innerHTML = `
                        <div class="text-muted text-center">
                            <i class="bx bx-camera fs-48 d-block mb-2"></i>
                            <div>Kamera akan dimuat di sini...</div>
                            <small class="text-muted">Klik "Mulai Kamera" untuk memulai pemindaian</small>
                        </div>
                    `;
                }
                
                updateCameraStatus('Kamera dihentikan', 'warning');
                updateScanStatus('Klik "Mulai Kamera" untuk memulai pemindaian', 'info');
                
                // Reset button states to allow restart
                showStartButton();
                hideStopButton();
                hideRetryButton();
                
                // Reset button disabled states with null checks
                const startBtn = document.getElementById('startCameraBtn');
                const stopBtn = document.getElementById('stopCameraBtn');
                const retryBtn = document.getElementById('retryCameraBtn');
                
                if (startBtn) startBtn.disabled = false;
                if (stopBtn) stopBtn.disabled = true;
                if (retryBtn) retryBtn.disabled = false;
                
                console.log('Camera stopped manually and buttons reset for restart');
                
                // Clear manual stop flag after a delay
                setTimeout(() => {
                    window.manualStop = false;
                }, 1000);
                
            } catch (error) {
                console.error('Error in resetUIAfterStop:', error);
            }
        }

        // Retry camera function
        function retryCamera() {
            console.log('Retrying camera...');
            stopCamera();
            setTimeout(() => {
                startCamera();
            }, 1000);
        }

        // Success callback
        function onScanSuccess(decodedText, decodedResult) {
            console.log('QR Code detected:', decodedText);
            
            // Stop scanning to prevent multiple submissions
            stopCamera();
            
            updateScanStatus('QR Code terdeteksi! Mengirim data absensi...', 'success');
            
            // Parse QR code data
            try {
                const qrData = JSON.parse(decodedText);
                console.log('Parsed QR data:', qrData);
                
                // Validate QR data format
                if (validateQRData(qrData)) {
                    // Submit attendance
                    submitAttendance(qrData);
                } else {
                    updateScanStatus('Format QR Code tidak valid atau bukan QR guru', 'danger');
                    // After validation fails, ensure buttons are reset properly
                    setTimeout(() => {
                        resetButtonsToInitialState();
                    }, 1000);
                }
                
            } catch (error) {
                console.error('Error parsing QR code:', error);
                updateScanStatus('Format QR Code tidak valid', 'danger');
                // After parsing error, ensure buttons are reset properly
                setTimeout(() => {
                    resetButtonsToInitialState();
                }, 1000);
            }
        }

        // Validate QR data format
        function validateQRData(qrData) {
            console.log('Validating QR data:', qrData);
            
            // Check if it's a teacher QR format (simplified version)
            const requiredFields = ['timetable_id', 'session_id', 'teacher_id', 'checksum'];
            const hasRequiredFields = requiredFields.every(field => qrData.hasOwnProperty(field));
            
            if (!hasRequiredFields) {
                console.error('Missing required fields:', requiredFields.filter(field => !qrData.hasOwnProperty(field)));
                return false;
            }
            
            // Check data types
            if (typeof qrData.timetable_id !== 'number') {
                console.error('timetable_id must be a number');
                return false;
            }
            
            if (typeof qrData.session_id !== 'string' || qrData.session_id.length === 0) {
                console.error('session_id must be a non-empty string');
                return false;
            }
            
            if (typeof qrData.teacher_id !== 'number') {
                console.error('teacher_id must be a number');
                return false;
            }
            
            if (typeof qrData.checksum !== 'string' || qrData.checksum.length === 0) {
                console.error('checksum must be a non-empty string');
                return false;
            }
            
            console.log('QR data validation passed');
            return true;
        }

        // Failure callback
        function onScanFailure(error) {
            // Don't log every scan failure as it's too verbose
            // console.log('Scan failed:', error);
        }

        // Submit attendance function
        function submitAttendance(qrData) {
            console.log('Submitting attendance with data:', qrData);
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            
            // Validate QR data format first
            if (!qrData.timetable_id) {
                updateScanStatus('Format QR Code tidak valid: timetable_id tidak ditemukan', 'danger');
                showRetryButton();
                return;
            }
            
            // Prepare request data - format sesuai dengan yang diharapkan controller
            const requestData = {
                timetable_id: qrData.timetable_id,
                session_token: qrData.session_id || qrData.session_token, // Handle both formats
                timestamp: new Date().toISOString()
            };
            
            console.log('Request data:', requestData);
            
            // Submit to server
            fetch('<?php echo e(route("murid.absensi.scan")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    updateScanStatus('Absensi berhasil! ' + (data.message || ''), 'success');
                    showNotification(data.message || 'Absensi berhasil dicatat!', true);
                    
                    // Refresh attendance history after successful scan
                    setTimeout(() => {
                        loadAttendanceHistory();
                    }, 1000);
                    
                    // Reset buttons after successful attendance
                    setTimeout(() => {
                        resetButtonsToInitialState();
                    }, 2000);
                } else {
                    updateScanStatus('Gagal absensi: ' + (data.message || 'Terjadi kesalahan'), 'danger');
                    showNotification(data.message || 'Terjadi kesalahan', false);
                    
                    // Reset buttons after failed attendance
                    setTimeout(() => {
                        resetButtonsToInitialState();
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error submitting attendance:', error);
                updateScanStatus('Terjadi kesalahan saat mengirim data', 'danger');
                showNotification('Terjadi kesalahan saat mengirim data', false);
                
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
                console.warn('cameraStatus element not found, skipping camera status update');
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
                console.warn('scanStatus element not found, skipping status update');
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
            console.log('Showing start button');
            const btn = document.getElementById('startCameraBtn');
            if (btn) btn.classList.remove('d-none');
        }

        function hideStartButton() {
            console.log('Hiding start button');
            const btn = document.getElementById('startCameraBtn');
            if (btn) btn.classList.add('d-none');
        }

        function showStopButton() {
            console.log('Showing stop button');
            const btn = document.getElementById('stopCameraBtn');
            if (btn) btn.classList.remove('d-none');
        }

        function hideStopButton() {
            console.log('Hiding stop button');
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
            console.log('Resetting buttons to initial state...');
            
            // Check if this is a manual stop - if so, don't interfere
            if (window.manualStop) {
                console.log('Manual stop detected, skipping automatic reset');
                return;
            }
            
            // Show start button, hide stop and retry buttons
            showStartButton();
            hideStopButton();
            hideRetryButton();
            
            // Reset disabled states
            document.getElementById('startCameraBtn').disabled = false;
            document.getElementById('stopCameraBtn').disabled = true;
            document.getElementById('retryCameraBtn').disabled = false;
            
            // Reset camera status
            updateCameraStatus('Siap untuk memulai kamera', 'info');
            
            // Only update scan status if element exists
            const scanStatusElement = document.getElementById('scanStatus');
            if (scanStatusElement) {
                updateScanStatus('Klik "Mulai Kamera" untuk memulai pemindaian', 'info');
            } else {
                console.warn('scanStatus element not found during reset, skipping scan status update');
            }
            
            console.log('Buttons reset to initial state');
        }


        // Basic camera access fallback function
        function tryBasicCameraAccess() {
            console.log('Trying basic camera access...');
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
                    console.log('Basic camera access granted');
                    
                    // Display video directly
                    const readerElement = document.getElementById('reader');
                    readerElement.innerHTML = '<video id="basicVideo" autoplay muted playsinline style="width: 100%; max-width: 500px; height: auto; border-radius: 8px;"></video>';
                    
                    const video = document.getElementById('basicVideo');
                    video.srcObject = stream;
                    
                    // Hide start button, show stop button
                    hideStartButton();
                    showStopButton();
                    
                    updateCameraStatus('Kamera aktif (Mode Dasar) - Arahkan ke QR Code', 'success');
                    updateScanStatus('Kamera aktif dalam mode dasar. QR scanning tidak tersedia.', 'warning');
                    
                    // Store stream for cleanup
                    window.basicCameraStream = stream;
                    isScanning = true;
                })
                .catch(function(error) {
                    console.error('Basic camera access failed:', error);
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
            console.log('Trying basic camera access with device:', deviceId);
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
                    console.log('Basic camera access granted with device:', deviceId);
                    
                    // Display video directly
                    const readerElement = document.getElementById('reader');
                    readerElement.innerHTML = '<video id="basicVideo" autoplay muted playsinline style="width: 100%; max-width: 500px; height: auto; border-radius: 8px;"></video>';
                    
                    const video = document.getElementById('basicVideo');
                    video.srcObject = stream;
                    
                    // Hide start button, show stop button
                    hideStartButton();
                    showStopButton();
                    
                    updateCameraStatus('Kamera aktif (Mode Dasar) - Arahkan ke QR Code', 'success');
                    updateScanStatus('Kamera aktif dalam mode dasar. QR scanning tidak tersedia.', 'warning');
                    
                    // Store stream for cleanup
                    window.basicCameraStream = stream;
                    isScanning = true;
                })
                .catch(function(error) {
                    console.error('Basic camera access failed with device:', error);
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
            const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
            const modalLabel = document.getElementById('notificationModalLabel');
            const modalMessage = document.getElementById('notificationMessage');
            const modalIcon = document.getElementById('notificationIcon');
            
            // Set title and icon based on success/failure
            if (isSuccess) {
                modalLabel.textContent = 'Berhasil';
                modalIcon.innerHTML = '<i class="bx bx-check-circle fs-48 text-success d-block mx-auto"></i>';
            } else {
                modalLabel.textContent = 'Gagal';
                modalIcon.innerHTML = '<i class="bx bx-x-circle fs-48 text-danger d-block mx-auto"></i>';
            }
            
            modalMessage.textContent = message;
            modal.show();
        }

        // Load attendance history function
        function loadAttendanceHistory() {
            fetch('<?php echo e(route("murid.attendance.history")); ?>', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

            // Debug: Log attendance data to console
            console.log('Attendance data received:', attendances);

            tbody.innerHTML = attendances.map((attendance, index) => {
                // Debug: Log each attendance item
                console.log(`Attendance ${index + 1}:`, {
                    status: attendance.status,
                    late_minutes: attendance.late_minutes,
                    notes: attendance.notes
                });
                
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
            // Debug: Log attendance data for formatting
            console.log('Formatting notes for attendance:', {
                status: attendance.status,
                late_minutes: attendance.late_minutes,
                notes: attendance.notes,
                check_in_time: attendance.check_in_time,
                check_out_time: attendance.check_out_time
            });
            
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
                
                // Debug: Log the formatting process
                console.log('Late minutes formatting:', {
                    original: attendance.late_minutes,
                    abs_rounded: lateMinutes,
                    timeFormat: timeFormat
                });
                
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
            
            console.log('Final formatted notes:', notes);
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
                    console.error("Error cleaning up scanner:", err);
                });
            }
        });
        
        // Make functions globally accessible for onclick handlers
        window.startCamera = startCamera;
        window.stopCamera = stopCamera;
        window.retryCamera = retryCamera;
        window.loadCameraList = loadCameraList;
        
        console.log('Functions registered globally:', {
            startCamera: typeof window.startCamera,
            stopCamera: typeof window.stopCamera,
            retryCamera: typeof window.retryCamera,
            loadCameraList: typeof window.loadCameraList
        });
    </script>
<?php $__env->stopPush(); ?>

<!-- Modal Notification -->
<div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="notificationIcon" class="mb-3">
                    <i class="bx bx-check-circle fs-48 text-success d-block mx-auto"></i>
                </div>
                <p class="mb-0" id="notificationMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('layouts.vertical-murid', ['subtitle' => 'Scan QR Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/murid/qr-absensi.blade.php ENDPATH**/ ?>