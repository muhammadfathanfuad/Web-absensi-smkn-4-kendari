@extends('layouts.vertical-guru', ['subtitle' => 'Scan QR Absensi'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Scan QR', 'subtitle' => 'Absensi'])

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:qr-code-outline" class="fs-20 me-2"></iconify-icon>
                        Generate QR code 
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Jadwal Mata Pelajaran</label>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="dropdownmapel"
                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true">
                                Pilih Mata Pelajaran
                            </button>
                            <div class="dropdown-menu w-100" aria-labelledby="dropdownmapel" style="max-width: 100%; word-wrap: break-word;">
                                @forelse ($jadwalHariIni as $jadwal)
                                    <button class="dropdown-item text-wrap" type="button" 
                                        data-timetable-id="{{ $jadwal->id }}" 
                                        data-subject-name="{{ $jadwal->classSubject->subject->name }}" 
                                        data-class-name="{{ $jadwal->classSubject->class->name }}" 
                                        data-time="{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}"
                                        style="white-space: normal; text-align: left; min-height: auto; padding: 0.5rem 1rem;">
                                        {{ $jadwal->classSubject->subject->name }} - {{ $jadwal->classSubject->class->name }} ({{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }})
                                    </button>
                                @empty
                                    <span class="dropdown-item-text">Tidak ada jadwal mengajar hari ini.</span>
                                @endforelse
                                
                                @if($jadwalHariIni->count() > 0)
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item d-flex align-items-center" type="button" onclick="resetDropdown()">
                                        <iconify-icon icon="solar:close-circle-outline" class="fs-16 me-2" style="color: inherit;"></iconify-icon>
                                        <span>Batalkan Pilihan</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" id="timetable_id" name="timetable_id" value="">
                        <div class="invalid-feedback">Silakan pilih jadwal terlebih dahulu.</div>
                    </div>

                    <div class="text-center">
                        <div id="qrcode" class="d-flex justify-content-center align-items-center mx-auto" style="display:none; width:280px; height:280px; border: 2px dashed #dee2e6; border-radius: 12px; background-color: #f8f9fa; position: relative; min-height: 280px;">
                            <div class="text-muted text-center">
                                <iconify-icon icon="solar:qr-code-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                QR Code akan muncul di sini...
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <p class="mb-2" id="qrInfoText"></p>
                            <div class="text-center">
                                <button id="stopSession" class="btn btn-danger btn-lg d-flex align-items-center justify-content-center mx-auto">
                                    <iconify-icon icon="solar:stop-circle-outline" class="fs-18 me-2"></iconify-icon>
                                Hentikan Sesi Absensi
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian kanan tabel hasil pindaian -->
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:list-check-outline" class="fs-20 me-2"></iconify-icon>
                        Hasil Pindaian Hari Ini
                    </h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                        <table class="table table-hover table-centered" id="scan-results-table">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Jam Masuk</th>
                                    <th scope="col">Jam Keluar</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="initial-message-row">
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted text-center">
                                            <iconify-icon icon="solar:list-check-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                            Silakan pilih mata pelajaran untuk melihat data.
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- Rekap Riwayat Absensi Hari Ini --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:history-outline" class="fs-20 me-2"></iconify-icon>
                        Rekap Riwayat Absensi Hari Ini
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
                                    <th scope="col">Total Siswa</th>
                                    <th scope="col">Hadir</th>
                                    <th scope="col">Terlambat</th>
                                    <th scope="col">Izin</th>
                                    <th scope="col">Sakit</th>
                                    <th scope="col">Alpa</th>
                                    <th scope="col">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                    <iconify-icon icon="solar:book-outline" class="fs-12"></iconify-icon>
                                                </span>
                                            </div>
                                            Matematika
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary py-1 px-2">XII RPL 1</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">07:00 - 08:30</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info py-1 px-2">30</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success py-1 px-2">
                                            <i class="bx bxs-circle text-success me-1"></i>25
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                            <i class="bx bxs-circle text-warning me-1"></i>3
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info py-1 px-2">
                                            <i class="bx bxs-circle text-info me-1"></i>1
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                            <i class="bx bxs-circle text-warning me-1"></i>1
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger py-1 px-2">
                                            <i class="bx bxs-circle text-danger me-1"></i>0
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">83.33%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title rounded-circle bg-success-subtle text-success">
                                                    <iconify-icon icon="solar:book-outline" class="fs-12"></iconify-icon>
                                                </span>
                                            </div>
                                            Bahasa Indonesia
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary py-1 px-2">XII RPL 1</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">08:30 - 10:00</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info py-1 px-2">30</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success py-1 px-2">
                                            <i class="bx bxs-circle text-success me-1"></i>28
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                            <i class="bx bxs-circle text-warning me-1"></i>1
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info py-1 px-2">
                                            <i class="bx bxs-circle text-info me-1"></i>0
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                            <i class="bx bxs-circle text-warning me-1"></i>1
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger py-1 px-2">
                                            <i class="bx bxs-circle text-danger me-1"></i>0
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">93.33%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title rounded-circle bg-warning-subtle text-warning">
                                                    <iconify-icon icon="solar:book-outline" class="fs-12"></iconify-icon>
                                                </span>
                                            </div>
                                            Pemrograman Web
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary py-1 px-2">XII RPL 2</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">10:00 - 11:30</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info py-1 px-2">28</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success py-1 px-2">
                                            <i class="bx bxs-circle text-success me-1"></i>20
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                            <i class="bx bxs-circle text-warning me-1"></i>5
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info py-1 px-2">
                                            <i class="bx bxs-circle text-info me-1"></i>2
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                            <i class="bx bxs-circle text-warning me-1"></i>1
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger py-1 px-2">
                                            <i class="bx bxs-circle text-danger me-1"></i>0
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-warning">71.43%</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Stop Session -->
    <div class="modal fade" id="stopSessionModal" tabindex="-1" aria-labelledby="stopSessionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stopSessionModalLabel">Konfirmasi Hentikan Sesi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <iconify-icon icon="solar:danger-triangle-outline" class="fs-48 text-warning"></iconify-icon>
                    </div>
                    <p class="text-center mb-0">Apakah Anda yakin ingin menghentikan sesi absensi?</p>
                    <p class="text-muted text-center small mt-2">Tindakan ini tidak dapat dibatalkan dan akan menghentikan semua proses absensi yang sedang berlangsung.</p>
                    <input type="hidden" id="stopSessionToken">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger d-flex align-items-center" id="confirmStopSessionButton">
                        <iconify-icon icon="solar:stop-circle-outline" class="fs-16 me-2"></iconify-icon>
                        Ya, Hentikan Sesi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi -->
    <div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <iconify-icon id="notificationIcon" class="fs-48"></iconify-icon>
                    </div>
                    <p class="mb-0 text-center" id="notificationMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>

    <style>
        #stopSession {
            display: none !important;
        }
        #stopSession.show {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        #stopSession iconify-icon {
            display: inline-flex !important;
            align-items: center !important;
            vertical-align: middle !important;
        }
        
        #confirmStopSessionButton iconify-icon {
            display: inline-flex !important;
            align-items: center !important;
            vertical-align: middle !important;
        }
        
        /* General iconify-icon alignment for buttons */
        .btn iconify-icon {
            display: inline-flex !important;
            align-items: center !important;
            vertical-align: middle !important;
        }
        
        #qrcode canvas {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 8px;
        }
        
        #qrcode {
            overflow: hidden;
        }
    </style>

    <script>
        // Pastikan QRCode library sudah ter-load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Checking QRCode library...');
            console.log('qrcode-generator type:', typeof qrcode);
            
            // Cek apakah qrcode-generator library tersedia
            if (typeof qrcode !== 'undefined') {
                console.log('qrcode-generator library is ready');
                window.QRCodeLoaded = true;
                window.QRCodeType = 'qrcode-generator';
            } else {
                console.error('qrcode-generator library not available');
                window.QRCodeLoaded = false;
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            // Pastikan tombol stop session tersembunyi saat halaman dimuat
            const stopSessionBtn = document.getElementById('stopSession');
            if (stopSessionBtn) {
                stopSessionBtn.classList.remove('show');
                console.log('Stop session button hidden on page load');
                console.log('Button classes after removing show:', stopSessionBtn.className);
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
            document.getElementById('confirmStopSessionButton').addEventListener('click', function() {
                console.log('=== Confirm stop session button clicked ===');
                const sessionToken = document.getElementById('stopSessionToken').value;
                console.log('Session token from hidden input:', sessionToken);
                console.log('Session token type:', typeof sessionToken);
                console.log('Session token length:', sessionToken ? sessionToken.length : 'null');
                
                if (sessionToken) {
                    console.log('Calling stopAttendanceSession with token:', sessionToken);
                    stopAttendanceSession(sessionToken);
                } else {
                    console.error('No session token found in hidden input');
                }
            });

            // Event listener untuk modal notifikasi
            const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
            
            // Ensure close buttons work
            document.querySelector('#notificationModal .btn-close').addEventListener('click', () => {
                notificationModal.hide();
            });
            document.querySelector('#notificationModal .btn-light').addEventListener('click', () => {
                notificationModal.hide();
            });
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
            dropdownmapel.textContent = 'Pilih Mata Pelajaran';
            
            // Reset hidden input
            timetableIdInput.value = '';
            
            // Sembunyikan QR Code
            qrCodeContainer.style.display = 'none';
            qrCodeContainer.innerHTML = `
                <div class="text-muted text-center">
                    <iconify-icon icon="solar:qr-code-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                    QR Code akan muncul di sini...
                </div>
            `;
            
            // Sembunyikan tombol stop session
            if (stopSessionBtn) {
                stopSessionBtn.classList.remove('show');
                console.log('Stop session button hidden');
                console.log('Button classes after removing show:', stopSessionBtn.className);
            }
            
            // Reset info text
            qrInfoText.textContent = '';
            
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
                console.log('Confirm stop session button reset');
            }
            
            // Reset hidden input session token
            const stopSessionTokenInput = document.getElementById('stopSessionToken');
            if (stopSessionTokenInput) {
                stopSessionTokenInput.value = '';
                console.log('Stop session token input reset');
            }
            
            // Reset tabel hasil scan
            const tbody = document.querySelector('#scan-results-table tbody');
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


        // Fungsi helper untuk generate QR code dengan qrcode-generator
        function generateQRCodeWithLibrary(container, data, options = {}) {
            if (typeof qrcode === 'undefined') {
                return Promise.reject(new Error('qrcode-generator library not available'));
            }
            
            try {
                const qr = qrcode(0, 'M');
                qr.addData(JSON.stringify(data));
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
            console.log('generateQRCode called with:', { timetableId, subjectName, className, time });

            if (!timetableId) {
                console.error('timetableId is empty or undefined');
                return;
            }

            const qrCodeContainer = document.getElementById('qrcode');
            const qrInfoText = document.getElementById('qrInfoText');
            const stopSessionBtn = document.getElementById('stopSession');
            
            console.log('QR Code container found:', qrCodeContainer);
            console.log('qrcode-generator library available:', typeof qrcode);
            console.log('QRCode loaded flag:', window.QRCodeLoaded);
            
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

            // Panggil endpoint server untuk generate QR Code
            fetch('{{ route("guru.absensi.generate-qr") }}', {
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
                
                console.log('QR Data received:', data);
                
                // Simpan session token untuk stop session
                window.currentSessionToken = data.session_id;
                
                // Cek apakah qrcode-generator library tersedia
                if (!window.QRCodeLoaded || typeof qrcode === 'undefined') {
                    console.error('qrcode-generator library not available');
                    qrCodeContainer.innerHTML = `
                        <div class="text-center text-danger">
                            <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                            Library QR Code tidak tersedia. Silakan refresh halaman.
                        </div>
                    `;
                    return;
                }

                // Generate QR Code dengan library yang tersedia
                console.log('Generating QR Code with data:', data);
                console.log('QR Code container element:', qrCodeContainer);
                console.log('Using QRCode library type:', window.QRCodeType);
                
                generateQRCodeWithLibrary(qrCodeContainer, data, {
                    cellSize: 4,
                    margin: 2,
                    darkColor: '#000000',
                    lightColor: '#ffffff'
                })
                .then(() => {
                    console.log('QR Code generated successfully');
                    
                    // Hanya tampilkan tombol stop session jika QR code berhasil dibuat
                    showStopSessionButtons(data.session_id, timetableId);
                })
                .catch((error) => {
                    console.error('QR Code generation failed:', error);
                    qrCodeContainer.innerHTML = `
                        <div class="text-center text-danger">
                            <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                            Gagal menghasilkan QR Code: ${error.message}
                        </div>
                    `;
                    
                    // Jangan tampilkan tombol stop session jika QR code gagal dibuat
                    console.log('QR Code generation failed - not showing stop session button');
                });
            })
            .catch(error => {
                console.error('Error generating QR Code:', error);
                qrCodeContainer.innerHTML = `
                    <div class="text-center text-danger">
                        <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                        ${error.message}
                    </div>
                `;
                
                // Jangan tampilkan tombol stop session jika ada error dalam fetch request
                console.log('Fetch request failed - not showing stop session button');
            });
        }

        // Fungsi helper untuk menampilkan tombol stop session
        function showStopSessionButtons(sessionId, timetableId) {
            console.log('=== showStopSessionButtons called ===');
            console.log('Session ID:', sessionId);
            console.log('Timetable ID:', timetableId);
            
            // Validasi: pastikan sessionId dan timetableId ada
            if (!sessionId || !timetableId) {
                console.error('Missing sessionId or timetableId - not showing stop session button');
                console.log('sessionId:', sessionId);
                console.log('timetableId:', timetableId);
                return;
            }
            
            const stopSessionBtn = document.getElementById('stopSession');
            console.log('Stop session button element:', stopSessionBtn);
            
            // Tampilkan tombol stop session di bawah QR code
            if (stopSessionBtn) {
                console.log('Adding show class to stop session button');
                stopSessionBtn.classList.add('show');
                console.log('Button classes after adding show:', stopSessionBtn.className);
                console.log('Button style display:', stopSessionBtn.style.display);
                
                stopSessionBtn.onclick = function() {
                    console.log('Stop session button clicked');
                    showStopSessionModal(sessionId); // sessionId is actually the session token
                };
                console.log('Stop session button displayed successfully');
            } else {
                console.error('Stop session button not found');
                return;
            }
            
            // Mulai polling untuk update hasil scan
            startScanResultsPolling(timetableId);
        }

        // Fungsi untuk menampilkan modal konfirmasi stop session
        function showStopSessionModal(sessionToken) {
            console.log('=== showStopSessionModal called ===');
            console.log('Session token received:', sessionToken);
            console.log('Current session token from window:', window.currentSessionToken);
            
            if (!sessionToken) {
                console.error('No session token provided');
                return;
            }

            // Set session token ke hidden input
            const hiddenInput = document.getElementById('stopSessionToken');
            if (hiddenInput) {
                hiddenInput.value = sessionToken;
                console.log('Session token set to hidden input:', hiddenInput.value);
            } else {
                console.error('Hidden input stopSessionToken not found');
                return;
            }
            
            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('stopSessionModal'));
            modal.show();
            console.log('Stop session modal shown');
        }

        // Fungsi untuk stop attendance session
        function stopAttendanceSession(sessionToken) {
            console.log('=== stopAttendanceSession called ===');
            console.log('Session token received:', sessionToken);
            console.log('Session token type:', typeof sessionToken);
            console.log('Session token length:', sessionToken ? sessionToken.length : 'null');
            
            if (!sessionToken) {
                console.error('No session token provided');
                return;
            }

            // Tampilkan loading state pada tombol modal
            const confirmBtn = document.getElementById('confirmStopSessionButton');
            if (!confirmBtn) {
                console.error('Confirm stop session button not found');
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
            console.log('Button set to loading state');

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            console.log('CSRF Token:', csrfToken ? csrfToken.getAttribute('content') : 'Not found');
            console.log('Stop session URL:', '{{ route("guru.absensi.stop-session") }}');

            const requestBody = {
                session_token: sessionToken
            };
            console.log('Request body:', requestBody);
            console.log('Request body JSON:', JSON.stringify(requestBody));

            fetch('{{ route("guru.absensi.stop-session") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                },
                body: JSON.stringify(requestBody)
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    console.log('Session stopped successfully');
                    
                    // Reset tombol terlebih dahulu
                    confirmBtn.innerHTML = originalText;
                    confirmBtn.disabled = false;
                    console.log('Button reset after successful stop');
                    
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('stopSessionModal'));
                    modal.hide();
                    
                    // Tampilkan notifikasi sukses
                    showNotification('Sesi absensi berhasil dihentikan', 'success');
                    resetDropdown();
                } else {
                    console.error('Server returned error:', data.error);
                    showNotification('Gagal menghentikan sesi: ' + (data.error || 'Unknown error'), 'error');
                    // Reset tombol jika gagal
                    if (confirmBtn) {
                        confirmBtn.innerHTML = originalText;
                        confirmBtn.disabled = false;
                        console.log('Button reset after server error');
                    }
                }
            })
            .catch(error => {
                console.error('Error stopping session:', error);
                console.error('Error details:', {
                    message: error.message,
                    stack: error.stack,
                    name: error.name
                });
                showNotification('Terjadi kesalahan saat menghentikan sesi: ' + error.message, 'error');
                // Reset tombol jika error
                if (confirmBtn) {
                    confirmBtn.innerHTML = originalText;
                    confirmBtn.disabled = false;
                    console.log('Button reset after error');
                }
            });
        }

        // Fungsi untuk menampilkan notifikasi dengan modal
        function showNotification(message, type = 'info') {
            const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
            const modalLabel = document.getElementById('notificationModalLabel');
            const modalMessage = document.getElementById('notificationMessage');
            const modalIcon = document.getElementById('notificationIcon');
            
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
            
            // Tampilkan modal
            modal.show();
        }

        // Fungsi untuk polling hasil scan
        function startScanResultsPolling(timetableId) {
            // Hentikan polling sebelumnya jika ada
            if (window.scanResultsInterval) {
                clearInterval(window.scanResultsInterval);
            }

            // Polling setiap 3 detik
            window.scanResultsInterval = setInterval(function() {
                fetch(`{{ route('guru.absensi.results', '') }}/${timetableId}`)
                .then(response => response.json())
                .then(data => {
                    updateScanResultsTable(data);
                })
                .catch(error => {
                    console.error('Error fetching scan results:', error);
                });
            }, 3000);
        }

        // Fungsi untuk update tabel hasil scan
        function updateScanResultsTable(data) {
            const tbody = document.querySelector('#scan-results-table tbody');
            
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
    </script>
@endpush
