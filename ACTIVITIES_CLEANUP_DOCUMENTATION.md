# Aktivitas Terbaru - Auto Cleanup

## Overview

Sistem ini secara otomatis membersihkan data aktivitas terbaru yang sudah berusia lebih dari 30 hari untuk menjaga performa dashboard admin.

## Fitur yang Diimplementasikan

### 1. Automatic Cleanup Command

-   **Command**: `php artisan activities:cleanup`
-   **Schedule**: Berjalan setiap bulan (30 hari sekali)
-   **Tabel yang dibersihkan**:
    -   `attendance_sessions` - Sesi absensi yang sudah selesai
    -   `leave_requests` - Permohonan izin siswa

### 2. Dashboard Filtering

-   Dashboard secara otomatis hanya menampilkan aktivitas dari 30 hari terakhir
-   Ini memberikan perlindungan tambahan jika cleanup command belum berjalan

## Cara Penggunaan

### Manual Cleanup

```bash
# Membersihkan aktivitas lebih dari 30 hari (default)
php artisan activities:cleanup

# Membersihkan aktivitas lebih dari 7 hari
php artisan activities:cleanup --days=7

# Membersihkan aktivitas lebih dari 60 hari
php artisan activities:cleanup --days=60
```

### Scheduled Cleanup

Command ini sudah terdaftar di Laravel scheduler dan akan berjalan otomatis setiap bulan. Pastikan cron job Laravel berjalan:

```bash
# Tambahkan ke crontab server
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

## Keamanan Data

### Yang TIDAK Dihapus:

-   Data absensi siswa (`attendances` table)
-   Data jadwal (`timetables` table)
-   Data pengguna (`users`, `teachers`, `students` tables)
-   Data kelas dan mata pelajaran

### Yang Dihapus:

-   Sesi absensi lama (`attendance_sessions` dengan `created_at` > 30 hari)
-   Permohonan izin lama (`leave_requests` dengan `created_at` > 30 hari)

## Monitoring

Command akan memberikan output detail tentang:

-   Jumlah attendance sessions yang dihapus
-   Jumlah leave requests yang dihapus
-   Total record yang dihapus
-   Tanggal cutoff yang digunakan

## Troubleshooting

Jika ada masalah dengan cleanup:

1. Cek log Laravel untuk error messages
2. Pastikan database connection berfungsi
3. Pastikan user memiliki permission untuk delete records
4. Test manual dengan command: `php artisan activities:cleanup --days=1`
