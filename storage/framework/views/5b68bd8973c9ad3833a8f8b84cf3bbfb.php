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
                                
                                <tr>
                                    <td>07:00 - 08:30</td>
                                    <td>Matematika</td>
                                    <td>XII RPL 1</td>
                                    <td><span class="badge bg-soft-success text-success">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>08:30 - 10:00</td>
                                    <td>Bahasa Indonesia</td>
                                    <td>XI TKJ 2</td>
                                    <td><span class="badge bg-soft-warning text-warning">Berlangsung</span></td>
                                </tr>
                                <tr>
                                    <td>10:30 - 12:00</td>
                                    <td>Dasar Desain Grafis</td>
                                    <td>X MM 1</td>
                                    <td><span class="badge bg-soft-secondary text-secondary">Akan Datang</span></td>
                                </tr>
                                <tr>
                                    <td>13:00 - 14:30</td>
                                    <td>Pemrograman Web</td>
                                    <td>XII RPL 1</td>
                                    <td><span class="badge bg-soft-secondary text-secondary">Akan Datang</span></td>
                                </tr>
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
                    <h5 class="text-muted">Total 4 Jam dari 8 Jam Hari Ini</h5>
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
                                 
                                <tr>
                                    <td>Ahmad Budi</td>
                                    <td>XII RPL 1</td>
                                    <td><span class="badge bg-soft-info text-info">Izin</span></td>
                                </tr>
                                <tr>
                                    <td>Siti Aminah</td>
                                    <td>XI TKJ 2</td>
                                    <td><span class="badge bg-soft-warning text-warning">Sakit</span></td>
                                </tr>
                                <tr>
                                    <td>Joko Susilo</td>
                                    <td>X MM 1</td>
                                    <td><span class="badge bg-soft-info text-info">Izin</span></td>
                                </tr>
                                 <tr>
                                    <td>Putri Lestari</td>
                                    <td>XII RPL 1</td>
                                    <td><span class="badge bg-soft-warning text-warning">Sakit</span></td>
                                </tr>
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
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                             <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle text-center">
                                <iconify-icon icon="solar:megaphone-bold" class="fs-24 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Rapat Dewan Guru</h6>
                            <small class="text-muted">10 Oktober 2025 - 08:00</small>
                        </div>
                    </div>
                     <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle text-center">
                                <iconify-icon icon="solar:calendar-bold" class="fs-24 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Kegiatan Class Meeting</h6>
                            <small class="text-muted">15 Desember 2025</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/pages/dashboard.js']); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Coding\Web-absensi-smkn-4-kendari\resources\views/guru/dashboard.blade.php ENDPATH**/ ?>