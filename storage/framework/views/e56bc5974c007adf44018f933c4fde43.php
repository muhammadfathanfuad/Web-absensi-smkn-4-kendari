

<?php $__env->startSection('title', 'Bantuan'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Bantuan</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('murid.dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Bantuan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-help-circle me-2"></i>
                        Pertanyaan yang Sering Diajukan (FAQ)
                    </h4>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                    <i class="bx bx-qr-scan me-2"></i>
                                    Bagaimana cara melakukan absensi menggunakan QR Code?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <ol>
                                        <li>Buka menu "QR Code" di sidebar</li>
                                        <li>Pilih kamera yang akan digunakan</li>
                                        <li>Arahkan kamera ke QR Code yang ditampilkan guru</li>
                                        <li>Tunggu hingga QR Code terdeteksi otomatis</li>
                                        <li>Data absensi akan dikirim secara otomatis</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    <i class="bx bx-calendar me-2"></i>
                                    Bagaimana cara melihat jadwal pelajaran?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Untuk melihat jadwal pelajaran:</p>
                                    <ol>
                                        <li>Klik menu "Jadwal Pelajaran" di sidebar</li>
                                        <li>Tabel pertama menampilkan jadwal hari ini</li>
                                        <li>Tabel kedua menampilkan semua jadwal dalam seminggu</li>
                                        <li>Gunakan filter untuk mencari jadwal berdasarkan hari atau mata pelajaran</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    <i class="bx bx-history me-2"></i>
                                    Bagaimana cara melihat riwayat absensi?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Untuk melihat riwayat absensi:</p>
                                    <ol>
                                        <li>Klik menu "Riwayat Absensi" di sidebar</li>
                                        <li>Pilih rentang tanggal yang diinginkan</li>
                                        <li>Klik tombol "Filter" untuk menampilkan data</li>
                                        <li>Data akan menampilkan status kehadiran, jam masuk, dan keterangan</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                    <i class="bx bx-file-plus me-2"></i>
                                    Bagaimana cara mengajukan permohonan izin?
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Untuk mengajukan permohonan izin:</p>
                                    <ol>
                                        <li>Klik menu "Permohonan Izin" di sidebar</li>
                                        <li>Isi form dengan lengkap (jenis izin, tanggal, alasan)</li>
                                        <li>Lampirkan dokumen pendukung jika diperlukan</li>
                                        <li>Klik "Ajukan Permohonan"</li>
                                        <li>Status dapat dicek di riwayat permohonan</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                    <i class="bx bx-megaphone me-2"></i>
                                    Bagaimana cara melihat pengumuman?
                                </button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Untuk melihat pengumuman:</p>
                                    <ol>
                                        <li>Klik menu "Pengumuman" di sidebar</li>
                                        <li>Gunakan filter untuk mencari pengumuman berdasarkan kategori atau tanggal</li>
                                        <li>Klik "Baca Selengkapnya" untuk melihat detail pengumuman</li>
                                        <li>Pengumuman penting akan ditandai dengan warna merah</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">
                                    <i class="bx bx-cog me-2"></i>
                                    Bagaimana cara mengubah password?
                                </button>
                            </h2>
                            <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Untuk mengubah password:</p>
                                    <ol>
                                        <li>Klik menu "Pengaturan" di sidebar</li>
                                        <li>Pilih tab "Keamanan Akun"</li>
                                        <li>Masukkan password lama</li>
                                        <li>Masukkan password baru dan konfirmasi</li>
                                        <li>Klik "Ubah Password"</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-support me-2"></i>
                        Butuh Bantuan Lebih Lanjut?
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                        <i class="bx bx-phone"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">Telepon</h6>
                                    <small class="text-muted">(0401) 123456</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <span class="avatar-title rounded-circle bg-success text-white">
                                        <i class="bx bx-envelope"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">Email</h6>
                                    <small class="text-muted">support@smkn4kendari.sch.id</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <span class="avatar-title rounded-circle bg-info text-white">
                                        <i class="bx bx-time"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">Jam Operasional</h6>
                                    <small class="text-muted">Senin - Jumat, 08:00 - 16:00 WITA</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <span class="avatar-title rounded-circle bg-warning text-white">
                                        <i class="bx bx-map"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">Alamat</h6>
                                    <small class="text-muted">Jl. Pendidikan No. 123, Kendari</small>
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
                    <h5 class="card-title mb-0">
                        <i class="bx bx-search me-2"></i>
                        Cari Bantuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="searchFAQ" placeholder="Cari pertanyaan...">
                    </div>
                    <button class="btn btn-primary w-100" onclick="searchFAQ()">
                        <i class="bx bx-search me-1"></i>
                        Cari
                    </button>
                </div>
            </div>

            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-category me-2"></i>
                        Kategori Bantuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bx bx-qr-scan me-2"></i>
                            Absensi QR Code
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bx bx-calendar me-2"></i>
                            Jadwal Pelajaran
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bx bx-history me-2"></i>
                            Riwayat Absensi
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bx bx-file-plus me-2"></i>
                            Permohonan Izin
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bx bx-megaphone me-2"></i>
                            Pengumuman
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bx bx-cog me-2"></i>
                            Pengaturan Akun
                        </a>
                    </div>
                </div>
            </div>

            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-bulb me-2"></i>
                        Tips & Trik
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">💡 Tips Absensi</h6>
                        <p class="mb-0 small">Pastikan kamera dalam kondisi baik dan QR Code terlihat jelas untuk hasil scan yang optimal.</p>
                    </div>
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">⚠️ Perhatian</h6>
                        <p class="mb-0 small">Ajukan permohonan izin minimal 1 hari sebelumnya untuk memudahkan proses persetujuan.</p>
                    </div>
                    <div class="alert alert-success">
                        <h6 class="alert-heading">✅ Saran</h6>
                        <p class="mb-0 small">Periksa pengumuman secara berkala untuk mendapatkan informasi terbaru dari sekolah.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function searchFAQ() {
    const searchTerm = document.getElementById('searchFAQ').value.toLowerCase();
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const button = item.querySelector('.accordion-button');
        const content = item.querySelector('.accordion-body');
        const text = (button.textContent + ' ' + content.textContent).toLowerCase();
        
        if (text.includes(searchTerm)) {
            item.style.display = '';
            // Highlight search term
            if (searchTerm) {
                const regex = new RegExp(`(${searchTerm})`, 'gi');
                content.innerHTML = content.textContent.replace(regex, '<mark>$1</mark>');
            }
        } else {
            item.style.display = 'none';
        }
    });
}

document.getElementById('searchFAQ').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchFAQ();
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.vertical-murid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/murid/bantuan.blade.php ENDPATH**/ ?>