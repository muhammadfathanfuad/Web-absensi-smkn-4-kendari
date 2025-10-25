# Panduan Import Jadwal Pelajaran

## 📋 Format Excel yang Didukung

Sistem mendukung **2 format** file Excel untuk import jadwal pelajaran:

### Format 1: Format Tradisional (Multi-Kelas)

```
HARI    | WAKTU         | TKJA  | TKJB  | TKJC  | RPLA  | ...
--------|---------------|-------|-------|-------|-------|----
SENIN   | 07.00 - 08.00 | UPACARA BENDERA | | | | | ...
        | 08.00 - 08.40 | A4/39 | A1/04 | B1/21 | A6/36 | ...
        | 08.40 - 09.20 | A4/39 | A1/04 | B1/21 | A6/36 | ...
```

**Karakteristik:**

-   ✅ Header: HARI, WAKTU, [Nama Kelas 1], [Nama Kelas 2], ...
-   ✅ Kolom 1: Hari (SENIN, SELASA, dll)
-   ✅ Kolom 2: Waktu (07.00 - 08.00)
-   ✅ Kolom 3+: Data kelas (A4/39, A1/04, dll)

### Format 2: Format dengan Header (Single-Kelas)

```
JADWAL PEMBELAJARAN KELAS X SMK NEGERI 4 KENDARI
SEMESTER GASAL TAHUN PELAJARAN 2025/2026

HARI | JAM | WAKTU         | KELAS X
-----|-----|---------------|--------
     |     |               | TKJA | TKJB | TKJC | ...
SENIN| 0   | 07.00 - 08.00 | UPACARA BENDERA | | | ...
     | 1   | 08.00 - 08.40 | A4/39 | A1/04 | B1/21 | ...
     | 2   | 08.40 - 09.20 | A4/39 | A1/04 | B1/21 | ...
```

**Karakteristik:**

-   ✅ Baris 1-3: Judul dan informasi
-   ✅ Baris 4: Header (HARI, JAM, WAKTU, KELAS X)
-   ✅ Baris 5: Nama kelas (TKJA, TKJB, TKJC, ...)
-   ✅ Baris 6+: Data jadwal

## 🔧 Cara Kerja Sistem

### 1. Auto-Detection Format

Sistem akan otomatis mendeteksi format file Excel:

-   **Format 1**: Jika header mengandung "HARI" dan "WAKTU" + kolom kelas
-   **Format 2**: Jika ada baris judul + header "HARI", "JAM", "WAKTU"

### 2. Processing Logic

```php
// Format 1: Langsung proses dari baris 1
HARI | WAKTU | TKJA | TKJB | ...

// Format 2: Skip baris judul, mulai dari baris 4
JUDUL | ... | ...
HARI  | JAM | WAKTU | KELAS X
```

### 3. Data Validation

-   ✅ **Hari**: SENIN, SELASA, RABU, KAMIS, JUMAT, SABTU, MINGGU
-   ✅ **Waktu**: Format HH.MM - HH.MM (07.00 - 08.00)
-   ✅ **Kelas**: Format KodeMataPelajaran/KodeGuru (A4/39)
-   ✅ **Special Entries**: UPACARA BENDERA, ISTRAHAT, dll (akan di-skip)

## 📝 Persyaratan Data

### 1. Mata Pelajaran

-   ✅ Kode mata pelajaran harus sudah ada di database
-   ✅ Format: huruf + angka (A4, B2, C1, D3)
-   ❌ Jika tidak ditemukan: Error dengan pesan jelas

### 2. Guru

-   ✅ Kode guru harus sudah ada di database
-   ✅ Format: angka (39, 25, 15, 42)
-   ✅ **Auto-matching**: 04 → 4, 05 → 5 (leading zero)
-   ❌ Jika tidak ditemukan: Error dengan pesan jelas

### 3. Kelas

-   ✅ Kelas akan dibuat otomatis jika belum ada
-   ✅ Nama: TKJA, TKJB, TKJC (tanpa suffix)
-   ✅ Grade: 10, 11, 12 (dari dropdown)
-   ✅ Display: TKJA-X, TKJB-XI, TKJC-XII

## 🚨 Troubleshooting

### Error: "Teacher with kode_guru 'XX' not found"

**Solusi:**

1. Pastikan guru dengan kode tersebut sudah diimport ke database
2. Periksa kode guru di file Excel
3. Sistem akan otomatis mencocokkan dengan leading zero (04 → 4)

### Error: "Subject with code 'XX' not found"

**Solusi:**

1. Pastikan mata pelajaran dengan kode tersebut sudah diimport ke database
2. Periksa kode mata pelajaran di file Excel
3. Format harus: huruf + angka (A4, B2, C1)

### Error: "Tidak ada term aktif"

**Solusi:**

1. Buat term aktif di sistem sebelum melakukan import
2. Pastikan ada term dengan status aktif

### Error: "Tipe minggu harus dipilih untuk kelas XI dan XII"

**Solusi:**

