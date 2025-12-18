<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Seed default roles if not present
        DB::table('roles')->updateOrInsert(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'slug' => 'admin', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('roles')->updateOrInsert(
            ['slug' => 'farmer'],
            ['name' => 'Farmer', 'slug' => 'farmer', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('roles')->updateOrInsert(
            ['slug' => 'user'],
            ['name' => 'User', 'slug' => 'user', 'created_at' => now(), 'updated_at' => now()]
        );
    }
}
