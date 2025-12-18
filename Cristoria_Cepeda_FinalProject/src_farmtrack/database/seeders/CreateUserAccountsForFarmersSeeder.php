<?php

namespace Database\Seeders;

use App\Models\Farmer;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateUserAccountsForFarmersSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating user accounts for farmers without accounts...');
        
        // Get the farmer role
        $farmerRole = Role::where('slug', 'farmer')->first();
        
        if (!$farmerRole) {
            $this->command->error('Farmer role not found! Please run RolesTableSeeder first.');
            return;
        }

        // Get all farmers without user accounts
        $farmersWithoutUsers = Farmer::whereNull('user_id')->get();
        
        if ($farmersWithoutUsers->isEmpty()) {
            $this->command->info('All farmers already have user accounts!');
            return;
        }

        $this->command->info("Found {$farmersWithoutUsers->count()} farmers without user accounts.");
        
        $batchSize = 500;
        $created = 0;
        $defaultPassword = Hash::make('farmer123'); // Default password for all farmers
        
        foreach ($farmersWithoutUsers->chunk($batchSize) as $batch) {
            DB::transaction(function () use ($batch, $farmerRole, $defaultPassword, &$created) {
                foreach ($batch as $farmer) {
                    // Create user account
                    $user = User::create([
                        'name' => $farmer->name,
                        'email' => $farmer->email,
                        'password' => $defaultPassword,
                        'role_id' => $farmerRole->id,
                        'email_verified_at' => now(),
                    ]);
                    
                    // Link farmer to user
                    $farmer->update(['user_id' => $user->id]);
                    
                    $created++;
                }
            });
            
            $this->command->info("Created {$created} user accounts so far...");
        }
        
        $this->command->info("Successfully created {$created} user accounts for farmers!");
        $this->command->info("Default password for all farmers: farmer123");
    }
}
