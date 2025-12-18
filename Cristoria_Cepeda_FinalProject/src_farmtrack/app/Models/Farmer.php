<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'latitude',
        'longitude',
        'farm_boundaries',
        'birthdate',
        'age',
        'gender',
        'farm_size',
        'farm_type',
        'supporting_document',
        'status',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'farm_size' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'farm_boundaries' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function crops(): HasMany
    {
        return $this->hasMany(Crop::class);
    }

    public function activeCrops(): HasMany
    {
        return $this->hasMany(Crop::class)->where('status', '!=', 'harvested');
    }

    public function getTotalYieldAttribute(): float
    {
        return $this->crops()->sum('actual_yield') ?? 0;
    }

    public function getAverageGrowthStageAttribute(): float
    {
        $crops = $this->activeCrops()->with('latestProgressLog')->get();
        if ($crops->isEmpty()) return 0;

        $totalStage = $crops->sum(function ($crop) {
            return $crop->latestProgressLog?->growth_stage ?? 0;
        });

        return round($totalStage / $crops->count(), 1);
    }
}