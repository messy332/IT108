<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Get the admin role ID
        $adminRole = \App\Models\Role::where('slug', 'admin')->first();
        
        if (!$adminRole) {
            $this->command->error('Admin role not found! Please run RolesTableSeeder first.');
            return;
        }

        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin_1010'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
