<div align="center">
<img src="public/images/logoKKN.png" alt="Logo KKN" width="120" />
<h1>Website Absensi Sekolah PresenZ</h1>
<p>
Sistem Absensi Siswa modern, real-time, dan berbasis QR Code.
</p>
<p> 
<a href="https://php.net"> <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php" alt="PHP Version"> </a> 
<a href="https://laravel.com"> <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel" alt="Laravel Version"> </a> 
<a href="https://opensource.org/licenses/MIT"> <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License"> </a> 
</p>
</div>

## 📚 Tentang Proyek

**Website Absensi PresenZ** adalah sebuah aplikasi web yang dibangun menggunakan Laravel 11 untuk memodernisasi dan mendigitalisasi proses absensi di sekolah. Sistem ini menggantikan absensi manual dengan sistem pemindaian QR Code yang cepat dan efisien, yang dapat diakses oleh siswa melalui perangkat mereka sendiri.

Aplikasi ini memiliki tiga peran pengguna utama: **Admin**, **Guru**, dan **Murid**, masing-masing dengan *dashboard* dan fungsionalitas yang disesuaikan untuk kebutuhannya.

-----

## ✨ Fitur Utama

Sistem ini kaya akan fitur yang dirancang untuk mengelola seluruh alur absensi dan kegiatan belajar mengajar.

### 👨‍💼 Admin

  * **Dashboard Statistik:** Melihat ringkasan data absensi, jumlah guru, siswa, dan kelas secara *real-time*.
  * **Manajemen Pengguna (CRUD):** Mengelola data lengkap untuk Guru, Murid, dan Admin lainnya.
  * **Manajemen Master Data:** Mengelola data vital sekolah seperti Kelas, Mata Pelajaran, dan Ruangan.
  * **Manajemen Jadwal Pelajaran:** Membuat dan mengatur jadwal pelajaran untuk semua kelas.
  * **🚀 Import Data Massal:** Fitur untuk mengimpor data Guru, Murid, Kelas, dan Jadwal Pelajaran secara massal menggunakan file Excel (`.xlsx`, `.csv`).
  * **Manajemen Pengumuman:** Membuat dan mempublikasikan pengumuman yang dapat dilihat oleh semua Guru dan Murid.
  * **Manajemen Delegasi:** Mengelola dan melihat riwayat delegasi sesi kelas yang dilakukan oleh guru.
  * **Laporan Absensi:** Menghasilkan laporan absensi terperinci (harian, mingguan, bulanan) dan mengekspornya ke format Excel.

### 👩‍🏫 Guru (Teacher)

  * **Dashboard Guru:** Menampilkan jadwal mengajar guru pada hari terkait.
  * **Manajemen Sesi Kelas:** Memulai sesi kelas sesuai jadwal, yang secara otomatis menghasilkan QR Code unik untuk absensi.
  * **Absensi QR Code:** Menampilkan QR Code di layar agar dapat dipindai oleh siswa.
  * **Manajemen Absensi Manual:** Menandai status absensi siswa (Hadir, Alfa, Izin, Sakit) secara manual jika siswa berhalangan memindai.
  * **Manajemen Perizinan:** Menerima, menyetujui, atau menolak permohonan izin yang diajukan oleh siswa.
  * **Delegasi Sesi:** Mendelegasikan sesi mengajarnya ke guru lain jika berhalangan hadir, lengkap dengan catatan.
  * **Melihat Pengumuman:** Melihat pengumuman terbaru dari Admin.

### 🎒 Murid (Student)

  * **Dashboard Murid:** Menampilkan jadwal pelajaran hari ini, statistik absensi personal (Hadir, Izin, Sakit, Alfa), dan pengumuman.
  * **📲 Absensi via Scan QR:** Melakukan absensi dengan memindai (scan) QR Code yang ditampilkan oleh guru di kelas menggunakan kamera *smartphone*.
  * **Jadwal Pelajaran:** Melihat jadwal pelajaran lengkap untuk kelasnya.
  * **Pengajuan Izin:** Mengajukan permohonan izin (Sakit atau Izin) secara digital dengan opsi untuk mengunggah dokumen pendukung (misal: surat dokter).
  * **Riwayat Absensi:** Melihat riwayat absensi personal secara detail per mata pelajaran dan tanggal.
  * **Melihat Pengumuman:** Melihat pengumuman terbaru dari Admin.

