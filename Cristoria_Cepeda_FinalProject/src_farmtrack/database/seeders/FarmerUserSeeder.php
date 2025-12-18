<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FarmerUserSeeder extends Seeder
{
    public function run()
    {
        // Get the farmer role ID
        $farmerRole = \App\Models\Role::where('slug', 'farmer')->first();
        
        if (!$farmerRole) {
            $this->command->error('Farmer role not found! Please run RolesTableSeeder first.');
            return;
        }

        // Create or update farmer user
        User::updateOrCreate(
            ['email' => 'farmer@gmail.com'],
            [
                'name' => 'Test Farmer',
                'password' => Hash::make('farmer123'),
                'role_id' => $farmerRole->id,
                'email_verified_at' => now(),
            ]
        );
        
        $this->command->info('Farmer user created: farmer@gmail.com / farmer123');
    }
}
