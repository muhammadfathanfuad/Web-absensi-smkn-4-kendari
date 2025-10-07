<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Menampilkan halaman pengumuman.
     */
    public function index()
    {
        // --- DATA STATIS UNTUK CONTOH ---
        $pengumuman = [
            [
                'waktu' => '08:00 WITA',
                'tanggal' => '5/September/2045',
                'isi' => 'Sosialisasi MBG akan di laksanakan pada tanggal 6/September/2045 selama 2 jam, karena pembelajaran di jam itu tidak dilaksanakan. Kepada bapak ibu guru agar mengarahkan siswanya ke aula',
                'penulis' => 'Kepala Sekolah'
            ],
            [
                'waktu' => '10:30 WITA',
                'tanggal' => '4/September/2045',
                'isi' => 'Diberitahukan kepada seluruh siswa kelas XII bahwa kegiatan persiapan Ujian Kompetensi Keahlian (UKK) akan dimulai minggu depan. Harap setiap siswa mempersiapkan diri.',
                'penulis' => 'Waka Kurikulum'
            ]
        ];

        return view('guru.pengumuman', compact('pengumuman'));
    }
}
