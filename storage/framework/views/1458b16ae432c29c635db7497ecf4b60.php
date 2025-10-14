

<?php $__env->startSection('title', 'Testing Dashboard Guru'); ?>

<?php $__env->startSection('content'); ?>


<?php echo $__env->make('layouts.partials/page-title', ['title' => 'Testing Dashboard', 'subtitle' => 'Guru'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="container-fluid">
    <!-- Testing Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bx bx-test-tube me-2"></i>
                        Mode Testing Dashboard Guru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="alert alert-info">
                                <strong>Informasi Testing:</strong><br>
                                <strong>Waktu Saat Ini:</strong> <?php echo e($testingInfo['current_time']); ?><br>
                                <strong>Hari:</strong> <?php echo e(\Carbon\Carbon::parse($testingInfo['current_time'])->locale('id')->dayName); ?><br>
                                <strong>Mode:</strong> <?php echo e($testingInfo['is_testing_mode'] ? 'Testing Mode' : 'Real Time'); ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column gap-2">
                                <button class="btn btn-primary" onclick="showTimeSelector()">
                                    <i class="bx bx-time me-1"></i> Pilih Waktu Testing
                                </button>
                                <button class="btn btn-success" onclick="generateMockData()">
                                    <i class="bx bx-plus me-1"></i> Generate Mock Data
                                </button>
                                <button class="btn btn-danger" onclick="clearMockData()">
                                    <i class="bx bx-trash me-1"></i> Clear Mock Data
                                </button>
                                <a href="<?php echo e(route('guru.dashboard')); ?>" class="btn btn-secondary">
                                    <i class="bx bx-home me-1"></i> Dashboard Normal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="row">
        <!-- Jadwal Mengajar Hari Ini -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Jadwal Mengajar Hari Ini</h5>
                </div>
                <div class="card-body">
                    <?php if($jadwalMengajar->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Jam</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $jadwalMengajar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="<?php echo e($jadwal['status'] == 'Berlangsung' ? 'table-success' : ($jadwal['status'] == 'Selesai' ? 'table-secondary' : '')); ?>">
                                            <td><?php echo e($jadwal['jam']); ?></td>
                                            <td><?php echo e($jadwal['mapel']); ?></td>
                                            <td><?php echo e($jadwal['kelas']); ?></td>
                                            <td>
                                                <span class="badge 
                                                    <?php echo e($jadwal['status'] == 'Berlangsung' ? 'bg-success' : 
                                                       ($jadwal['status'] == 'Selesai' ? 'bg-secondary' : 'bg-primary')); ?>">
                                                    <?php echo e($jadwal['status']); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bx bx-calendar-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Tidak ada jadwal mengajar hari ini</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Jam Mengajar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Jam Mengajar Hari Ini</h5>
                </div>
                <div class="card-body text-center">
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: <?php echo e($jamMengajarData['persentase']); ?>%"
                             aria-valuenow="<?php echo e($jamMengajarData['persentase']); ?>" 
                             aria-valuemin="0" aria-valuemax="100">
                            <?php echo e($jamMengajarData['persentase']); ?>%
                        </div>
                    </div>
                    <p class="text-muted"><?php echo e($jamMengajarData['label']); ?></p>
                </div>
            </div>

            <!-- Siswa Izin -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Siswa Izin</h5>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $siswaIzin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong><?php echo e($siswa['nama']); ?></strong><br>
                                <small class="text-muted"><?php echo e($siswa['kelas']); ?></small>
                            </div>
                            <span class="badge bg-warning"><?php echo e($siswa['keterangan']); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mt-4">
        <!-- Statistik Kehadiran -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Statistik Kehadiran</h5>
                </div>
                <div class="card-body">
                    <canvas id="kehadiranChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Riwayat Mengajar -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Riwayat Mengajar Bulan Ini</h5>
                </div>
                <div class="card-body">
                    <canvas id="riwayatChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengumuman -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Pengumuman</h5>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="<?php echo e($item['icon']); ?> text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h6 class="mb-1"><?php echo e($item['judul']); ?></h6>
                                <small class="text-muted"><?php echo e($item['tanggal']); ?></small>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <h5 class="modal-title" id="timeSelectorModalLabel">Pilih Waktu Testing</h5>
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
                        <h6>Custom Time:</h6>
                        <form id="customTimeForm">
                            <div class="mb-3">
                                <label for="testDate" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="testDate" name="test_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="testTime" class="form-label">Waktu</label>
                                <input type="time" class="form-control" id="testTime" name="test_time" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Apply Custom Time</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js configurations
const kehadiranCtx = document.getElementById('kehadiranChart').getContext('2d');
new Chart(kehadiranCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($statistikKehadiranData['labels'], 15, 512) ?>,
        datasets: [{
            data: <?php echo json_encode($statistikKehadiranData['series'], 15, 512) ?>,
            backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

const riwayatCtx = document.getElementById('riwayatChart').getContext('2d');
new Chart(riwayatCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($riwayatMengajarData['categories'], 15, 512) ?>,
        datasets: [{
            label: <?php echo json_encode($riwayatMengajarData['series'][0]['name'], 15, 512) ?>,
            data: <?php echo json_encode($riwayatMengajarData['series'][0]['data'], 15, 512) ?>,
            backgroundColor: '#007bff'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Time selector functions
function showTimeSelector() {
    loadTimeScenarios();
    new bootstrap.Modal(document.getElementById('timeSelectorModal')).show();
}

function loadTimeScenarios() {
    fetch('/guru/testing/time-scenarios')
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
    const url = new URL(window.location);
    url.searchParams.set('test_date', date);
    url.searchParams.set('test_time', time);
    window.location.href = url.toString();
}

// Custom time form
document.getElementById('customTimeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    applyTime(formData.get('test_date'), formData.get('test_time'));
});

// Mock data functions
function generateMockData() {
    if (confirm('Apakah Anda yakin ingin membuat mock data? Data lama akan dihapus.')) {
        fetch('/guru/mock-data/generate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal membuat mock data');
        });
    }
}

function clearMockData() {
    if (confirm('Apakah Anda yakin ingin menghapus mock data?')) {
        fetch('/guru/mock-data/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghapus mock data');
        });
    }
}

// Auto refresh every 30 seconds in testing mode
<?php if($testingInfo['is_testing_mode']): ?>
setInterval(() => {
    window.location.reload();
}, 30000);
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Testing Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/testing-dashboard.blade.php ENDPATH**/ ?>