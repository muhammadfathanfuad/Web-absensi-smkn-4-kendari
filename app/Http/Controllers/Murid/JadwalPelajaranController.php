<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalPelajaranController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk murid.
     */
    public function index()
    {
        return view('murid.jadwal-pelajaran');
    }

    /**
     * Menampilkan halaman jadwal pelajaran.
     * UBAH FUNGSI INI
     */
    public function jadwal()
    {
        // Logika untuk mengambil data jadwal bisa ditambahkan di sini nanti
        return view('murid.jadwal-pelajaran');
    }

    /**
     * Menampilkan halaman status absensi.
     */
    public function absensi()
    {
        // Placeholder, bisa dibuatkan view-nya nanti
        return view('murid.dashboard')->with('info', 'Halaman Status Absensi sedang dalam pengembangan.');
    }

    /**
     * Menampilkan halaman pengumuman.
     */
    public function pengumuman()
    {
        // Placeholder
        return view('murid.dashboard')->with('info', 'Halaman Pengumuman sedang dalam pengembangan.');
    }
}