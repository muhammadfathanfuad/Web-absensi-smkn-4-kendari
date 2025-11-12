<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Create base roles for the system: admin, teacher, student
     * Using firstOrCreate to make it idempotent (can be run multiple times)
     */
    public function run()
    {
        // Menambahkan role dengan firstOrCreate untuk idempotent
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'teacher']);
        Role::firstOrCreate(['name' => 'student']);
        
        $this->command->info('Base roles created successfully!');
    }
}
