<?php $__env->startSection('content'); ?>

<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Bantuan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-md bg-primary bg-opacity-10 rounded-circle mx-auto mb-3">
                    <iconify-icon icon="solar:book-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                </div>
                <h5 class="card-title">Panduan Penggunaan</h5>
                <p class="text-muted">Pelajari cara menggunakan sistem absensi dengan panduan lengkap</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userGuideModal">
                    <i class="bx bx-book-open me-1"></i> Buka Panduan
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-md bg-success bg-opacity-10 rounded-circle mx-auto mb-3">
                    <iconify-icon icon="solar:question-circle-outline" class="fs-32 text-success avatar-title"></iconify-icon>
                </div>
                <h5 class="card-title">FAQ</h5>
                <p class="text-muted">Temukan jawaban untuk pertanyaan yang sering diajukan</p>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#faqModal">
                    <i class="bx bx-help-circle me-1"></i> Lihat FAQ
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-md bg-warning bg-opacity-10 rounded-circle mx-auto mb-3">
                    <iconify-icon icon="solar:phone-outline" class="fs-32 text-warning avatar-title"></iconify-icon>
                </div>
                <h5 class="card-title">Kontak Support</h5>
                <p class="text-muted">Hubungi tim support untuk bantuan teknis</p>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#contactModal">
                    <i class="bx bx-phone me-1"></i> Hubungi
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="userGuideModal" tabindex="-1" aria-labelledby="userGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userGuideModalLabel">Panduan Penggunaan Sistem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="list-group" id="guideTabs" role="tablist">
                            <a class="list-group-item list-group-item-action active" data-bs-toggle="list" href="#dashboard" role="tab">
                                <i class="bx bx-home me-2"></i> Dashboard
                            </a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#users" role="tab">
                                <i class="bx bx-user me-2"></i> Manajemen User
                            </a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#schedule" role="tab">
                                <i class="bx bx-calendar me-2"></i> Jadwal Pelajaran
                            </a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#reports" role="tab">
                                <i class="bx bx-file-blank me-2"></i> Laporan
                            </a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#announcements" role="tab">
                                <i class="bx bx-news me-2"></i> Pengumuman
                            </a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#settings" role="tab">
                                <i class="bx bx-cog me-2"></i> Pengaturan
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content" id="guideTabContent">
                            <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                                <h6>Dashboard Admin</h6>
                                <p>Dashboard admin memberikan gambaran menyeluruh tentang sistem absensi sekolah. Anda dapat melihat:</p>
                                <ul>
                                    <li>Statistik kehadiran harian</li>
                                    <li>Jumlah guru dan siswa</li>
                                    <li>Grafik tren kehadiran</li>
                                    <li>Aktivitas terbaru</li>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="users" role="tabpanel">
                                <h6>Manajemen User</h6>
                                <p>Kelola akun pengguna sistem:</p>
                                <ul>
                                    <li>Tambah, edit, dan hapus akun guru</li>
                                    <li>Tambah, edit, dan hapus akun siswa</li>
                                    <li>Atur peran dan izin pengguna</li>
                                    <li>Reset password pengguna</li>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="schedule" role="tabpanel">
                                <h6>Jadwal Pelajaran</h6>
                                <p>Kelola jadwal pelajaran sekolah:</p>
                                <ul>
                                    <li>Buat jadwal pelajaran baru</li>
                                    <li>Edit jadwal yang sudah ada</li>
                                    <li>Import jadwal dari Excel</li>
                                    <li>Atur konflik jadwal</li>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="reports" role="tabpanel">
                                <h6>Laporan</h6>
                                <p>Buat dan lihat laporan kehadiran:</p>
                                <ul>
                                    <li>Filter laporan berdasarkan tanggal</li>
                                    <li>Export laporan ke Excel</li>
                                    <li>Lihat statistik kehadiran</li>
                                    <li>Analisis tren kehadiran</li>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="announcements" role="tabpanel">
                                <h6>Pengumuman</h6>
                                <p>Buat dan kelola pengumuman:</p>
                                <ul>
                                    <li>Buat pengumuman untuk semua pengguna</li>
                                    <li>Target pengumuman ke guru atau siswa</li>
                                    <li>Atur prioritas pengumuman</li>
                                    <li>Kelola status pengumuman</li>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="settings" role="tabpanel">
                                <h6>Pengaturan</h6>
                                <p>Konfigurasi sistem:</p>
                                <ul>
                                    <li>Pengaturan sekolah</li>
                                    <li>Konfigurasi absensi</li>
                                    <li>Pengaturan notifikasi</li>
                                    <li>Keamanan sistem</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="faqModal" tabindex="-1" aria-labelledby="faqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="faqModalLabel">Frequently Asked Questions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                Bagaimana cara menambah guru baru?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Untuk menambah guru baru, masuk ke menu "Manajemen User" > "Tambah Guru". Isi form dengan data lengkap guru dan klik "Simpan".
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                Bagaimana cara import jadwal dari Excel?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Masuk ke menu "Jadwal Pelajaran" > "Import Excel". Download template terlebih dahulu, isi dengan data jadwal, lalu upload file Excel tersebut.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Bagaimana cara membuat laporan kehadiran?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Masuk ke menu "Laporan", atur filter tanggal dan kelas yang diinginkan, lalu klik "Filter". Anda dapat export hasil laporan ke Excel.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                Bagaimana cara mengatur notifikasi email?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Masuk ke menu "Pengaturan" > "Pengaturan Notifikasi". Aktifkan notifikasi email dan konfigurasi SMTP server jika diperlukan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Kontak Support</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle">
                                    <iconify-icon icon="solar:phone-outline" class="fs-20 text-primary"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Telepon</h6>
                                <p class="text-muted mb-0">(0401) 123456</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm bg-success bg-opacity-10 rounded-circle">
                                    <iconify-icon icon="solar:mailbox-outline" class="fs-20 text-success"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Email</h6>
                                <p class="text-muted mb-0">support@smkn4kendari.sch.id</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm bg-info bg-opacity-10 rounded-circle">
                                    <iconify-icon icon="solar:clock-outline" class="fs-20 text-info"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Jam Kerja</h6>
                                <p class="text-muted mb-0">Senin - Jumat: 08:00 - 16:00 WITA</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h6>Kirim Pesan</h6>
                <form id="contactForm">
                    <div class="mb-3">
                        <label for="contact_subject" class="form-label">Subjek</label>
                        <input type="text" class="form-control" id="contact_subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_message" class="form-label">Pesan</label>
                        <textarea class="form-control" id="contact_message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Contact form submission
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const subject = document.getElementById('contact_subject').value;
        const message = document.getElementById('contact_message').value;
        
        if (!subject || !message) {
            showAlert('error', 'Mohon lengkapi semua field');
            return;
        }
        
        showLoading('Mengirim pesan...');
        
        fetch('/admin/bantuan/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                subject: subject,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showAlert('success', 'Pesan berhasil dikirim');
                document.getElementById('contactForm').reset();
                bootstrap.Modal.getInstance(document.getElementById('contactModal')).hide();
            } else {
                showAlert('error', 'Gagal mengirim pesan');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat mengirim pesan');
        });
    });

    // Utility functions
    function showLoading(message) {
        const loadingHtml = `
            <div class="d-flex align-items-center">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                ${message}
            </div>
        `;
        showAlert('info', loadingHtml);
    }

    function hideLoading() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.innerHTML.includes('spinner-border')) {
                alert.remove();
            }
        });
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 
                          type === 'info' ? 'alert-info' : 'alert-warning';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const content = document.querySelector('.page-content .container-fluid');
        content.insertAdjacentHTML('afterbegin', alertHtml);
        
        if (type !== 'info') {
            setTimeout(() => {
                const alert = content.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'Bantuan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/bantuan.blade.php ENDPATH**/ ?>