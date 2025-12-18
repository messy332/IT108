<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\ProgressLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProgressLogController extends Controller
{
    public function index(): View
    {
        $q = request('q');
        $growthStageFilter = request('growth_stage');
        $varietyFilter = request('variety');
        
        $logs = ProgressLog::with(['crop.farmer'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('activity_type', 'like', "%$q%")
                        ->orWhere('weather_condition', 'like', "%$q%")
                        ->orWhere('description', 'like', "%$q%")
                        ->orWhereDate('log_date', $q)
                        ->orWhereHas('crop', function ($q2) use ($q) {
                            $q2->where('crop_name', 'like', "%$q%")
                               ->orWhereHas('farmer', function ($q3) use ($q) {
                                   $q3->where('name', 'like', "%$q%")
                                      ->orWhere('email', 'like', "%$q%");
                               });
                        });
                });
            })
            ->when($growthStageFilter, function ($query) use ($growthStageFilter) {
                $query->where('growth_stage', $growthStageFilter);
            })
            ->when($varietyFilter, function ($query) use ($varietyFilter) {
                $query->whereHas('crop', function ($q) use ($varietyFilter) {
                    $q->where('variety', $varietyFilter);
                });
            })
            ->orderBy('log_date', 'desc')
            ->paginate(20)
            ->appends([
                'q' => $q,
                'growth_stage' => $growthStageFilter,
                'variety' => $varietyFilter
            ]);

        // Get distinct varieties for filter dropdown
        $varieties = Crop::distinct()->pluck('variety')->filter()->sort()->values();

        return view('progress-logs.index', compact('logs', 'varieties'));
    }

    public function show(ProgressLog $progressLog): View
    {
        $progressLog->load(['crop.farmer']);
        return view('progress-logs.show', compact('progressLog'));
    }

    public function create(): View
    {
        $crops = Crop::with('farmer')
            ->where('status', '!=', 'harvested')
            ->get();

        // Get all progress logs grouped by crop_id and activity_type
        $existingLogs = ProgressLog::all()
            ->groupBy('crop_id')
            ->map(function ($logs) {
                return $logs->pluck('activity_type')->unique()->values()->toArray();
            })
            ->toArray();

        return view('progress-logs.create', compact('crops', 'existingLogs'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'crop_id' => 'required|exists:crops,id',
                'log_date' => 'required|date|after:2020-01-01',
                'activity_type' => 'required|string|in:' . implode(',', array_keys(ProgressLog::getActivityTypes())),
                'description' => 'nullable|string|max:1000',
                'cost' => 'required|numeric|min:0|max:100000',
                'weather_condition' => 'required|string|in:' . implode(',', array_keys(ProgressLog::getWeatherConditions())),
                'growth_stage' => 'nullable|integer|min:1|max:10',
                'observations' => 'nullable|string|max:1000',
            ], [
                'crop_id.required' => 'Please select a crop.',
                'crop_id.exists' => 'Selected crop does not exist.',
                'log_date.required' => 'Activity date is required.',
                'log_date.after' => 'Activity date must be after 2020.',
                'activity_type.required' => 'Activity type is required.',
                'activity_type.in' => 'Please select a valid activity type.',

                'description.max' => 'Description cannot exceed 1,000 characters.',
                'cost.required' => 'Cost is required.',
                'cost.min' => 'Cost cannot be negative.',
                'cost.max' => 'Cost cannot exceed 100,000.',
                'weather_condition.required' => 'Weather condition is required.',
                'weather_condition.in' => 'Please select a valid weather condition.',
                'growth_stage.min' => 'Growth stage must be between 1 and 10.',
                'growth_stage.max' => 'Growth stage must be between 1 and 10.',
                'observations.max' => 'Observations cannot exceed 1,000 characters.',
            ]);

            // Check if crop exists and validate log date against crop dates
            $crop = Crop::find($validated['crop_id']);
            
            if ($validated['log_date'] < $crop->planting_date->format('Y-m-d')) {
                return back()->withErrors(['log_date' => 'Activity date cannot be before the crop planting date.'])->withInput();
            }

            if ($crop->actual_harvest_date && $validated['log_date'] > $crop->actual_harvest_date->format('Y-m-d')) {
                return back()->withErrors(['log_date' => 'Activity date cannot be after the crop harvest date.'])->withInput();
            }

            // Check for duplicate logs (activity type must be unique per crop)
            $existingLog = ProgressLog::where('crop_id', $validated['crop_id'])
                ->where('activity_type', $validated['activity_type'])
                ->first();

            if ($existingLog) {
                return back()->withErrors(['activity_type' => 'This activity type has already been logged for this crop.'])->withInput();
            }

            $log = ProgressLog::create($validated);

            return redirect()->route('crops.show', $log->crop)
                ->with('success', 'Progress log added successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while saving the progress log. Please try again.'])->withInput();
        }
    }

    public function edit(ProgressLog $progressLog): View
    {
        $crops = Crop::with('farmer')
            ->where('status', '!=', 'harvested')
            ->get();

        return view('progress-logs.edit', compact('progressLog', 'crops'));
    }

    public function update(Request $request, ProgressLog $progressLog): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'crop_id' => 'required|exists:crops,id',
                'log_date' => 'required|date|after:2020-01-01',
                'activity_type' => 'required|string|in:' . implode(',', array_keys(ProgressLog::getActivityTypes())),
                'description' => 'nullable|string|max:1000',
                'cost' => 'required|numeric|min:0|max:100000',
                'weather_condition' => 'required|string|in:' . implode(',', array_keys(ProgressLog::getWeatherConditions())),
                'growth_stage' => 'nullable|integer|min:1|max:10',
                'observations' => 'nullable|string|max:1000',
            ], [
                'crop_id.required' => 'Please select a crop.',
                'crop_id.exists' => 'Selected crop does not exist.',
                'log_date.required' => 'Activity date is required.',
                'log_date.after' => 'Activity date must be after 2020.',
                'activity_type.required' => 'Activity type is required.',
                'activity_type.in' => 'Please select a valid activity type.',

                'description.max' => 'Description cannot exceed 1,000 characters.',
                'cost.required' => 'Cost is required.',
                'cost.min' => 'Cost cannot be negative.',
                'cost.max' => 'Cost cannot exceed 100,000.',
                'weather_condition.required' => 'Weather condition is required.',
                'weather_condition.in' => 'Please select a valid weather condition.',
                'growth_stage.min' => 'Growth stage must be between 1 and 10.',
                'growth_stage.max' => 'Growth stage must be between 1 and 10.',
                'observations.max' => 'Observations cannot exceed 1,000 characters.',
            ]);

            // Check if crop exists and validate log date against crop dates
            $crop = Crop::find($validated['crop_id']);
            
            if ($validated['log_date'] < $crop->planting_date->format('Y-m-d')) {
                return back()->withErrors(['log_date' => 'Activity date cannot be before the crop planting date.'])->withInput();
            }

            if ($crop->actual_harvest_date && $validated['log_date'] > $crop->actual_harvest_date->format('Y-m-d')) {
                return back()->withErrors(['log_date' => 'Activity date cannot be after the crop harvest date.'])->withInput();
            }

            // Check for duplicate logs (excluding current log)
            $existingLog = ProgressLog::where('crop_id', $validated['crop_id'])
                ->where('log_date', $validated['log_date'])
                ->where('activity_type', $validated['activity_type'])
                ->where('id', '!=', $progressLog->id)
                ->first();

            if ($existingLog) {
                return back()->withErrors(['log_date' => 'A log for this activity already exists on this date for this crop.'])->withInput();
            }

            $progressLog->update($validated);

            return redirect()->route('progress-logs.show', $progressLog)
                ->with('success', 'Progress log updated successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while updating the progress log. Please try again.'])->withInput();
        }
    }

    public function destroy(ProgressLog $progressLog): RedirectResponse
    {
        try {
            $crop = $progressLog->crop;
            $activityType = $progressLog->activity_type;
            $logDate = $progressLog->log_date->format('M j, Y');
            
            $progressLog->delete();

            return redirect()->route('crops.show', $crop)
                ->with('success', "Progress log '{$activityType}' from {$logDate} deleted successfully!");

        } catch (\Exception $e) {
            return redirect()->route('progress-logs.index')
                ->with('error', 'An error occurred while deleting the progress log. Please try again.');
        }
    }
}