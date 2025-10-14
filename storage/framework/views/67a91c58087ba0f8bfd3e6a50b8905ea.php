

<?php $__env->startSection('title', 'Testing Mode - Universal Time Override'); ?>

<?php $__env->startSection('content'); ?>


<?php echo $__env->make('layouts.partials/page-title', ['title' => 'Universal Testing', 'subtitle' => 'Time Override'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bx bx-test-tube me-2"></i>
                        Universal Testing Mode
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="alert alert-info">
                                <h5>Testing Mode untuk Semua Halaman</h5>
                                <p>Gunakan mode testing ini untuk menguji halaman-halaman yang bergantung pada waktu real-time di malam hari atau waktu yang tidak sesuai dengan jadwal normal.</p>
                                
                                <div class="mt-3">
                                    <strong>Status Saat Ini:</strong><br>
                                    <span id="testingStatus">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column gap-2">
                                <button class="btn btn-primary" onclick="showTimeSelector()">
                                    <i class="bx bx-time me-1"></i> Set Testing Time
                                </button>
                                <button class="btn btn-danger" onclick="clearTestingMode()">
                                    <i class="bx bx-x me-1"></i> Clear Testing Mode
                                </button>
                                <a href="<?php echo e(route('guru.dashboard')); ?>" class="btn btn-success">
                                    <i class="bx bx-home me-1"></i> Dashboard Guru
                                </a>
                                <a href="<?php echo e(route('guru.absensi.scan')); ?>" class="btn btn-info">
                                    <i class="bx bx-qr-scan me-1"></i> Scan QR
                                </a>
                                <a href="<?php echo e(route('guru.jadwal-mengajar')); ?>" class="btn btn-secondary">
                                    <i class="bx bx-calendar me-1"></i> Jadwal Mengajar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Dashboard Guru</h5>
                </div>
                <div class="card-body">
                    <p>Test dashboard guru dengan jadwal mengajar real-time</p>
                    <a href="<?php echo e(route('guru.dashboard')); ?>" class="btn btn-primary">Test Dashboard</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Scan QR Absensi</h5>
                </div>
                <div class="card-body">
                    <p>Test scanner QR untuk absensi siswa</p>
                    <a href="<?php echo e(route('guru.absensi.scan')); ?>" class="btn btn-info">Test Scanner</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Jadwal Mengajar</h5>
                </div>
                <div class="card-body">
                    <p>Test halaman jadwal mengajar guru</p>
                    <a href="<?php echo e(route('guru.jadwal-mengajar')); ?>" class="btn btn-secondary">Test Jadwal</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Testing Instructions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Cara Penggunaan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>1. Set Testing Time</h6>
                            <ol>
                                <li>Klik "Set Testing Time"</li>
                                <li>Pilih preset waktu atau custom time</li>
                                <li>Pilih halaman target untuk testing</li>
                                <li>Klik "Apply Time"</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <h6>2. Test Halaman</h6>
                            <ol>
                                <li>Buka halaman yang ingin ditest</li>
                                <li>Perhatikan status dan waktu yang ditampilkan</li>
                                <li>Test berbagai skenario waktu</li>
                                <li>Clear testing mode setelah selesai</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Time Selector Modal -->
<div class="modal fade" id="timeSelectorModal" tabindex="-1" aria-labelledby="timeSelectorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timeSelectorModalLabel">Set Testing Time</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Preset Waktu:</h6>
                        <div id="timeScenarios">
                            <!-- Time scenarios will be loaded here -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Custom Time & Target:</h6>
                        <form id="customTimeForm">
                            <div class="mb-3">
                                <label for="testDate" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="testDate" name="test_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="testTime" class="form-label">Waktu</label>
                                <input type="time" class="form-control" id="testTime" name="test_time" required>
                            </div>
                            <div class="mb-3">
                                <label for="targetUrl" class="form-label">Target URL</label>
                                <select class="form-select" id="targetUrl" name="target_url" required>
                                    <option value="">Pilih Halaman Target</option>
                                    <option value="<?php echo e(route('guru.dashboard')); ?>">Dashboard Guru</option>
                                    <option value="<?php echo e(route('guru.absensi.scan')); ?>">Scan QR Absensi</option>
                                    <option value="<?php echo e(route('guru.jadwal-mengajar')); ?>">Jadwal Mengajar</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Apply Time</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Load testing status
function loadTestingStatus() {
    fetch('/testing/status')
        .then(response => response.json())
        .then(data => {
            const statusDiv = document.getElementById('testingStatus');
            if (data.testing_mode) {
                statusDiv.innerHTML = `
                    <span class="badge bg-warning">Testing Mode Active</span><br>
                    <strong>Testing Time:</strong> ${data.test_datetime}<br>
                    <strong>Real Time:</strong> ${data.current_real_time}
                `;
            } else {
                statusDiv.innerHTML = `
                    <span class="badge bg-success">Real Time Mode</span><br>
                    <strong>Current Time:</strong> ${data.current_real_time}
                `;
            }
        });
}

// Time selector functions
function showTimeSelector() {
    loadTimeScenarios();
    new bootstrap.Modal(document.getElementById('timeSelectorModal')).show();
}

function loadTimeScenarios() {
    fetch('/testing/time-scenarios')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('timeScenarios');
            container.innerHTML = '';
            
            data.scenarios.forEach(scenario => {
                const button = document.createElement('button');
                button.className = 'btn btn-outline-primary btn-sm mb-2 d-block w-100 text-start';
                button.innerHTML = `
                    <strong>${scenario.name}</strong><br>
                    <small>${scenario.date} ${scenario.time}</small><br>
                    <small class="text-muted">${scenario.description}</small>
                `;
                button.onclick = () => applyTime(scenario.date, scenario.time);
                container.appendChild(button);
            });
        });
}

function applyTime(date, time) {
    const targetUrl = document.getElementById('targetUrl').value || '<?php echo e(route("guru.dashboard")); ?>';
    
    const formData = new FormData();
    formData.append('test_date', date);
    formData.append('test_time', time);
    formData.append('target_url', targetUrl);
    
    fetch('/testing/set-time', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => {
        if (response.ok) {
            window.location.href = targetUrl;
        }
    });
}

function clearTestingMode() {
    if (confirm('Apakah Anda yakin ingin menghapus testing mode?')) {
        fetch('/testing/clear-time', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            loadTestingStatus();
        });
    }
}

// Custom time form
document.getElementById('customTimeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    applyTime(formData.get('test_date'), formData.get('test_time'));
});

// Load status on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTestingStatus();
    
    // Auto refresh status every 30 seconds
    setInterval(loadTestingStatus, 30000);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Universal Testing'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/testing/index.blade.php ENDPATH**/ ?>