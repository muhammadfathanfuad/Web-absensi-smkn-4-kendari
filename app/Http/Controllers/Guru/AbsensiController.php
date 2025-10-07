<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman status absensi.
     */
    public function index()
    {
        // --- DATA STATIS UNTUK CONTOH ---

        // Data untuk filter dropdown
        $kelas = [
            ['id' => 1, 'nama' => 'XII RPL 1'],
            ['id' => 2, 'nama' => 'XI TKJ 2'],
            ['id' => 3, 'nama' => 'X MM 1'],
        ];

        $mapel = [
            ['id' => 1, 'nama' => 'Matematika'],
            ['id' => 2, 'nama' => 'Bahasa Indonesia'],
            ['id' => 3, 'nama' => 'Pemrograman Web'],
        ];

        // Daftar siswa dengan penambahan kolom 'status'
        // null = Belum Absen
        // 'H' = Hadir (misalnya dari scan QR)
        // 'S' = Sakit (misalnya diinput manual oleh guru)
        $students = [
            ['nis' => '12345', 'nama' => 'Ahmad Budi Santoso', 'jk' => 'Laki-laki', 'status' => 'H'],
            ['nis' => '12346', 'nama' => 'Siti Aminah', 'jk' => 'Perempuan', 'status' => null],
            ['nis' => '12347', 'nama' => 'Joko Susilo', 'jk' => 'Laki-laki', 'status' => 'S'],
            ['nis' => '12348', 'nama' => 'Putri Lestari', 'jk' => 'Perempuan', 'status' => 'H'],
            ['nis' => '12349', 'nama' => 'Dewi Anggraini', 'jk' => 'Perempuan', 'status' => null],
            ['nis' => '12350', 'nama' => 'Rizky Pratama', 'jk' => 'Laki-laki', 'status' => null],
            ['nis' => '12351', 'nama' => 'Eka Yulianti', 'jk' => 'Perempuan', 'status' => null],
        ];


        // Kirim data ke view
        return view('guru.status-absensi', compact('kelas', 'mapel', 'students'));
    }
}
