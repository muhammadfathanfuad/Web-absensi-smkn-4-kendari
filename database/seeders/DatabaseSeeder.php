<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            UserRolesTableSeeder::class,
            TeachersTableSeeder::class,
            ClassesTableSeeder::class,
            StudentsTableSeeder::class,
            TermsTableSeeder::class,
            SubjectsTableSeeder::class,
            ClassSubjectsTableSeeder::class,
            TimetablesTableSeeder::class,
            SessionsTableSeeder::class,
        ]);
    }
}
