<?php $__env->startSection('content'); ?>

    
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Dashboard', 'subtitle' => 'Guru'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
            <!-- Intentionally left blank after removing Statistik Kehadiran and Pengumuman -->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    
    <script>
        // Data dari controller yang akan digunakan oleh JavaScript
        var jamMengajarData = <?php echo json_encode($jamMengajarData, 15, 512) ?>;
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/dashboard.js']); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\PresenZ\Web-absensi-smkn-4-kendari\resources\views/guru/dashboard.blade.php ENDPATH**/ ?>