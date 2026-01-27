# 📚 PresenZ (Sistem Absensi Digital SMK Negeri 4 Kendari)

Sistem Absensi Digital berbasis web yang dirancang khusus untuk **SMK Negeri 4 Kendari**. Aplikasi ini mentransformasi proses kehadiran konvensional menjadi digital menggunakan teknologi **QR Code**, memudahkan manajemen jadwal, serta menyediakan pelaporan yang akurat bagi Admin, Guru, dan Siswa.

---

## 🎯 Fitur Unggulan

### 👨‍💼 Panel Admin

* **Dashboard Statistik:** Visualisasi data total guru, siswa, kelas, dan aktivitas sistem.
* **Manajemen User:** Fitur import data dari Excel untuk guru dan siswa, serta manajemen foto profil.
* **Manajemen Akademik:** Pengaturan jadwal pelajaran, mata pelajaran, kelas, dan semester (term).
* **Sistem Delegasi:** Fitur untuk memberikan tugas pengganti kepada guru atau siswa lain saat guru utama berhalangan hadir.

### 👨‍🏫 Panel Guru

* **Absensi QR Code:** Fitur *Generate QR Code* dinamis dengan sistem *timer countdown* dan *auto-refresh*.
* **Catat Kehadiran:** Tombol kehadiran mandiri yang aktif pada jam operasional (07:00 - 14:45).
* **Laporan & Riwayat:** Rekapitulasi kehadiran siswa per mata pelajaran dengan filter periode semester.

### 👨‍🎓 Panel Siswa

* **Scan Kehadiran:** Melakukan absensi melalui scan QR Code dari guru di dalam kelas.
* **Pengajuan Izin:** Form pengajuan izin/sakit dengan fitur upload dokumen pendukung.
* **Jadwal Real-time:** Tampilan jadwal pelajaran harian yang informatif.

---

## 🛠️ Teknologi yang Digunakan

* **Framework Utama:** Laravel 11.31.
* **Frontend:** Bootstrap 5.3, Vite, & jQuery.
* **Database:** MySQL/SQLite dengan Eloquent ORM.
* **Library Pendukung:**
* `DomPDF` untuk ekspor laporan ke PDF.
* `Maatwebsite Excel` untuk manajemen data Excel.
* `HTML5-QRCode` & `Pusher` untuk fitur scan dan notifikasi real-time.

---

## 🚀 Cara Instalasi

1. **Clone Repositori:**
```bash
git clone https://github.com/muhammadfathanfuad/web-absensi-smkn-4-kendari.git
cd web-absensi-smkn-4-kendari

```


2. **Instalasi Dependensi:**
```bash
composer install
npm install

```


3. **Konfigurasi Environment:**
Salin file `.env.example` ke `.env` dan sesuaikan kredensial database Anda.
4. **Migrasi & Seed Data:**
```bash
php artisan migrate --seed

```


5. **Jalankan Aplikasi:**
```bash
php artisan serve
npm run dev

```



---

## ⚖️ Lisensi & HAKI

### **Pernyataan Hak Cipta**

Seluruh kode sumber dan aset dalam proyek ini dilindungi oleh Undang-Undang No. 28 Tahun 2014 tentang Hak Cipta.

* **Pencipta:** Muhammad Fathan Fuad.
* **Judul Ciptaan:** PresenZ.
* **Jenis Ciptaan:** Program Komputer.
* **Nomor Pencatatan:** 001011218.
* **Lisensi:** Terbuka di bawah MIT License. Hak moral dan hak ekonomi atas ciptaan ini dilindungi oleh Undang-Undang No. 28 Tahun 2014 tentang Hak Cipta.

### **Lisensi Perangkat Lunak**

Proyek ini dilisensikan di bawah **MIT License**. Anda diperbolehkan untuk menggunakan, memodifikasi, dan mendistribusikan perangkat lunak ini dengan syarat tetap menyertakan atribusi pencipta asli.

---

## 📝 Catatan Penting: Time Override

Sistem ini menyertakan `TimeOverrideService` untuk keperluan pengujian fungsionalitas berbasis waktu (seperti absensi otomatis), yang memungkinkan pengguna mengatur waktu sistem secara kustom melalui dashboard.

---

**Dibuat oleh Muhammad Fathan Fuad**