-----

## 🛠️ Teknologi yang Digunakan

Proyek ini dibangun menggunakan ekosistem Laravel modern dengan tumpukan teknologi berikut:

  * **Backend:** PHP 8.2+, **Laravel 11**
  * **Frontend:** Vite.js, Blade Templates, SCSS, JavaScript
  * **Database:** MySQL
  * **Paket PHP Utama:**
      * `maatwebsite/excel`: Untuk fungsionalitas import dan export data Excel.
  * **Library JavaScript Utama:**
      * `html5-qrcode`: Untuk fungsionalitas pemindai QR Code di sisi klien (murid).

-----

## 🚀 Instalasi dan Konfigurasi

Ikuti langkah-langkah ini untuk menjalankan proyek secara lokal.

### 1\. Prasyarat

  * PHP \>= 8.2
  * Composer
  * Node.js & NPM / Bun
  * Database (MySQL)

### 2\. Langkah-langkah Instalasi

1.  **Clone Repository:**

    ```bash
    git clone https://github.com/muhammadfathanfuad/web-absensi-smkn-4-kendari.git
    cd web-absensi-smkn-4-kendari
    ```

2.  **Install Dependensi PHP:**

    ```bash
    composer install
    ```

3.  **Konfigurasi Lingkungan:**

      * Salin file `.env.example` menjadi `.env`.
        ```bash
        cp .env.example .env
        ```
      * Hasilkan kunci aplikasi baru:
        ```bash
        php artisan key:generate
        ```
      * Atur koneksi database Anda (DB\_DATABASE, DB\_USERNAME, DB\_PASSWORD) di dalam file `.env`.

4.  **Migrasi dan Seeding Database:**

      * Jalankan migrasi untuk membuat semua tabel.
      * Jalankan *seeder* untuk mengisi data awal (terutama *roles* dan akun admin).

    <!-- end list -->

    ```bash
    php artisan migrate --seed
    ```

5.  **Symlink Storage:**

      * Buat *symbolic link* agar file yang diunggah (seperti foto profil dan bukti izin) dapat diakses.

    <!-- end list -->

    ```bash
    php artisan storage:link
    ```

6.  **Install Dependensi Frontend:**

      * Proyek ini menggunakan `bun.lockb`, jadi **Bun** direkomendasikan.

    <!-- end list -->

    ```bash
    # Menggunakan Bun (Direkomendasikan)
    bun install

    # Alternatif menggunakan NPM
    npm install
    ```

### 3\. Menjalankan Aplikasi

1.  **Jalankan Vite Server (untuk Frontend):**

      * Buka terminal baru dan jalankan *development server* Vite.

    <!-- end list -->

    ```bash
    # Menggunakan Bun
    bun run dev

    # Alternatif menggunakan NPM
    npm run dev
    ```

2.  **Jalankan Laravel Server (untuk Backend):**

      * Di terminal utama Anda, jalankan server Artisan.

    <!-- end list -->

    ```bash
    php artisan serve
    ```

3.  **Akses Aplikasi:**

      * Buka browser Anda dan kunjungi `http://127.0.0.1:8000`.

-----

## 🔑 Akun Default

Setelah menjalankan `php artisan migrate --seed`, akun admin default akan dibuat:

  * **Email:** `admin@gmail.com`
  * **Password:** `password`

Anda dapat menggunakan akun ini untuk login pertama kali dan mulai mengelola data sekolah.

-----

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License**. Lihat file `composer.json` untuk detailnya.

-----

<div align="center">
Dibuat dengan ❤️ oleh <a href="[https://github.com/muhammadfathanfuad](https://www.google.com/search?q=https://github.com/muhammadfathanfuad)">Tim Web KKN Tematik Teknik Informatika UHO 2025</a>
</div>
