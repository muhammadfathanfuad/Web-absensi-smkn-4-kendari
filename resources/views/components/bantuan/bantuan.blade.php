@props([
    'mode' => 'guru', // 'guru', 'murid', or 'admin'
    'showVideoGuide' => true,
    'showDocumentation' => true,
    'showSystemStatus' => true,
    'showSearchFAQ' => false,
    'showCategoryHelp' => false,
    'showTipsTricks' => false
])

<div class="row">
    <div class="col-lg-8">
        {{-- FAQ --}}
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
                    @if($mode === 'guru')
                        {{-- FAQ untuk Guru --}}
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
                    @elseif($mode === 'murid')
                        {{-- FAQ untuk Murid --}}
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
                    @else
                        {{-- FAQ untuk Admin --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                    <i class="bx bx-home fs-16 me-2"></i>
                                    Bagaimana cara menggunakan Dashboard Admin?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Dashboard Admin menampilkan ringkasan informasi sistem:</p>
                                    <ul>
                                        <li><strong>Statistik Utama:</strong> Total Guru, Siswa, Mata Pelajaran, dan Kelas</li>
                                        <li><strong>Statistik Hari Ini:</strong> Kehadiran, Permohonan Izin, dan Sesi Aktif</li>
                                        <li><strong>Grafik Performa Guru:</strong> Data 7 hari terakhir dengan pagination</li>
                                        <li><strong>Tren Kehadiran:</strong> Grafik kehadiran siswa 30 hari terakhir</li>
                                        <li><strong>Statistik Per Kelas:</strong> Data kehadiran per kelas dengan pagination</li>
                                        <li><strong>Aktivitas Terbaru:</strong> Log aktivitas sistem dengan pagination</li>
                                        <li><strong>Beban Kerja Guru:</strong> Distribusi beban mengajar per guru</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    <i class="bx bx-user fs-16 me-2"></i>
                                    Bagaimana cara menambah dan mengelola user di Manajemen User?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Manajemen User memiliki 3 tab:</p>
                                    <ol>
                                        <li><strong>Data User:</strong> Menampilkan semua user dengan filter role (Guru/Siswa) dan kelas. Fitur: Tambah User, Export PDF, Edit, Hapus, Bulk Delete</li>
                                        <li><strong>Data Guru:</strong> Menampilkan semua guru. Fitur: Tambah Guru (manual dengan form: Email, NIP, Kode Guru, Mata Pelajaran), Upload Data (Excel), Hapus, Bulk Delete</li>
                                        <li><strong>Data Murid:</strong> Menampilkan semua murid. Fitur: Tambah Murid (manual), Upload Data (Excel), Hapus, Bulk Delete</li>
                                    </ol>
                                    <p><strong>Cara Tambah Guru:</strong> Klik tab "Data Guru" > "Tambah Guru" > Isi form (Email, NIP, Kode Guru, Mata Pelajaran) > Klik "Tambah Guru"</p>
                                    <p><strong>Cara Upload Data:</strong> Klik "Upload Data" > Pilih file Excel > Upload. Pastikan format file sesuai template.</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    <i class="bx bx-calendar fs-16 me-2"></i>
                                    Bagaimana cara mengelola jadwal pelajaran?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Jadwal Pelajaran memiliki 5 tab:</p>
                                    <ol>
                                        <li><strong>Kelas X:</strong> Jadwal untuk kelas X dengan filter Semester, Kelas, dan Hari. Fitur: Import Jadwal (Excel), Export PDF, Edit, Hapus (single/bulk/all)</li>
                                        <li><strong>Kelas XI:</strong> Jadwal untuk kelas XI dengan filter Semester. Fitur: Import, Export PDF, Filter lanjutan</li>
                                        <li><strong>Kelas XII:</strong> Jadwal untuk kelas XII</li>
                                        <li><strong>Info Akademik:</strong> Kelola Mata Pelajaran, Kelas, dan Semester. Fitur: Tambah, Edit, Hapus untuk masing-masing</li>
                                        <li><strong>Tambah Mata Pelajaran Manual:</strong> Form untuk menambah mata pelajaran baru secara manual</li>
                                    </ol>
                                    <p><strong>Cara Import Jadwal:</strong> Pilih tab kelas yang diinginkan > "Import Jadwal" > Pilih Semester > Upload file Excel > Klik "Import Jadwal"</p>
                                    <p><strong>Cara Edit Jadwal:</strong> Klik ikon edit pada baris jadwal > Ubah data > Simpan</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                    <i class="bx bx-file-blank fs-16 me-2"></i>
                                    Bagaimana cara membuat dan melihat laporan kehadiran?
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Laporan memiliki 3 jenis:</p>
                                    <ol>
                                        <li><strong>Per Guru:</strong> Laporan kehadiran berdasarkan guru. Menampilkan statistik per guru</li>
                                        <li><strong>Per Siswa:</strong> Laporan kehadiran berdasarkan siswa dengan filter kelas. Menampilkan statistik per siswa</li>
                                        <li><strong>Per Kelas:</strong> Laporan kehadiran berdasarkan kelas. Menampilkan statistik per kelas</li>
                                    </ol>
                                    <p><strong>Cara Membuat Laporan:</strong></p>
                                    <ol>
                                        <li>Pilih jenis laporan (Per Guru/Siswa/Kelas)</li>
                                        <li>Atur filter tanggal (Dari Tanggal - Sampai Tanggal) atau gunakan tombol cepat (Hari Ini/Minggu Ini/Bulan Ini)</li>
                                        <li>Untuk laporan Per Siswa, pilih kelas jika diperlukan</li>
                                        <li>Klik "Terapkan Filter"</li>
                                        <li>Laporan akan menampilkan: Total Records, Present Count, Late Count, Absent Count, dan Persentase</li>
                                        <li>Klik "Export" untuk mengunduh laporan dalam format PDF</li>
                                    </ol>
                                    <p><strong>Pertanyaan yang bisa dijawab:</strong> Berapa tingkat kehadiran guru/siswa/kelas? Siapa yang sering absen? Berapa persentase kehadiran dalam periode tertentu?</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                    <i class="bx bx-news fs-16 me-2"></i>
                                    Bagaimana cara membuat dan mengelola pengumuman?
                                </button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p><strong>Cara Membuat Pengumuman:</strong></p>
                                    <ol>
                                        <li>Klik menu "Pengumuman" di sidebar</li>
                                        <li>Klik "Buat Pengumuman"</li>
                                        <li>Isi form: Judul, Isi, Target (Semua/Guru/Siswa), Prioritas (Normal/Tinggi/Mendesak), Kategori (Umum/Akademik/Kegiatan/Penting), Tanggal Berakhir (opsional)</li>
                                        <li>Centang "Aktifkan pengumuman" jika ingin langsung aktif</li>
                                        <li>Klik "Buat Pengumuman"</li>
                                    </ol>
                                    <p><strong>Fitur Lainnya:</strong></p>
                                    <ul>
                                        <li><strong>Edit:</strong> Klik ikon edit pada pengumuman > Ubah data > Simpan</li>
                                        <li><strong>Hapus:</strong> Klik ikon hapus pada pengumuman > Konfirmasi</li>
                                        <li><strong>Toggle Status:</strong> Aktifkan/nonaktifkan pengumuman tanpa menghapus</li>
                                        <li><strong>Lihat Detail:</strong> Klik ikon lihat untuk melihat detail lengkap pengumuman</li>
                                    </ul>
                                    <p><strong>Catatan:</strong> Notifikasi otomatis akan dikirim ke target pengguna saat pengumuman dibuat atau diaktifkan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">
                                    <i class="bx bx-transfer fs-16 me-2"></i>
                                    Bagaimana cara mengelola delegasi dan permohonan izin guru?
                                </button>
                            </h2>
                            <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Manajemen Pengganti memiliki 2 tab:</p>
                                    <ol>
                                        <li><strong>Delegasi Absensi:</strong> Kelola delegasi tugas absensi dari guru asli ke pengganti
                                            <ul>
                                                <li><strong>Tambah Delegasi:</strong> Pilih mata pelajaran, kelas, hari, guru pengganti (guru atau murid dari kelas yang sama), tipe (Permanent/Temporary), tanggal berlaku</li>
                                                <li><strong>Edit/Hapus:</strong> Klik ikon edit/hapus pada delegasi</li>
                                                <li><strong>Status:</strong> Active/Inactive</li>
                                            </ul>
                                        </li>
                                        <li><strong>Permohonan Izin Guru:</strong> Kelola permohonan izin dari guru
                                            <ul>
                                                <li><strong>Approve:</strong> Klik "Approve" > Pilih pengganti (guru atau murid dari kelas yang sama) > Tambahkan catatan admin (opsional) > Approve</li>
                                                <li><strong>Reject:</strong> Klik "Reject" > Tambahkan alasan penolakan > Reject</li>
                                                <li>Sistem akan otomatis membuat delegasi saat permohonan disetujui</li>
                                            </ul>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq7">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7">
                                    <i class="bx bx-cog fs-16 me-2"></i>
                                    Bagaimana cara mengatur pengaturan sistem?
                                </button>
                            </h2>
                            <div id="collapse7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Pengaturan Admin memiliki 2 bagian utama:</p>
                                    <ol>
                                        <li><strong>Pengaturan Profil:</strong>
                                            <ul>
                                                <li>Edit nama lengkap, email, nomor telepon</li>
                                                <li>Ubah password (perlu password lama)</li>
                                                <li>Upload foto profil</li>
                                                <li>Klik "Simpan Perubahan" setelah selesai</li>
                                            </ul>
                                        </li>
                                        <li><strong>Informasi Sistem:</strong>
                                            <ul>
                                                <li>Statistik database (tabel, ukuran, dll)</li>
                                                <li>Versi PHP dan Laravel</li>
                                                <li>Environment (Production/Development)</li>
                                                <li>Penggunaan storage dan persentase</li>
                                            </ul>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($showVideoGuide && $mode === 'guru')
            {{-- Panduan Video --}}
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
        @endif

        @if($mode === 'murid')
            {{-- Kontak Support untuk Murid --}}
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
        @endif
    </div>

    <div class="col-lg-4">
        @if($mode === 'guru')
            {{-- Kontak Support --}}
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

            @if($showDocumentation)
                {{-- Dokumentasi --}}
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
            @endif

            @if($showSystemStatus)
                {{-- Status Sistem --}}
                @php
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
                @endphp
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
                            <span class="badge bg-{{ $dbStatus == 'Online' ? 'success' : 'danger' }}-subtle text-{{ $dbStatus == 'Online' ? 'success' : 'danger' }} py-1 px-2">
                                <i class="bx bxs-circle text-{{ $dbStatus == 'Online' ? 'success' : 'danger' }} me-1"></i>{{ $dbStatus }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">PHP Version</span>
                            <span class="fw-semibold">{{ $phpVersion }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Laravel Version</span>
                            <span class="fw-semibold">{{ $laravelVersion }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Environment</span>
                            <span class="badge bg-{{ $environment === 'production' ? 'danger' : 'warning' }}-subtle text-{{ $environment === 'production' ? 'danger' : 'warning' }} py-1 px-2">
                                {{ strtoupper($environment) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Storage</span>
                            <span class="fw-semibold">{{ $storageFormat }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Storage Usage</span>
                            <span class="badge bg-{{ $storagePercent >= 90 ? 'danger' : ($storagePercent >= 75 ? 'warning' : 'success') }}-subtle text-{{ $storagePercent >= 90 ? 'danger' : ($storagePercent >= 75 ? 'warning' : 'success') }} py-1 px-2">
                                {{ $storagePercent }}%
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        @elseif($mode === 'murid')
            @if($showSearchFAQ)
                {{-- Pencarian FAQ --}}
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
            @endif

            @if($showCategoryHelp)
                {{-- Kategori Bantuan --}}
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
            @endif

            @if($showTipsTricks)
                {{-- Tips & Trik --}}
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
            @endif
        @endif
    </div>
</div>

