@extends('layouts.vertical-murid', ['subtitle' => 'Scan QR Absensi'])

@section('css')
    @vite(['resources/css/murid/qr-absensi.css'])
@endsection

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Siswa', 'subtitle' => 'Scan QR Absensi'])

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

    {{-- Riwayat Kehadiran Hari Ini --}}
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
                                {{-- Data akan dimuat via JavaScript --}}
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
                    
                    {{-- Summary Card --}}
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

    {{-- Modal Notifikasi --}}
    @include('components.pengaturan.notification-modal', [
        'modalId' => 'notificationModal',
        'modalLabelId' => 'notificationModalLabel',
        'messageId' => 'notificationMessage',
        'errorsId' => 'notificationErrors',
        'errorsBodyId' => 'notificationErrorsBody',
        'modalSize' => '',
        'showErrorsTable' => false,
        'showIcon' => true,
        'iconId' => 'notificationIcon'
    ])
@endsection

@push('scripts')
    <!-- HTML5 QR Code Scanner Library -->
    <!-- Prevent duplicate loading using JavaScript check -->
    <script>
        // Prevent duplicate library loading
        (function() {
            // Check if library is already loaded
            if (typeof Html5Qrcode !== 'undefined' || typeof Html5QrcodeScanner !== 'undefined') {
                window.html5QrcodeLoaded = true;
                return;
            }
            
            // Check if script tag already exists
            const existingScript = document.querySelector('script[src*="html5-qrcode"]');
            if (existingScript) {
                return;
            }
            
            // Load library only once
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
            script.async = true;
            script.onerror = function() {
                window.html5QrcodeLoadError = true;
            };
            script.onload = function() {
                window.html5QrcodeLoaded = true;
            };
            document.head.appendChild(script);
        })();
    </script>
    
    {{-- Inject routes ke window object untuk digunakan oleh JavaScript --}}
    <script>
        window.qrAbsensiRoutes = {
            scan: '{{ route("murid.absensi.scan") }}',
            history: '{{ route("murid.attendance.history") }}'
        };
    </script>

    @vite(['resources/js/murid/qr-absensi.js'])
@endpush
