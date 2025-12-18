<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_id',
        'log_date',
        'activity_type',
        'description',
        'cost',
        'weather_condition',
        'growth_stage',
        'observations',
        'images',
    ];

    protected $casts = [
        'log_date' => 'date',
        'cost' => 'decimal:2',
        'images' => 'array',
    ];

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public static function getActivityTypes(): array
    {
        return [
            'land_preparation' => 'Land Preparation',
            'seed_preparation_nursery' => 'Seed Preparation & Nursery',
            'transplanting_seeding' => 'Transplanting or Direct Seeding',
            'watering_irrigation' => 'Watering / Irrigation Management',
            'fertilization' => 'Fertilization',
            'weeding' => 'Weeding',
            'pest_disease_management' => 'Pest & Disease Management',
            'panicle_flowering_care' => 'Panicle Initiation / Flowering Care',
            'preharvest_drainage' => 'Pre-Harvest Drainage / Sanitation',
            'harvesting' => 'Harvesting',
        ];
    }

    public static function getWeatherConditions(): array
    {
        return [
            'sunny' => 'Sunny',
            'cloudy' => 'Cloudy',
            'rainy' => 'Rainy',
            'stormy' => 'Stormy',
            'windy' => 'Windy',
        ];
    }
}