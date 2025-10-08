<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            // User ID: 1
            [
                'full_name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password_hash' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // User ID: 2
            [
                'full_name' => 'Budi Setiawan, S.Pd.',
                'username' => 'budi.guru',
                'email' => 'guru@example.com',
                'password_hash' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // User ID: 3
            [
                'full_name' => 'Ahmad Budi',
                'username' => 'ahmad.budi',
                'email' => 'ahmad.budi@example.com',
                'password_hash' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // User ID: 4
            [
                'full_name' => 'Siti Aminah',
                'username' => 'siti.aminah',
                'email' => 'siti.aminah@example.com',
                'password_hash' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}