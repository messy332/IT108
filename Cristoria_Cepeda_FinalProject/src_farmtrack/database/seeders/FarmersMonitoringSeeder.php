<?php

namespace Database\Seeders;

use App\Models\Farmer;
use App\Models\Crop;
use App\Models\ProgressLog;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class FarmersMonitoringSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Deleting existing data...');
        
        // Delete in order: logs -> crops -> farmers (respecting foreign keys)
        ProgressLog::query()->delete();
        $this->command->info('Deleted all progress logs');
        
        Crop::query()->delete();
        $this->command->info('Deleted all crops');
        
        Farmer::query()->delete();
        $this->command->info('Deleted all farmers');
        
        $this->command->info('Creating farmers...');
        
        // Target volume: 10,000 farmers (batched)
        $batchSize = 1000;
        $totalFarmers = 10000;
        $faker = Faker::create();
        $usedNames = [];
        
        for ($batch = 0; $batch < $totalFarmers / $batchSize; $batch++) {
            $farmers = [];
            
            for ($i = 0; $i < $batchSize; $i++) {
                $farmerNumber = ($batch * $batchSize) + $i + 1;
                $gender = collect(['male', 'female'])->random();
                $age = rand(18, 75); // Age range 18-75 as per form validation
                $birthdate = Carbon::now()->subYears($age)->subDays(rand(0, 365));
                
                $name = $this->uniqueFarmerName($faker, $gender, $usedNames);
                $farmers[] = [
                    'name' => $name,
                    'email' => "farmer{$farmerNumber}@farmtrack.com",
                    'phone' => $this->generateNumericPhone(),
                    'address' => $this->generateAddress(),
                    'birthdate' => $birthdate,
                    'age' => $age,
                    'gender' => $gender,
                    'farm_size' => round(rand(1, 10000) / 100, 2), // 0.01 to 100.00 hectares
                    'farm_type' => 'crop', // Fixed to 'crop' as per form
                    'status' => rand(0, 10) > 1 ? 'active' : 'inactive', // 90% active
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            Farmer::insert($farmers);
            $this->command->info("Created farmers batch " . ($batch + 1) . " of " . ($totalFarmers / $batchSize));
        }

        $this->command->info('Creating crops for farmers...');
        $this->createCropsInBatches();
        
        $this->command->info('Creating progress logs...');
        $this->createProgressLogsInBatches();
    }

    private function createCropsInBatches(): void
    {
        // Rice varieties from the form dropdown
        $riceVarieties = [
            // Premium / Aromatic Market Varieties
            'Princess Bea', 'Dinorado', 'Super Dinorado', 'Angelica', 'Super Angelica',
            'Milagrosa', 'Harvester', 'Sinandomeng', 'Sinandomeng Special', 'Hasmine', 'Jasmine',
            // Regular Milled / Well-Milled Categories
            'Regular Rice', 'Well-Milled Rice', 'Premium Rice', 'Blue Label', 'Red Label',
            'Gold Label', 'Diamond Rice',
            // Glutinous / Sticky Rice
            'Malagkit White', 'Malagkit Black', 'Ominio', 'Diket',
            // Heirloom / Native Varieties
            'Tinawon White', 'Tinawon Red', 'Tinawon Pink', 'Unoy', 'Unoy Red Rice',
            'Balatinaw', 'Kintoman',
        ];

        $seasons = ['wet', 'dry', 'year-round'];

        // Target volume: 10,000 crops (batched)
        $batchSize = 1000;
        $totalCrops = 10000;
        $farmerIds = Farmer::pluck('id')->toArray();
        
        for ($batch = 0; $batch < $totalCrops / $batchSize; $batch++) {
            $crops = [];
            
            for ($i = 0; $i < $batchSize; $i++) {
                $variety = collect($riceVarieties)->random();
                $season = collect($seasons)->random();
                $plantingDate = Carbon::now()->subDays(rand(30, 365));
                $expectedHarvestDate = $plantingDate->copy()->addDays(rand(90, 150)); // Rice cycle 90-150 days
                
                $crops[] = [
                    'farmer_id' => collect($farmerIds)->random(),
                    'crop_name' => 'Rice', // Fixed to 'Rice' as per form
                    'variety' => $variety,
                    'area_planted' => round(rand(1, 500) / 100, 2), // 0.01 to 5.00 hectares
                    'planting_date' => $plantingDate,
                    'expected_harvest_date' => $expectedHarvestDate,
                    'actual_harvest_date' => rand(0, 10) > 7 ? $expectedHarvestDate->copy()->addDays(rand(-10, 20)) : null,
                    'season' => $season,
                    'status' => collect(['planted', 'growing', 'harvested', 'failed'])->random(),
                    'expected_yield' => rand(300, 5000), // 3-5 tons per hectare average
                    'actual_yield' => rand(0, 10) > 6 ? rand(250, 4800) : null,
                    'notes' => rand(0, 10) > 7 ? 'Good growth conditions observed' : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            Crop::insert($crops);
            $this->command->info("Created crops batch " . ($batch + 1) . " of " . ($totalCrops / $batchSize));
        }
    }

    private function createProgressLogsInBatches(): void
    {
        // Activity types from ProgressLog model
        $activities = [
            'land_preparation' => 'Prepared the land by plowing and leveling for rice planting',
            'seed_preparation_nursery' => 'Prepared seeds and set up nursery beds for seedling growth',
            'transplanting_seeding' => 'Transplanted rice seedlings from nursery to main field',
            'watering_irrigation' => 'Managed water levels and irrigation system for optimal growth',
            'fertilization' => 'Applied fertilizer to boost crop nutrition and yield',
            'weeding' => 'Removed weeds to prevent competition for nutrients',
            'pest_disease_management' => 'Applied pest control measures and disease prevention',
            'panicle_flowering_care' => 'Monitored panicle development and flowering stage',
            'preharvest_drainage' => 'Drained field and prepared for harvest',
            'harvesting' => 'Harvested mature rice crops',
        ];

        // Activity type to growth stage mapping (must match JavaScript mapping)
        $activityToGrowthStage = [
            'land_preparation' => 1,
            'seed_preparation_nursery' => 2,
            'transplanting_seeding' => 3,
            'watering_irrigation' => 4,
            'fertilization' => 5,
            'weeding' => 6,
            'pest_disease_management' => 7,
            'panicle_flowering_care' => 8,
            'preharvest_drainage' => 9,
            'harvesting' => 10,
        ];

        $weatherConditions = ['sunny', 'cloudy', 'rainy', 'windy', 'stormy'];
        
        // Target volume: 10,000 progress logs (batched)
        $batchSize = 1000;
        $totalLogs = 10000;
        $cropIds = Crop::pluck('id')->toArray();
        
        for ($batch = 0; $batch < $totalLogs / $batchSize; $batch++) {
            $logs = [];
            
            for ($i = 0; $i < $batchSize; $i++) {
                $activityType = collect(array_keys($activities))->random();
                $logDate = Carbon::now()->subDays(rand(1, 365));
                
                $logs[] = [
                    'crop_id' => collect($cropIds)->random(),
                    'log_date' => $logDate,
                    'activity_type' => $activityType,
                    'description' => $activities[$activityType],
                    'cost' => rand(0, 10) > 5 ? round(rand(50, 20000) / 10, 2) : null, // ₱5.00 to ₱2000.00
                    'weather_condition' => collect($weatherConditions)->random(),
                    'growth_stage' => $activityToGrowthStage[$activityType],
                    'observations' => rand(0, 10) > 6 ? 'Normal growth progress observed. Crop health is good.' : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            ProgressLog::insert($logs);
            $this->command->info("Created progress logs batch " . ($batch + 1) . " of " . ($totalLogs / $batchSize));
        }
    }

    private function uniqueFarmerName($faker, string $gender, array &$used): string
    {
        // Use faker then sanitize to letters/spaces, capitalize words, enforce single spaces
        $attempts = 0;
        do {
            $raw = $gender === 'male' ? $faker->name('male') : $faker->name('female');
            $name = $this->sanitizeName($raw);
            $attempts++;
            if ($attempts > 10) {
                // fallback: append random number to avoid infinite loop
                $name = $name . ' ' . rand(100, 999);
            }
        } while (isset($used[$name]));
        $used[$name] = true;
        return $name;
    }

    private function sanitizeName(string $raw): string
    {
        // Keep letters and spaces only
        $v = preg_replace('/[^A-Za-z ]+/', ' ', $raw);
        // Collapse spaces
        $v = trim(preg_replace('/\s+/', ' ', $v));
        // Title case words
        $v = implode(' ', array_map(function ($w) {
            return ucfirst(strtolower($w));
        }, array_filter(explode(' ', $v))));
        return $v;
    }

    private function generateNumericPhone(): string
    {
        // Digits only; 11-digit PH mobile-like number (09 + 9 digits)
        return '09' . str_pad((string)rand(0, 999999999), 9, '0', STR_PAD_LEFT);
    }

    private function generateAddress(): string
    {
        $barangays = ['San Jose', 'Poblacion', 'Maligaya', 'Santa Cruz', 'San Antonio', 'Bagong Silang', 'Riverside', 'Central', 'East', 'West'];
        $cities = ['Nueva Ecija', 'Laguna', 'Bulacan', 'Pampanga', 'Tarlac', 'Bataan', 'Zambales', 'Pangasinan', 'Cabanatuan', 'San Jose'];
        
        return 'Barangay ' . collect($barangays)->random() . ', ' . collect($cities)->random();
    }


}