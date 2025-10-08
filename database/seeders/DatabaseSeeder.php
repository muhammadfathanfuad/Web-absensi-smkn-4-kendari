<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan ini SANGAT PENTING untuk menghindari error relasi
        $this->call([
            // 1. Data master yang tidak bergantung pada data lain
            RolesTableSeeder::class,
            RoomsTableSeeder::class,
            SubjectsTableSeeder::class,
            TermsTableSeeder::class,

            // 2. Data pengguna
            UsersTableSeeder::class,

            // 3. Data yang bergantung pada pengguna (user)
            UserRolesTableSeeder::class,
            TeachersTableSeeder::class,

            // 4. Data yang bergantung pada data master dan guru
            ClassesTableSeeder::class,

            // 5. Data yang bergantung pada pengguna (user) dan kelas
            StudentsTableSeeder::class,

            // 6. Data relasi jadwal
            ClassSubjectsTableSeeder::class,
            TimetablesTableSeeder::class,
            ClassSessionsTableSeeder::class,
        ]);
    }
}