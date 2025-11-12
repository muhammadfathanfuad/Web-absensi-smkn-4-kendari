<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class BaseUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Create base admin user for initial access to the system
     */
    public function run()
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'full_name' => 'Administrator',
                'email' => 'admin@example.com',
                'password_hash' => Hash::make('password'),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Update password if user already exists (in case password was changed)
        if ($admin->wasRecentlyCreated) {
            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists.');
        }

        $this->command->info('Admin User - Email: admin@example.com, Password: password');
        $this->command->warn('⚠️  Please change the default password after first login!');
    }
}

