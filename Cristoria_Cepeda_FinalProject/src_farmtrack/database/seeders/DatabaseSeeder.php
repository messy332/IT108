<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            AdminUserSeeder::class,
            FarmerUserSeeder::class,
            DefaultFarmerAccountSeeder::class,
            FarmersMonitoringSeeder::class,
            AssignDoasDocumentSeeder::class,
            UpdateFarmerEmailsSeeder::class,
        ]);
    }

}
