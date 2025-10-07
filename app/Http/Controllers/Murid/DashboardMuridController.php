<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Jika nanti Anda memerlukan model, tambahkan di sini. Contoh:
// use App\Models\Jadwal;
// use App\Models\Pengumuman;

class DashboardMuridController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk murid.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Di sini Anda bisa menambahkan logika untuk mengambil data
        // yang dibutuhkan di halaman dashboard, misalnya:
        // $pengumumanTerbaru = Pengumuman::latest()->first();
        // $jadwalHariIni = Jadwal::where('hari', now()->dayOfWeek)->get();

        // Untuk saat ini, kita hanya menampilkan view-nya saja
        return view('murid.dashboard');
    }

    /**
     * Menampilkan halaman jadwal pelajaran.
     * (Belum dibuat, ini hanya contoh)
     *
     * @return \Illuminate\View\View
     */
    public function jadwal()
    {
        // Logika untuk mengambil data jadwal pelajaran murid
        // $jadwal = Jadwal::where('kelas_id', $murid->kelas_id)->get();
        
        // return view('murid.jadwal-pelajaran', compact('jadwal'));
        
        // Karena view belum dibuat, kita arahkan ke dashboard saja sebagai placeholder
        return view('murid.dashboard')->with('info', 'Halaman Jadwal Pelajaran sedang dalam pengembangan.');
    }

    /**
     * Menampilkan halaman status absensi.
     * (Belum dibuat, ini hanya contoh)
     *
     * @return \Illuminate\View\View
     */
    public function absensi()
    {
        // Logika untuk mengambil riwayat absensi murid
        // $riwayatAbsensi = Absensi::where('murid_id', $murid->id)->get();
        
        // return view('murid.status-absensi', compact('riwayatAbsensi'));
        
        return view('murid.dashboard')->with('info', 'Halaman Status Absensi sedang dalam pengembangan.');
    }

    /**
     * Menampilkan halaman pengumuman.
     * (Belum dibuat, ini hanya contoh)
     *
     * @return \Illuminate\View\View
     */
    public function pengumuman()
    {
        // Logika untuk mengambil semua pengumuman
        // $pengumuman = Pengumuman::all();
        
        // return view('murid.pengumuman', compact('pengumuman'));
        
        return view('murid.dashboard')->with('info', 'Halaman Pengumuman sedang dalam pengembangan.');
    }
}