1. Untuk kelas XI dan XII, wajib memilih tipe minggu (ganjil/genap)
2. Kelas X tidak memerlukan tipe minggu

### Error: "Invalid class info format"

**Solusi:**

1. Pastikan format: KodeMataPelajaran/KodeGuru
2. Contoh: A4/39, B2/25, C1/15
3. Jangan gunakan spasi atau karakter lain

## 📊 Format File Excel yang Valid

### ✅ Format yang Didukung:

**Format 1 - Multi-Kelas:**

```
HARI    | WAKTU         | TKJA  | TKJB  | TKJC
--------|---------------|-------|-------|-------
SENIN   | 07.00 - 08.00 | UPACARA BENDERA | | |
        | 08.00 - 08.40 | A4/39 | A1/04 | B1/21
        | 08.40 - 09.20 | A4/39 | A1/04 | B1/21
```

**Format 2 - Single-Kelas:**

```
JADWAL PEMBELAJARAN KELAS X
SEMESTER GASAL 2025/2026

HARI | JAM | WAKTU         | KELAS X
-----|-----|---------------|--------
     |     |               | TKJA | TKJB
SENIN| 0   | 07.00 - 08.00 | UPACARA | |
     | 1   | 08.00 - 08.40 | A4/39 | A1/04
```

### ❌ Format yang Tidak Didukung:

```
❌ Format tanpa header yang jelas
❌ Format dengan kolom yang tidak konsisten
❌ Format dengan data yang tidak lengkap
❌ Format dengan encoding yang salah
```

## 🔄 Proses Import

### 1. Pre-Import Checks

-   ✅ Validasi file Excel (format, ukuran)
-   ✅ Validasi grade dan week_type
-   ✅ Cek term aktif
-   ✅ Auto-detect format file

### 2. Data Processing

-   ✅ Parse header dan struktur
-   ✅ Validasi format waktu
-   ✅ Validasi format mata pelajaran/guru
-   ✅ Skip special entries
-   ✅ Handle leading zero pada kode guru

### 3. Database Operations

-   ✅ Buat/update kelas dengan grade
-   ✅ Buat/update class_subject
-   ✅ Buat timetable entries
-   ✅ Handle duplikasi

### 4. Post-Import

-   ✅ Log hasil import
-   ✅ Tampilkan jumlah entri yang diproses
-   ✅ Error reporting jika ada

## 📈 Monitoring dan Logging

### Log Files

-   📁 `storage/logs/laravel.log`
-   🔍 Cari: "Processing row", "Created classroom", "Error processing"

### Success Indicators

-   ✅ "Enhanced timetable import completed. Processed X entries."
-   ✅ "Created new classroom: TKJA with grade 10"
-   ✅ "Found teacher with code '4' for input '04'"

### Error Indicators

-   ❌ "Teacher with kode_guru 'XX' not found"
-   ❌ "Subject with code 'XX' not found"
-   ❌ "Invalid class info format"

## 🎯 Best Practices

### 1. File Preparation

-   ✅ Gunakan format yang konsisten
-   ✅ Pastikan header sesuai dengan format yang didukung
-   ✅ Periksa kode guru dan mata pelajaran sebelum import
-   ✅ Gunakan format waktu yang benar (HH.MM - HH.MM)

### 2. Data Validation

-   ✅ Import guru dan mata pelajaran terlebih dahulu
-   ✅ Pastikan term aktif sudah dibuat
-   ✅ Pilih grade yang sesuai (X, XI, XII)
-   ✅ Pilih week_type untuk XI dan XII

### 3. Error Handling

-   ✅ Periksa log file jika import gagal
-   ✅ Validasi data sebelum import
-   ✅ Backup data sebelum import besar
-   ✅ Test dengan file kecil terlebih dahulu

## 🔧 Advanced Configuration

### Custom Format Support

Jika ada format Excel yang tidak didukung, sistem dapat diperluas dengan:

1. **Menambah Format Detection**

```php
private function detectFormat(Collection $rows)
{
    // Tambahkan logika deteksi format baru
    if ($this->isFormat3($rows)) {
        $this->detectedFormat = 'format3';
    }
}
```

2. **Menambah Processing Logic**

```php
private function processFormat3($row, $index, $termId, $daysMap, &$errors)
{
    // Implementasi format baru
}
```

### Performance Optimization

-   ✅ Import dalam batch untuk file besar
-   ✅ Gunakan transaction untuk konsistensi data
-   ✅ Optimasi query database
-   ✅ Memory management untuk file besar

---

## 📞 Support

Jika mengalami masalah dengan import jadwal:

1. **Periksa log file** di `storage/logs/laravel.log`
2. **Validasi format** file Excel sesuai panduan
3. **Pastikan data** guru dan mata pelajaran sudah ada
4. **Test dengan file kecil** terlebih dahulu
5. **Gunakan enhanced import** untuk format yang berbeda

**Format yang didukung:** Format 1 (Multi-Kelas) dan Format 2 (Single-Kelas)
**Auto-detection:** ✅ Aktif
**Error handling:** ✅ Comprehensive
**Logging:** ✅ Detailed
