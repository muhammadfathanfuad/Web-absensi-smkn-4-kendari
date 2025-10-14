<?php $__env->startSection('content'); ?>

    
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Dashboard', 'subtitle' => 'Guru'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    
    <?php if(session('time_override_active')): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-warning d-flex justify-content-between align-items-center">
                <div>
                    <i class="bx bx-time me-2"></i>
                    <strong>Time Override Active:</strong> 
                    Waktu saat ini: <?php echo e(session('time_override_datetime')); ?> | 
                    Waktu real: <?php echo e(now()->toDateTimeString()); ?>

                </div>
                <div>
                    <a href="<?php echo e(route('time-override.index')); ?>" class="btn btn-warning btn-sm me-2">
                        <i class="bx bx-cog me-1"></i> Time Override
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <div>
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>Real Time Mode:</strong> Ingin test dengan waktu yang berbeda?
                </div>
                <div>
                    <a href="<?php echo e(route('time-override.index')); ?>" class="btn btn-warning btn-sm">
                        <i class="bx bx-time me-1"></i> Time Override
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        
        <div class="col-xl-7">
            <div class="card card-height-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">Jadwal Mengajar Hari Ini</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Jam</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php $__empty_1 = true; $__currentLoopData = $jadwalMengajar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($jadwal['jam']); ?></td>
                                        <td><?php echo e($jadwal['mapel']); ?></td>
                                        <td><?php echo e($jadwal['kelas']); ?></td>
                                        <td>
                                            <?php if($jadwal['status'] == 'Selesai'): ?>
                                                <span class="badge bg-soft-success text-success"><?php echo e($jadwal['status']); ?></span>
                                            <?php elseif($jadwal['status'] == 'Berlangsung'): ?>
                                                <span class="badge bg-soft-warning text-warning"><?php echo e($jadwal['status']); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-soft-secondary text-secondary"><?php echo e($jadwal['status']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada jadwal mengajar hari ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-5">
            <div class="card card-height-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">Jam Mengajar Hari Ini</h4>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div dir="ltr">
                        
                        <div id="jamMengajarChart" class="apex-charts" style="height: 250px;"></div>
                    </div>
                </div>
                 <div class="card-footer bg-transparent border-top-0 text-center">
                    
                    <h5 class="text-muted"><?php echo e($jamMengajarData['label']); ?></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Riwayat Mengajar Bulan Ini</h4>
                </div>
                <div class="card-body">
                    
                    <div id="riwayatMengajarChart" class="apex-charts" dir="ltr" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">List Siswa Izin Hari Ini</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                 
                                <?php $__empty_1 = true; $__currentLoopData = $siswaIzin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $izin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($izin['nama']); ?></td>
                                        <td><?php echo e($izin['kelas']); ?></td>
                                        <td>
                                            <?php if($izin['keterangan'] == 'Sakit'): ?>
                                                <span class="badge bg-soft-warning text-warning"><?php echo e($izin['keterangan']); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-soft-info text-info"><?php echo e($izin['keterangan']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                     <tr>
                                        <td colspan="3" class="text-center">Tidak ada siswa yang izin hari ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-6">
            
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Statistik Kehadiran Siswa</h4>
                </div>
                <div class="card-body">
                    
                    <div id="statistikKehadiranChart" class="apex-charts" dir="ltr" style="height: 250px;"></div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Pengumuman</h4>
                </div>
                <div class="card-body" style="max-height: 220px; overflow-y: auto;">
                    
                    <?php $__empty_1 = true; $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                 <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle text-center">
                                    <iconify-icon icon="<?php echo e($item['icon']); ?>" class="fs-24 text-primary avatar-title"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0"><?php echo e($item['judul']); ?></h6>
                                <small class="text-muted"><?php echo e($item['tanggal']); ?></small>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-center">Tidak ada pengumuman.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    
    <script>
        // Data dari controller yang akan digunakan oleh JavaScript
        var jamMengajarData = <?php echo json_encode($jamMengajarData, 15, 512) ?>;
        var riwayatMengajarData = <?php echo json_encode($riwayatMengajarData, 15, 512) ?>;
        var statistikKehadiranData = <?php echo json_encode($statistikKehadiranData, 15, 512) ?>;
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/dashboard.js']); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/dashboard.blade.php ENDPATH**/ ?>