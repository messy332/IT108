<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Crop;
use App\Models\ProgressLog;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function summary(): JsonResponse
    {
        $totalFarmers = Farmer::count();
        // Prefer declared farm_size on farmers; fallback to sum of crop areas if none
        $totalFarmArea = (float) Farmer::sum('farm_size');
        if ($totalFarmArea <= 0) {
            $totalFarmArea = (float) Crop::sum('area_planted');
        }
        $activeCrops = Crop::where('status', '!=', 'harvested')->count();
        $totalLogs = ProgressLog::count();

        return response()->json([
            'total_farmers' => $totalFarmers,
            'total_farm_area' => round($totalFarmArea, 1),
            'active_crops' => $activeCrops,
            'total_logs' => $totalLogs,
            'updated_at' => now()->toIso8601String(),
        ]);
    }
}
