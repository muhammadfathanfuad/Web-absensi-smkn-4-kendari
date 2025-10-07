<?php $__env->startSection('content'); ?>
    <!-- Mulai Konten Halaman -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Guru</a></li>
                        <li class="breadcrumb-item active">Jadwal Mengajar</li>
                    </ol>
                </div>
                <h4 class="page-title">Jadwal Mengajar</h4>
            </div>
        </div>
    </div>
    <!-- Akhir Judul Halaman -->

    <div class="row">
        <!-- Kolom Kiri: Pemberitahuan dan Kalender -->
        <div class="col-xl-4 col-lg-5">

            <!-- Card Pemberitahuan -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Pemberitahuan</h5>
                    
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                           <i class="ri-calendar-event-fill fs-20 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-14">Rapat Awal Semester</h6>
                            <p class="mb-0 text-muted fs-12">Pemberitahuan rapat akan dilaksanakan pada...</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                           <i class="ri-task-fill fs-20 text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-14">Pengumpulan Nilai Akhir</h6>
                            <p class="mb-0 text-muted fs-12">Harap segera menyelesaikan pengisian nilai...</p>
                        </div>
                    </div>
                     <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                           <i class="ri-information-fill fs-20 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-14">Update Sistem Absensi</h6>
                            <p class="mb-0 text-muted fs-12">Sistem akan di-maintenance pada hari sabtu...</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Card Kalender -->
            <div class="card">
                <div class="card-body">
                     <h5 class="card-title mb-3">Kalender</h5>
                    <!-- Kalender akan di-generate oleh JavaScript di sini -->
                    <div id="calendar-widget"></div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Jadwal Hari Ini dan Semester -->
        <div class="col-xl-8 col-lg-7">
            <!-- Card Jadwal Hari Ini -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jadwal Hari Ini: <?php echo e(\Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY')); ?></h5>
                    <p class="card-subtitle mb-2 text-muted">Berikut adalah jadwal mengajar Anda untuk hari ini.</p>

                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>JAM KE-</th>
                                    <th>WAKTU</th>
                                    <th>KELAS</th>
                                    <th>MATA PELAJARAN</th>
                                    <th>STATUS</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>07:00 - 07:45</td>
                                    <td>XII RPL</td>
                                    <td>Basis Data</td>
                                    <td><span class="badge bg-soft-warning text-warning">Belum Absen</span></td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm">Absensi</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>07:45 - 08:30</td>
                                    <td>XII RPL</td>
                                    <td>Basis Data</td>
                                    <td><span class="badge bg-soft-success text-success">Berlangsung</span></td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm">Lihat Detail</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card Jadwal Semester Ini -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jadwal Mengajar Semester Ini</h5>
                <p class="card-subtitle mb-2 text-muted">Jadwal lengkap untuk semester Ganjil 2025/2026.</p>

                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>HARI</th>
                                    <th>JAM KE-</th>
                                    <th>WAKTU</th>
                                    <th>KELAS</th>
                                    <th>MATA PELAJARAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Senin</td>
                                    <td>3-4</td>
                                    <td>08:30 - 10:00</td>
                                    <td>XI TKJ 2</td>
                                    <td>Jaringan Dasar</td>
                                </tr>
                                <tr>
                                    <td>Selasa</td>
                                    <td>1-2</td>
                                    <td>07:00 - 08:30</td>
                                    <td>X RPL 1</td>
                                    <td>Dasar Pemrograman</td>
                                </tr>
                                <tr>
                                    <td>Jumat</td>
                                    <td>1-2</td>
                                    <td>07:00 - 08:30</td>
                                    <td>XII RPL</td>
                                    <td>Basis Data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-guru', ['title' => 'Jadwal Mengajar'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/jadwal-mengajar.blade.php ENDPATH**/ ?>