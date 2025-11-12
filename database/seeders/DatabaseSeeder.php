<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Base data seeder for initial access to the system.
     * 
     * Run: php artisan db:seed
     */
    public function run(): void
    {
        // Base data seeder - urutan penting untuk menghindari error relasi
        $this->call([
            // 1. Create roles (admin, teacher, student)
            RolesTableSeeder::class,
            
            // 2. Create admin user
            BaseUsersSeeder::class,
            
            // 3. Assign admin role to admin user
            BaseUserRolesSeeder::class,
        ]);
        
        $this->command->info('✅ Base data seeding completed!');
        $this->command->info('📧 Login with: admin@example.com / password');
    }
}