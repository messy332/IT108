<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farmer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DefaultFarmerAccountSeeder extends Seeder
{
    public function run()
    {
        // Get the farmer role ID
        $farmerRole = \App\Models\Role::where('slug', 'farmer')->first();
        
        if (!$farmerRole) {
            $this->command->error('Farmer role not found! Please run RolesTableSeeder first.');
            return;
        }

        // Create or update default farmer user
        $user = User::updateOrCreate(
            ['email' => 'defaultfarmer@gmail.com'],
            [
                'name' => 'Default Farmer',
                'password' => Hash::make('Farmer#1010'),
                'role_id' => $farmerRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Create or update associated farmer profile
        Farmer::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'Default Farmer',
                'email' => 'defaultfarmer@gmail.com',
                'phone' => '09123456789',
                'address' => 'Barangay Poblacion, Nueva Ecija',
                'birthdate' => Carbon::now()->subYears(35),
                'age' => 35,
                'gender' => 'male',
                'farm_size' => 2.50,
                'farm_type' => 'crop',
                'status' => 'active',
            ]
        );

        $this->command->info('Default farmer account created:');
        $this->command->info('Email: defaultfarmer@gmail.com');
        $this->command->info('Password: Farmer#1010');
    }
}
