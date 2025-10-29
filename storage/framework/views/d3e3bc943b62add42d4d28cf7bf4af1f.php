<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Bantuan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row">
        <div class="col-lg-8">
            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-info bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:question-circle-outline" class="fs-32 text-info avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="card-title mb-1">
                                Pertanyaan yang Sering Diajukan
                            </h4>
                            <p class="text-muted mb-0">Temukan jawaban untuk pertanyaan umum</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    <i class="bx bx-qr fs-16 me-2"></i>
                                    Bagaimana cara menggunakan fitur Scan QR untuk absensi?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <ol>
                                        <li>Pilih jadwal mata pelajaran yang akan dimulai</li>
                                        <li>Klik tombol "Mulai Sesi Absensi"</li>
                                        <li>QR Code akan muncul di layar</li>
                                        <li>Minta siswa untuk scan QR Code dengan aplikasi mereka</li>
                                        <li>Pantau hasil pindaian di tabel sebelah kanan</li>
                                        <li>Klik "Hentikan Sesi Absensi" setelah selesai</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                    <i class="bx bx-calendar-check fs-16 me-2"></i>
                                    Bagaimana cara melihat jadwal mengajar?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Anda dapat melihat jadwal mengajar dengan cara:</p>
                                    <ul>
                                        <li>Klik menu "Jadwal Mengajar" di sidebar</li>
                                        <li>Tabel pertama menampilkan jadwal hari ini</li>
                                        <li>Tabel kedua menampilkan jadwal semester ini</li>
                                        <li>Warna kuning pada baris menunjukkan jadwal yang akan segera dimulai</li>
                                        <li>Warna hijau menunjukkan jadwal yang sedang berlangsung</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                    <i class="bx bx-history fs-16 me-2"></i>
                                    Bagaimana cara melihat riwayat absensi siswa?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Untuk melihat riwayat absensi:</p>
                                    <ul>
                                        <li>Klik menu "Status Absensi" di sidebar</li>
                                        <li>Pilih tanggal yang ingin dilihat</li>
                                        <li>Pilih kelas dan mata pelajaran</li>
                                        <li>Data absensi akan ditampilkan dalam tabel</li>
                                        <li>Anda dapat mengekspor data ke Excel jika diperlukan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                    <i class="bx bx-cog fs-16 me-2"></i>
                                    Bagaimana cara mengubah password?
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Untuk mengubah password:</p>
                                    <ol>
                                        <li>Klik menu "Pengaturan" di sidebar</li>
                                        <li>Scroll ke bagian "Keamanan Akun"</li>
                                        <li>Masukkan password lama</li>
                                        <li>Masukkan password baru</li>
                                        <li>Konfirmasi password baru</li>
                                        <li>Klik "Ubah Password"</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                    <i class="bx bx-news fs-16 me-2"></i>
                                    Bagaimana cara melihat pengumuman terbaru?
                                </button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Untuk melihat pengumuman:</p>
                                    <ul>
                                        <li>Klik menu "Pengumuman" di sidebar</li>
                                        <li>Pengumuman terbaru akan ditampilkan di halaman utama</li>
                                        <li>Pengumuman penting ditandai dengan warna kuning</li>
                                        <li>Informasi umum ditandai dengan warna biru</li>
                                        <li>Update sistem ditandai dengan warna hijau</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-video-recording fs-32 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="card-title mb-1">
                                Panduan Video
                            </h4>
                            <p class="text-muted mb-0">Tonton tutorial penggunaan sistem</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <div class="avatar-lg bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                                        <iconify-icon icon="solar:play-circle-outline" class="fs-48 text-primary"></iconify-icon>
                                    </div>
                                    <h6 class="card-title">Cara Menggunakan Scan QR</h6>
                                    <p class="text-muted small">Tutorial lengkap penggunaan fitur absensi QR Code</p>
                                    <button class="btn btn-primary btn-sm">
                                        <iconify-icon icon="solar:play-outline" class="fs-14 me-1"></iconify-icon>
                                        Tonton Video
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <div class="avatar-lg bg-success bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                                        <iconify-icon icon="solar:play-circle-outline" class="fs-48 text-success"></iconify-icon>
                                    </div>
                                    <h6 class="card-title">Melihat Jadwal Mengajar</h6>
                                    <p class="text-muted small">Panduan melihat dan memahami jadwal mengajar</p>
                                    <button class="btn btn-success btn-sm">
                                        <iconify-icon icon="solar:play-outline" class="fs-14 me-1"></iconify-icon>
                                        Tonton Video
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-phone fs-20 me-2"></i>
                        Kontak Support
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-phone fs-16 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Telepon</h6>
                            <p class="text-muted mb-0">(0401) 123456</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-envelope fs-16 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Email</h6>
                            <p class="text-muted mb-0">support@smkn4kendari.sch.id</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bxl-whatsapp fs-16 text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">WhatsApp</h6>
                            <p class="text-muted mb-0">+62 812-3456-7890</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-time-five fs-16 text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Jam Kerja</h6>
                            <p class="text-muted mb-0">Senin - Jumat<br>08:00 - 16:00 WITA</p>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:document-outline" class="fs-20 me-2"></iconify-icon>
                        Dokumentasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary">
                            <iconify-icon icon="solar:download-outline" class="fs-16 me-2"></iconify-icon>
                            Panduan Pengguna
                        </button>
                        <button class="btn btn-outline-success">
                            <iconify-icon icon="solar:download-outline" class="fs-16 me-2"></iconify-icon>
                            Manual Teknis
                        </button>
                        <button class="btn btn-outline-info">
                            <iconify-icon icon="solar:download-outline" class="fs-16 me-2"></iconify-icon>
                            FAQ Lengkap
                        </button>
                    </div>
                </div>
            </div>

            
            <?php
                // Check database connection
                $dbStatus = 'Online';
                try {
                    \Illuminate\Support\Facades\DB::connection()->getPdo();
                } catch (\Exception $e) {
                    $dbStatus = 'Offline';
                }

                // Check storage
                $storageTotal = disk_total_space('/');
                $storageFree = disk_free_space('/');
                $storageUsed = $storageTotal - $storageFree;
                $storagePercent = $storageTotal > 0 ? round(($storageUsed / $storageTotal) * 100, 1) : 0;
                $storageFormat = round($storageUsed / (1024*1024*1024), 1) . ' GB / ' . round($storageTotal / (1024*1024*1024), 1) . ' GB';

                // Check PHP version
                $phpVersion = phpversion();

                // Check Laravel version
                $laravelVersion = app()->version();

                // Check environment
                $environment = app()->environment();
            ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-server fs-20 me-2"></i>
                        Status Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Database</span>
                        <span class="badge bg-<?php echo e($dbStatus == 'Online' ? 'success' : 'danger'); ?>-subtle text-<?php echo e($dbStatus == 'Online' ? 'success' : 'danger'); ?> py-1 px-2">
                            <i class="bx bxs-circle text-<?php echo e($dbStatus == 'Online' ? 'success' : 'danger'); ?> me-1"></i><?php echo e($dbStatus); ?>

                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">PHP Version</span>
                        <span class="fw-semibold"><?php echo e($phpVersion); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Laravel Version</span>
                        <span class="fw-semibold"><?php echo e($laravelVersion); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Environment</span>
                        <span class="badge bg-<?php echo e($environment === 'production' ? 'danger' : 'warning'); ?>-subtle text-<?php echo e($environment === 'production' ? 'danger' : 'warning'); ?> py-1 px-2">
                            <?php echo e(strtoupper($environment)); ?>

                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Storage</span>
                        <span class="fw-semibold"><?php echo e($storageFormat); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Storage Usage</span>
                        <span class="badge bg-<?php echo e($storagePercent >= 90 ? 'danger' : ($storagePercent >= 75 ? 'warning' : 'success')); ?>-subtle text-<?php echo e($storagePercent >= 90 ? 'danger' : ($storagePercent >= 75 ? 'warning' : 'success')); ?> py-1 px-2">
                            <?php echo e($storagePercent); ?>%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Bantuan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/bantuan-guru.blade.php ENDPATH**/ ?>