<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class BaseUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Assign admin role to the admin user
     */
    public function run()
    {
        // Find admin user and admin role
        $adminUser = User::where('email', 'admin@example.com')->first();
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminUser) {
            $this->command->error('Admin user not found! Please run BaseUsersSeeder first.');
            return;
        }

        if (!$adminRole) {
            $this->command->error('Admin role not found! Please run RolesTableSeeder first.');
            return;
        }

        // Assign admin role to admin user (idempotent)
        if (!$adminUser->roles()->where('role_id', $adminRole->id)->exists()) {
            $adminUser->roles()->attach($adminRole->id);
            $this->command->info('Admin role assigned to admin user successfully!');
        } else {
            $this->command->info('Admin role already assigned to admin user.');
        }
    }
}

