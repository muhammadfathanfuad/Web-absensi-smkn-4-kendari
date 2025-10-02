<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan pengguna admin
        User::create([
            'full_name' => 'Administrator',
            'email' => 'admin@example.com',
            'phone' => '081234567890',
            'username' => 'admin',
            'password_hash' => Hash::make('password123'),
            'status' => 'active',
            'last_login_at' => now(),
            'last_ip' => '127.0.0.1',
        ]);

        // Menambahkan pengguna guru
        User::create([
            'full_name' => 'Guru Matematika',
            'email' => 'guru.math@example.com',
            'phone' => '082345678901',
            'username' => 'guru_math',
            'password_hash' => Hash::make('password123'),
            'status' => 'active',
            'last_login_at' => now(),
            'last_ip' => '127.0.0.1',
        ]);

        // Menambahkan pengguna murid
        User::create([
            'full_name' => 'Murid A',
            'email' => null, // Murid menggunakan NIS sebagai pengganti email
            'phone' => '083456789012',
            'username' => 'murid_a',
            'password_hash' => Hash::make('password123'),
            'status' => 'active',
            'last_login_at' => now(),
            'last_ip' => '127.0.0.1',
        ]);
    }
}
