<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'crop_name',
        'variety',
        'area_planted',
        'planting_date',
        'expected_harvest_date',
        'actual_harvest_date',
        'season',
        'status',
        'expected_yield',
        'actual_yield',
        'harvest_image',
        'notes',
    ];

    protected $casts = [
        'planting_date' => 'date',
        'expected_harvest_date' => 'date',
        'actual_harvest_date' => 'date',
        'area_planted' => 'decimal:2',
        'expected_yield' => 'decimal:2',
        'actual_yield' => 'decimal:2',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function progressLogs(): HasMany
    {
        return $this->hasMany(ProgressLog::class);
    }

    public function latestProgressLog(): HasOne
    {
        return $this->hasOne(ProgressLog::class)->latestOfMany();
    }

    public function getDaysPlantedAttribute(): int
    {
        return $this->planting_date->diffInDays(now());
    }

    public function getDaysToHarvestAttribute(): int
    {
        if (!$this->expected_harvest_date) return 0;
        return now()->diffInDays($this->expected_harvest_date, false);
    }

    public function getProgressPercentageAttribute(): float
    {
        if (!$this->expected_harvest_date) return 0;
        
        $totalDays = $this->planting_date->diffInDays($this->expected_harvest_date);
        $daysPassed = $this->planting_date->diffInDays(now());
        
        return min(100, round(($daysPassed / $totalDays) * 100, 1));
    }

    public function getCalculatedStatusAttribute(): string
    {
        // If actual harvest date OR actual yield is filled, status is 'harvested'
        if ($this->actual_harvest_date || $this->actual_yield) {
            return 'harvested';
        }

        // If expected harvest date has passed and no actual harvest/yield, status is 'failed'
        if ($this->expected_harvest_date && now()->isAfter($this->expected_harvest_date)) {
            return 'failed';
        }

        // If planting date is in the future, status is 'scheduled'
        if ($this->planting_date && now()->startOfDay()->lt($this->planting_date->startOfDay())) {
            return 'scheduled';
        }

        // Default to 'planted' (crop is in the ground)
        return 'planted';
    }
}