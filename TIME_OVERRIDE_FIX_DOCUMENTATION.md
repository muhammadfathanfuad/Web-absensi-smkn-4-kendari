# Time Override System - Dokumentasi Lengkap

## Overview

Sistem Time Override telah diperbaiki untuk memastikan semua waktu di seluruh aplikasi menggunakan waktu yang di-override ketika fitur ini aktif. Sistem ini mencakup:

1. **Backend (PHP/Laravel)**: TimeOverrideService dengan helper functions
2. **Frontend (JavaScript)**: Time override helper untuk browser
3. **Views (Blade)**: Semua tampilan waktu menggunakan TimeOverrideService
4. **Controllers**: Semua controller menggunakan TimeOverrideService

## Komponen yang Telah Diperbaiki

### 1. TimeOverrideService (app/Services/TimeOverrideService.php)

**Fungsi yang Ditambahkan:**

-   `timestamp()` - Mendapatkan timestamp saat ini
-   `format($format)` - Format waktu dengan format tertentu
-   `toISOString()` - Format untuk JavaScript
-   `toJSON()` - Format JSON untuk JavaScript
-   `translatedFormat($format)` - Format dengan terjemahan
-   `localeFormat($format)` - Format dengan locale Indonesia
-   `carbon()` - Mendapatkan instance Carbon
-   `forDatabase()` - Format untuk database
-   `dateForDatabase()` - Format tanggal untuk database
-   `forFilename()` - Format untuk nama file

### 2. Helper Functions (app/helpers.php)

**Fungsi Global yang Tersedia:**

-   `time_now()` - Waktu saat ini
-   `time_today()` - Tanggal saat ini
-   `time_timestamp()` - Timestamp saat ini
-   `time_format($format)` - Format waktu
-   `time_day_of_week()` - Hari dalam seminggu
-   `time_for_js()` - Format untuk JavaScript
-   `time_locale($format)` - Format dengan locale
-   `time_translated($format)` - Format dengan terjemahan

### 3. TimeHelper Class (app/Helpers/TimeHelper.php)

**Class Helper untuk penggunaan yang lebih mudah:**

```php
use App\Helpers\TimeHelper;

$now = TimeHelper::now();
$today = TimeHelper::today();
$formatted = TimeHelper::format('Y-m-d H:i:s');
```

### 4. Blade Directives (TimeOverrideServiceProvider)

**Directive yang Tersedia:**

-   `@time_now` - Waktu saat ini
-   `@time_today` - Tanggal saat ini
-   `@time_format('Y-m-d H:i:s')` - Format waktu
-   `@time_locale('dddd, D MMMM Y')` - Format dengan locale
-   `@time_translated('l, j F Y')` - Format dengan terjemahan

### 5. JavaScript Helper (public/js/time-override.js)

**Fitur JavaScript:**

-   Override `new Date()` constructor
-   Override `Date.now()` function
-   Format waktu dengan format tertentu
-   Set dan clear override time
-   Mendapatkan waktu saat ini

### 6. Initialization Script (public/js/time-override-init.js)

**Fitur Otomatis:**

-   Memuat data time override dari server
-   Update semua tampilan waktu di halaman
-   Menampilkan indikator time override
-   Re-initialize saat navigasi

## File yang Telah Diperbaiki

### Views:

-   `resources/views/murid/jadwal-pelajaran.blade.php`
-   `resources/views/murid/dashboard.blade.php`
-   `resources/views/guru/dashboard.blade.php`
-   `resources/views/guru/jadwal-mengajar.blade.php`
-   `resources/views/admin/laporan.blade.php`
-   `resources/views/guru/pengaturan-guru.blade.php`
-   `resources/views/layouts/base.blade.php`

### Controllers:

-   `app/Http/Controllers/Murid/ScanController.php`
-   `app/Http/Controllers/Guru/AbsensiController.php`
-   `app/Http/Controllers/Admin/AdminReportController.php`
-   `app/Http/Controllers/TimeOverrideController.php`

