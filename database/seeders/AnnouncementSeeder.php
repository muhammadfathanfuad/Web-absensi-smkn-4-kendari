<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use App\Services\TimeOverrideService;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user (assuming first user is admin)
        $admin = User::first();
        
        if (!$admin) {
            $this->command->warn('No admin user found. Please create a user first.');
            return;
        }

        $announcements = [
            [
                'title' => 'Perubahan Jadwal Ujian Semester Ganjil',
                'content' => 'Diberitahukan kepada seluruh guru dan siswa bahwa jadwal ujian semester ganjil akan dimajukan 1 minggu dari jadwal semula. Silakan sesuaikan rencana pembelajaran dan persiapan ujian Anda.',
                'target' => 'all',
                'priority' => 'urgent',
                'category' => 'penting',
                'is_active' => true,
                'expires_at' => TimeOverrideService::now()->addDays(30),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Workshop Teknologi Pendidikan',
                'content' => 'Akan diadakan workshop penggunaan teknologi dalam pembelajaran pada tanggal 15 Desember 2024. Diharapkan semua guru dapat mengikuti kegiatan ini untuk meningkatkan kompetensi dalam penggunaan teknologi pendidikan.',
                'target' => 'teachers',
                'priority' => 'high',
                'category' => 'kegiatan',
                'is_active' => true,
                'expires_at' => TimeOverrideService::now()->addDays(45),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Update Sistem Absensi Online',
                'content' => 'Sistem absensi online telah diperbarui dengan fitur-fitur baru yang lebih user-friendly. Silakan gunakan fitur terbaru untuk memudahkan proses absensi siswa dan monitoring kehadiran.',
                'target' => 'teachers',
                'priority' => 'normal',
                'category' => 'umum',
                'is_active' => true,
                'expires_at' => now()->addDays(60),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Ujian Tengah Semester (UTS)',
                'content' => 'Ujian Tengah Semester akan dilaksanakan pada tanggal 15-20 Oktober 2024. Silakan persiapkan diri dengan baik dan pastikan semua materi pembelajaran telah dipahami.',
                'target' => 'students',
                'priority' => 'urgent',
                'category' => 'akademik',
                'is_active' => true,
                'expires_at' => now()->addDays(20),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Pekan Olahraga Sekolah (POS)',
                'content' => 'Pekan Olahraga Sekolah akan dilaksanakan pada tanggal 25-30 Oktober 2024. Daftarkan diri Anda segera untuk mengikuti berbagai cabang olahraga yang tersedia!',
                'target' => 'students',
                'priority' => 'high',
                'category' => 'kegiatan',
                'is_active' => true,
                'expires_at' => now()->addDays(25),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Jadwal Remedial',
                'content' => 'Jadwal remedial untuk mata pelajaran yang belum tuntas akan dimulai minggu depan. Silakan cek jadwal di portal dan persiapkan diri untuk mengikuti remedial.',
                'target' => 'students',
                'priority' => 'normal',
                'category' => 'akademik',
                'is_active' => true,
                'expires_at' => now()->addDays(35),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Perubahan Jam Masuk Sekolah',
                'content' => 'Mulai bulan November, jam masuk sekolah berubah menjadi 07:00 WITA. Harap disesuaikan dengan jadwal baru ini dan datang tepat waktu.',
                'target' => 'all',
                'priority' => 'high',
                'category' => 'umum',
                'is_active' => true,
                'expires_at' => now()->addDays(50),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Pembayaran SPP Bulan Oktober',
                'content' => 'Bagi yang belum membayar SPP bulan Oktober, harap segera menyelesaikan pembayaran sebelum tanggal 31 Oktober. Pembayaran dapat dilakukan melalui bank atau langsung ke bendahara sekolah.',
                'target' => 'students',
                'priority' => 'urgent',
                'category' => 'penting',
                'is_active' => true,
                'expires_at' => now()->addDays(10),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Hari Sumpah Pemuda',
                'content' => 'Dalam rangka memperingati Hari Sumpah Pemuda, akan diadakan berbagai kegiatan menarik seperti lomba pidato, lomba poster, dan pertunjukan seni. Daftarkan diri Anda untuk berpartisipasi!',
                'target' => 'students',
                'priority' => 'normal',
                'category' => 'kegiatan',
                'is_active' => true,
                'expires_at' => now()->addDays(40),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Pelatihan Penggunaan Aplikasi Sekolah',
                'content' => 'Akan diadakan pelatihan penggunaan aplikasi sekolah untuk guru-guru baru. Pelatihan akan dilaksanakan pada hari Sabtu, 2 November 2024 di ruang multimedia.',
                'target' => 'teachers',
                'priority' => 'normal',
                'category' => 'kegiatan',
                'is_active' => true,
                'expires_at' => now()->addDays(15),
                'created_by' => $admin->id,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }

        $this->command->info('Announcements seeded successfully!');
    }
}