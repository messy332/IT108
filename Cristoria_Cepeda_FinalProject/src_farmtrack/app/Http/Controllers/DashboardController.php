<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Crop;
use App\Models\ProgressLog;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect farmers to their dashboard
        if (auth()->user()->isFarmer()) {
            return redirect()->route('farmer.dashboard');
        }
        $stats = [
            'total_farmers' => Farmer::count(),
            'active_farmers' => Farmer::where('status', 'active')->count(),
            'total_crops' => Crop::count(),
            'active_crops' => Crop::whereIn('status', ['planted', 'growing'])->count(),
            'harvested_crops' => Crop::where('status', 'harvested')->count(),
            'total_logs' => ProgressLog::count(),
            'total_farm_area' => Farmer::sum('farm_size'),
            'total_yield' => Crop::sum('actual_yield'),
        ];

        // Recent activities
        $recentLogs = ProgressLog::with(['crop.farmer'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Crops needing attention (no logs in last 7 days)
        $cropsNeedingAttention = Crop::with(['farmer', 'latestProgressLog'])
            ->whereIn('status', ['planted', 'growing'])
            ->whereDoesntHave('progressLogs', function($query) {
                $query->where('log_date', '>=', now()->subDays(7));
            })
            ->limit(5)
            ->get();

        // Upcoming harvests (next 30 days)
        $upcomingHarvests = Crop::with('farmer')
            ->where('status', '!=', 'harvested')
            ->whereBetween('expected_harvest_date', [now(), now()->addDays(30)])
            ->orderBy('expected_harvest_date')
            ->limit(5)
            ->get();

        // Gender distribution for pie chart
        $genderStats = [
            'female' => Farmer::where('gender', 'female')->count(),
            'male' => Farmer::where('gender', 'male')->count(),
        ];

        // Activity type distribution for pie chart
        $activityStats = ProgressLog::selectRaw('activity_type, COUNT(*) as count')
            ->groupBy('activity_type')
            ->get()
            ->mapWithKeys(function($item) {
                $activityTypes = ProgressLog::getActivityTypes();
                $label = $activityTypes[$item->activity_type] ?? $item->activity_type;
                return [$item->activity_type => [
                    'label' => $label,
                    'count' => $item->count
                ]];
            })
            ->toArray();

        // Top 10 most planted rice varieties (by count)
        $topRiceVarieties = Crop::selectRaw('variety, COUNT(*) as plant_count, SUM(area_planted) as total_hectares')
            ->groupBy('variety')
            ->orderBy('plant_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'variety' => $item->variety,
                    'count' => $item->plant_count,
                    'hectares' => round($item->total_hectares, 2)
                ];
            })
            ->values()
            ->toArray();

        // Crop hectares by variety for bar chart (keeping for backward compatibility)
        $cropHectares = Crop::selectRaw('variety, SUM(area_planted) as total_hectares')
            ->groupBy('variety')
            ->orderBy('total_hectares', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'variety' => $item->variety,
                    'hectares' => round($item->total_hectares, 2)
                ];
            })
            ->values()
            ->toArray();

        // Prepare recent months data for charts
        $recentMonths = $recentLogs->map(function($log) {
            return $log->log_date->format('Y-m');
        })->values()->toArray();

        // Get farmers with farm locations - Load all farmers with optimized data
        $farmersWithLocation = Farmer::select('id', 'name', 'latitude', 'longitude', 'farm_size')
            ->with(['crops' => function($query) {
                $query->select('id', 'farmer_id', 'crop_name', 'variety', 'status', 'area_planted', 'planting_date', 'expected_harvest_date')
                    ->whereIn('status', ['planted', 'growing']) // Only load active crops for map
                    ->limit(10); // Limit crops per farmer for performance
            }])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function($farmer) {
                // Simplify data structure for better JSON performance
                return [
                    'id' => $farmer->id,
                    'name' => $farmer->name,
                    'latitude' => $farmer->latitude,
                    'longitude' => $farmer->longitude,
                    'farm_size' => $farmer->farm_size,
                    'crops' => $farmer->crops->map(function($crop) {
                        return [
                            'crop_name' => $crop->crop_name,
                            'variety' => $crop->variety,
                            'status' => $crop->status,
                            'area_planted' => $crop->area_planted,
                            'planting_date' => $crop->planting_date,
                            'expected_harvest_date' => $crop->expected_harvest_date,
                        ];
                    })->toArray()
                ];
            });

        // Calculate average farm size
        $avgFarmSize = Farmer::whereNotNull('farm_size')
            ->where('farm_size', '>', 0)
            ->avg('farm_size');

        // Get total rice farms (crops)
        $totalRiceFarms = Crop::count();

        return view('dashboard', compact(
            'stats', 
            'recentLogs', 
            'cropsNeedingAttention', 
            'upcomingHarvests',
            'genderStats',
            'activityStats',
            'cropHectares',
            'topRiceVarieties',
            'recentMonths',
            'farmersWithLocation',
            'avgFarmSize',
            'totalRiceFarms'
        ));
    }

    public function search()
    {
        $query = request('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Search farmers
        $farmers = Farmer::where('name', 'like', "%{$query}%")
            ->orWhere('address', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'address', 'farm_size']);

        foreach ($farmers as $farmer) {
            $results[] = [
                'type' => 'farmer',
                'id' => $farmer->id,
                'title' => $farmer->name,
                'subtitle' => $farmer->address . ' • ' . ($farmer->farm_size ?? 'N/A') . ' ha',
                'url' => route('farmers.show', $farmer->id)
            ];
        }

        // Search crops
        $crops = Crop::with('farmer')
            ->where('crop_name', 'like', "%{$query}%")
            ->orWhere('variety', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($crops as $crop) {
            $results[] = [
                'type' => 'crop',
                'id' => $crop->id,
                'title' => $crop->crop_name . ' (' . $crop->variety . ')',
                'subtitle' => $crop->farmer->name . ' • ' . ucfirst($crop->status),
                'url' => route('crops.show', $crop->id)
            ];
        }

        // Search activities
        $activities = ProgressLog::with('crop.farmer')
            ->where('activity_type', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($activities as $activity) {
            $results[] = [
                'type' => 'activity',
                'id' => $activity->id,
                'title' => $activity->activity_type,
                'subtitle' => $activity->crop->farmer->name . ' • ' . $activity->crop->crop_name . ' • ' . $activity->log_date->format('M j, Y'),
                'url' => route('progress-logs.index') . '?crop_id=' . $activity->crop_id
            ];
        }

        return response()->json($results);
    }
}