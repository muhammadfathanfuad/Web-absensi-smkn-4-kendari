<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Pastikan role admin ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Data untuk Administrator 1
        $admin1 = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'full_name' => 'Administrator 1',
                'email' => 'admin@example.com',
                'password_hash' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Data untuk Administrator 2
        $admin2 = User::firstOrCreate(
            ['email' => 'admin2@example.com'],
            [
                'full_name' => 'Administrator 2',
                'email' => 'admin2@example.com',
                'password_hash' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Assign role admin ke kedua user
        if (!$admin1->roles()->where('role_id', $adminRole->id)->exists()) {
            $admin1->roles()->attach($adminRole->id);
        }

        if (!$admin2->roles()->where('role_id', $adminRole->id)->exists()) {
            $admin2->roles()->attach($adminRole->id);
        }

        $this->command->info('Admin users created successfully!');
        $this->command->info('Administrator 1 - Email: admin@example.com, Password: password');
        $this->command->info('Administrator 2 - Email: admin2@example.com, Password: password');
    }
}
