<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Override - Universal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .time-override-card {
            border-left: 4px solid #ffc107;
        }
        .status-active {
            background: linear-gradient(135deg, #ffc107, #ff8c00);
            color: white;
        }
        .status-inactive {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        .scenario-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .scenario-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card time-override-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bx bx-time me-2"></i>
                            Universal Time Override
                        </h4>
                        <div>
                            <a href="/" class="btn btn-outline-primary btn-sm">
                                <i class="bx bx-home me-1"></i> Home
                            </a>
                            <button class="btn btn-outline-danger btn-sm" onclick="clearTimeOverride()">
                                <i class="bx bx-x me-1"></i> Clear Override
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card" id="statusCard">
                    <div class="card-header" id="statusHeader">
                        <h5 class="mb-0">
                            <i class="bx bx-info-circle me-2"></i>
                            Status Time Override
                        </h5>
                    </div>
                    <div class="card-body" id="statusBody">
                        <!-- Status content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Preset Scenarios -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bx bx-calendar me-2"></i>
                            Preset Time Scenarios
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="scenariosContainer">
                            <!-- Scenarios will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Time -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bx bx-edit me-2"></i>
                            Custom Time Override
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="customTimeForm">
                            <div class="mb-3">
                                <label for="customDate" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="customDate" name="date" required>
                            </div>
                            <div class="mb-3">
                                <label for="customTime" class="form-label">Waktu</label>
                                <input type="time" class="form-control" id="customTime" name="time" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-check me-1"></i> Set Custom Time
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bx bx-link me-2"></i>
                            Quick Access - Test Pages
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="d-grid">
                                    <a href="/guru/dashboard" class="btn btn-outline-primary">
                                        <i class="bx bx-home me-1"></i> Dashboard Guru
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-grid">
                                    <a href="/guru/absensi/scan" class="btn btn-outline-info">
                                        <i class="bx bx-qr-scan me-1"></i> Scan QR
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-grid">
                                    <a href="/guru/jadwal-mengajar" class="btn btn-outline-secondary">
                                        <i class="bx bx-calendar me-1"></i> Jadwal Mengajar
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-grid">
                                    <a href="/admin/jadwal-pelajaran" class="btn btn-outline-success">
                                        <i class="bx bx-cog me-1"></i> Admin Jadwal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="bx bx-help-circle me-2"></i>
                            Cara Penggunaan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>1. Set Time Override</h6>
                                <ol>
                                    <li>Pilih preset scenario atau set custom time</li>
                                    <li>Klik "Apply Time" atau "Set Custom Time"</li>
                                    <li>Time override akan aktif untuk semua halaman</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h6>2. Test Halaman</h6>
                                <ol>
                                    <li>Klik link di "Quick Access" untuk test halaman</li>
                                    <li>Semua halaman akan menggunakan waktu yang di-override</li>
                                    <li>Klik "Clear Override" untuk kembali ke waktu real</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load initial status
        function loadStatus() {
            fetch('/time-override/status')
                .then(response => response.json())
                .then(data => {
                    updateStatusDisplay(data);
                })
                .catch(error => {
                    console.error('Error loading status:', error);
                });
        }

        // Update status display
        function updateStatusDisplay(data) {
            const statusCard = document.getElementById('statusCard');
            const statusHeader = document.getElementById('statusHeader');
            const statusBody = document.getElementById('statusBody');

            if (data.is_active) {
                statusCard.className = 'card status-active';
                statusHeader.innerHTML = `
                    <h5 class="mb-0">
                        <i class="bx bx-time me-2"></i>
                        Time Override Active
                    </h5>
                `;
                statusBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Override Time:</strong><br>
                            ${data.override_datetime}
                        </div>
                        <div class="col-md-6">
                            <strong>Real Time:</strong><br>
                            ${data.real_time}
                        </div>
                    </div>
                `;
            } else {
                statusCard.className = 'card status-inactive';
                statusHeader.innerHTML = `
                    <h5 class="mb-0">
                        <i class="bx bx-check-circle me-2"></i>
                        Real Time Mode
                    </h5>
                `;
                statusBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Current Time:</strong><br>
                            ${data.current_time}
                        </div>
                    </div>
                `;
            }
        }

        // Load scenarios
        function loadScenarios() {
            fetch('/time-override/scenarios')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('scenariosContainer');
                    container.innerHTML = '';

                    data.scenarios.forEach(scenario => {
                        const col = document.createElement('div');
                        col.className = 'col-md-6 mb-3';
                        col.innerHTML = `
                            <div class="card scenario-card h-100" onclick="applyScenario('${scenario.date}', '${scenario.time}')">
                                <div class="card-body">
                                    <h6 class="card-title">${scenario.name}</h6>
                                    <p class="card-text">
                                        <small class="text-muted">${scenario.date} ${scenario.time}</small><br>
                                        <small>${scenario.description}</small>
                                    </p>
                                </div>
                            </div>
                        `;
                        container.appendChild(col);
                    });
                });
        }

        // Apply scenario
        function applyScenario(date, time) {
            const formData = new FormData();
            formData.append('date', date);
            formData.append('time', time);

            fetch('/time-override/set-time', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadStatus();
                    showAlert('Time override berhasil diset!', 'success');
                } else {
                    showAlert('Gagal set time override!', 'danger');
                }
            });
        }

        // Clear time override
        function clearTimeOverride() {
            if (confirm('Apakah Anda yakin ingin menghapus time override?')) {
                fetch('/time-override/clear-time', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadStatus();
                        showAlert('Time override berhasil dihapus!', 'success');
                    } else {
                        showAlert('Gagal hapus time override!', 'danger');
                    }
                });
            }
        }

        // Custom time form
        document.getElementById('customTimeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            applyScenario(formData.get('date'), formData.get('time'));
        });

        // Show alert
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 3000);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStatus();
            loadScenarios();
            
            // Auto refresh status every 30 seconds
            setInterval(loadStatus, 30000);
        });
    </script>
</body>
</html>
