<?php

namespace Database\Seeders;

use App\Models\Farmer;
use Illuminate\Database\Seeder;

class UpdateFarmerEmailsSeeder extends Seeder
{
    public function run()
    {
        $farmers = Farmer::all();

        foreach ($farmers as $farmer) {
            if (str_contains($farmer->email, '@farmtrack.com')) {
                $emailParts = explode('@', $farmer->email);
                $farmer->email = $emailParts[0] . '@gmail.com';
                $farmer->save();
            }
        }
    }
}
