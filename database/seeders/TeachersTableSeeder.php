<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User; // Pastikan model User di-import

class TeachersTableSeeder extends Seeder
{
    public function run()
    {
        // Ambil user_id berdasarkan username atau email yang sesuai
        $teacherUser = User::where('username', 'guru_math')->first(); // Ganti dengan username yang sesuai

        // Pastikan user ditemukan
        if ($teacherUser) {
            Teacher::create([
                'user_id' => $teacherUser->id,  // Menggunakan user_id yang valid
                'nip' => '1234567890',
                'department' => 'Matematika',
                'title' => 'S.Pd.'
            ]);
        } else {
            // Jika user tidak ditemukan, bisa mencetak pesan atau menambahkan handling error
            echo "User guru_math tidak ditemukan.\n";
        }
    }
}