### Routes:

-   `routes/web.php` - Menambahkan endpoint `/time-override/js-data`

## Cara Penggunaan

### 1. Di Controller

```php
use App\Services\TimeOverrideService;

// Mendapatkan waktu saat ini
$now = TimeOverrideService::now();

// Mendapatkan tanggal saat ini
$today = TimeOverrideService::today();

// Format waktu
$formatted = TimeOverrideService::format('Y-m-d H:i:s');

// Untuk database
$dbTime = TimeOverrideService::forDatabase();
```

### 2. Di View (Blade)

```blade
{{-- Menggunakan TimeOverrideService langsung --}}
{{ \App\Services\TimeOverrideService::now()->format('Y-m-d H:i:s') }}

{{-- Menggunakan helper functions --}}
{{ time_now()->format('Y-m-d H:i:s') }}

{{-- Menggunakan Blade directives --}}
@time_format('Y-m-d H:i:s')
```

### 3. Di JavaScript

```javascript
// Mendapatkan waktu saat ini (akan menggunakan override jika aktif)
const now = new Date();

// Menggunakan helper
const currentTime = window.TimeOverrideHelper.getCurrentTime();

// Format waktu
const formatted = window.TimeOverrideHelper.formatTime("Y-m-d H:i:s");
```

## Testing

### 1. Test Time Override

1. Buka halaman `/time-override`
2. Set waktu override ke waktu tertentu
3. Navigasi ke semua halaman dan periksa apakah waktu sudah berubah

### 2. Halaman yang Harus Ditest

-   Dashboard Admin (`/admin/dashboard`)
-   Dashboard Guru (`/guru/dashboard`)
-   Dashboard Murid (`/murid/dashboard`)
-   Jadwal Pelajaran (`/murid/jadwal-pelajaran`)
-   Jadwal Mengajar (`/guru/jadwal-mengajar`)
-   Laporan (`/admin/laporan`)
-   Absensi (`/guru/absensi`)

### 3. Fitur yang Harus Ditest

-   Tampilan tanggal di header halaman
-   Status jam pelajaran (belum dimulai, sedang berlangsung, selesai)
-   Waktu scan QR code
-   Waktu absensi
-   Filter tanggal di laporan
-   Semua timestamp di database

## Troubleshooting

### 1. Waktu Tidak Berubah

-   Pastikan TimeOverrideService digunakan di semua tempat
-   Periksa apakah ada penggunaan `Carbon::now()` atau `now()` langsung
-   Pastikan JavaScript helper dimuat

### 2. JavaScript Tidak Terpengaruh

-   Pastikan script `time-override.js` dan `time-override-init.js` dimuat
-   Periksa console browser untuk error
-   Pastikan endpoint `/time-override/js-data` dapat diakses

### 3. Database Timestamp Tidak Berubah

-   Pastikan menggunakan `TimeOverrideService::forDatabase()` untuk query
-   Periksa apakah ada penggunaan `now()` langsung di query

## Catatan Penting

1. **Selalu gunakan TimeOverrideService** untuk semua operasi waktu
2. **Jangan gunakan `Carbon::now()` atau `now()` langsung** di kode baru
3. **Test semua halaman** setelah mengaktifkan time override
4. **Periksa JavaScript** jika ada interaksi waktu di frontend
5. **Gunakan helper functions** untuk kemudahan penggunaan

## Update Terbaru

-   ✅ Semua view menggunakan TimeOverrideService
-   ✅ Semua controller menggunakan TimeOverrideService
-   ✅ JavaScript helper untuk frontend
-   ✅ Helper functions global
-   ✅ Blade directives
-   ✅ Service provider untuk integrasi
-   ✅ Endpoint untuk JavaScript
-   ✅ Initialization script otomatis
-   ✅ Indikator visual time override

