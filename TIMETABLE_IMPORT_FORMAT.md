# Format File Excel untuk Import Jadwal Pelajaran

## Struktur File Excel

File Excel harus memiliki struktur sebagai berikut:

### Kolom 1: Hari

-   Berisi nama hari dalam bahasa Indonesia (senin, selasa, rabu, kamis, jumat, sabtu, minggu)
-   Case insensitive (huruf besar/kecil tidak berpengaruh)

### Kolom 2: Waktu

-   Format: "HH.MM - HH.MM" (contoh: "07.00 - 08.00")
-   Menggunakan titik (.) sebagai pemisah jam dan menit
-   Menggunakan tanda hubung (-) untuk memisahkan waktu mulai dan selesai

### Kolom 3 dan seterusnya: Nama Kelas

-   Header kolom berisi nama kelas (contoh: TKJA, TKJB, TKJC, dll)
-   Data dalam sel berisi informasi mata pelajaran dan guru
-   Format: "KodeMataPelajaran/KodeGuru" (contoh: "A4/39")

## Contoh Format File

| Hari   | Waktu         | TKJA  | TKJB  | TKJC  |
| ------ | ------------- | ----- | ----- | ----- |
| senin  | 07.00 - 08.00 | A4/39 | B2/25 | C1/15 |
| senin  | 08.00 - 09.00 | D3/42 | A4/39 | B2/25 |
| selasa | 07.00 - 08.00 | C1/15 | D3/42 | A4/39 |

## Persyaratan Data

### 1. Mata Pelajaran

-   Kode mata pelajaran harus sudah ada di database
-   Format kode: huruf dan angka (contoh: A4, B2, C1, D3)

### 2. Guru

-   Kode guru harus sudah ada di database
-   Format kode: angka (contoh: 39, 25, 15, 42)
-   Sistem akan otomatis mencocokkan kode dengan atau tanpa leading zero (04 → 4, 05 → 5)

### 3. Kelas

-   Kelas akan dibuat otomatis jika belum ada
-   Nama kelas akan diformat dengan grade: `TKJA-X`, `TKJB-X`, dll
-   Grade akan diambil dari dropdown yang dipilih saat import
-   Jika kelas sudah ada dengan grade berbeda, grade akan diupdate

## Catatan Penting

1. **Term Aktif**: Pastikan ada term aktif di database sebelum melakukan import
2. **Grade Selection**: Pilih grade yang sesuai dari dropdown (X, XI, XII)
3. **Kelas XI dan XII**: Untuk kelas XI dan XII, wajib memilih tipe minggu (ganjil/genap)
4. **Format Waktu**: Gunakan format 24 jam dengan titik sebagai pemisah
5. **Pemisah Data**: Gunakan tanda slash (/) untuk memisahkan kode mata pelajaran dan kode guru
6. **Header**: Baris pertama harus berisi header (Hari, Waktu, Nama Kelas)
7. **Format Kelas**: Kelas akan otomatis diformat dengan grade (TKJA-X, TKJB-X, dll)

## Troubleshooting

### Error: "Teacher with kode_guru 'XX' not found"

-   Pastikan guru dengan kode tersebut sudah diimport ke database
-   Periksa kode guru di file Excel

### Error: "Subject with code 'XX' not found"

-   Pastikan mata pelajaran dengan kode tersebut sudah diimport ke database
-   Periksa kode mata pelajaran di file Excel

### Error: "Tidak ada term aktif"

-   Buat term aktif di sistem sebelum melakukan import
-   Pastikan ada term dengan status aktif

### Error: "Tipe minggu harus dipilih untuk kelas XI dan XII"

-   Untuk kelas XI dan XII, wajib memilih tipe minggu (ganjil atau genap)
-   Kelas X tidak memerlukan tipe minggu
