<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CropController extends Controller
{
    public function index(): View
    {
        $q = request('q');
        $progressMin = request('progress_min');
        $progressMax = request('progress_max');
        $yieldMin = request('yield_min');
        $yieldMax = request('yield_max');
        $variety = request('variety');
        $status = request('status');

        // Get unique varieties for the dropdown (sorted alphabetically)
        $varieties = Crop::select('variety')
            ->distinct()
            ->orderBy('variety')
            ->pluck('variety');

        // Available statuses
        $statuses = ['scheduled', 'planted', 'growing', 'harvested', 'failed'];

        $crops = Crop::with(['farmer', 'latestProgressLog'])
            ->withCount('progressLogs')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('crop_name', 'like', "%$q%")
                        ->orWhere('variety', 'like', "%$q%")
                        ->orWhere('status', 'like', "%$q%")
                        ->orWhere('season', 'like', "%$q%")
                        ->orWhereHas('farmer', function ($q2) use ($q) {
                            $q2->where('name', 'like', "%$q%")
                               ->orWhere('email', 'like', "%$q%");
                        });
                });
            })
            ->when($variety, function ($query) use ($variety) {
                $query->where('variety', $variety);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($progressMin !== null, function ($query) use ($progressMin) {
                $query->where('progress_percentage', '>=', $progressMin);
            })
            ->when($progressMax !== null, function ($query) use ($progressMax) {
                $query->where('progress_percentage', '<=', $progressMax);
            })
            ->when($yieldMin !== null, function ($query) use ($yieldMin) {
                $query->where('actual_yield', '>=', $yieldMin);
            })
            ->when($yieldMax !== null, function ($query) use ($yieldMax) {
                $query->where('actual_yield', '<=', $yieldMax);
            })
            ->orderBy('crop_name')
            ->paginate(15)
            ->appends([
                'q' => $q,
                'progress_min' => $progressMin,
                'progress_max' => $progressMax,
                'yield_min' => $yieldMin,
                'yield_max' => $yieldMax,
                'variety' => $variety,
                'status' => $status,
            ]);

        return view('crops.index', compact('crops', 'varieties', 'statuses'));
    }

    public function show(Crop $crop): View
    {
        $crop->load(['farmer', 'progressLogs' => function($query) {
            $query->orderBy('log_date', 'desc');
        }]);

        return view('crops.show', compact('crop'));
    }

    public function create(): View
    {
        $farmers = Farmer::where('status', 'active')->get();
        return view('crops.create', compact('farmers'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'farmer_id' => 'required|exists:farmers,id',
                'crop_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'variety' => 'required|string|max:255',
                'custom_variety' => 'nullable|string|max:255|regex:/^[A-Z][a-zA-Z]+(?: [a-zA-Z]+)*$/',
                'area_planted' => 'required|numeric|min:0.01|max:1000',
                'planting_date' => 'required|date|after_or_equal:today',
                'expected_harvest_date' => 'required|date|after:planting_date|before:2030-12-31',
                'season' => 'required|in:wet,dry,year-round',
                'expected_yield' => 'required|numeric|min:0|max:500000',
                'harvest_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'notes' => 'nullable|string|max:1000',
            ], [
                'farmer_id.required' => 'This field is required.',
                'farmer_id.exists' => 'Selected farmer does not exist.',
                'crop_name.required' => 'This field is required.',
                'crop_name.regex' => 'Crop name should only contain letters and spaces.',
                'variety.required' => 'This field is required.',
                'custom_variety.regex' => 'Rice variety must start with a capital letter, contain only letters and spaces, and no double spaces.',
                'area_planted.required' => 'This field is required.',
                'area_planted.min' => 'Area planted must be at least 0.01 hectares.',
                'area_planted.max' => 'Area planted cannot exceed 1,000 hectares.',
                'planting_date.required' => 'This field is required.',
                'planting_date.after_or_equal' => 'Planting date must be today or a future date.',
                'expected_harvest_date.required' => 'This field is required.',
                'expected_harvest_date.after' => 'Expected harvest date must be after planting date.',
                'expected_harvest_date.before' => 'Expected harvest date must be before 2030.',
                'season.required' => 'This field is required.',
                'expected_yield.required' => 'This field is required.',
                'expected_yield.max' => 'Expected yield cannot exceed 500,000 kg.',
                'notes.max' => 'Notes cannot exceed 1,000 characters.',
            ]);

            // Handle custom variety
            if ($validated['variety'] === 'others') {
                if (empty($validated['custom_variety'])) {
                    return back()->withErrors(['custom_variety' => 'This field is required.'])->withInput();
                }
                $validated['variety'] = $validated['custom_variety'];
            }
            unset($validated['custom_variety']);

            // Check if farmer exists and is active
            $farmer = Farmer::find($validated['farmer_id']);
            if ($farmer->status !== 'active') {
                return back()->withErrors(['farmer_id' => 'Cannot add crops to inactive farmers.'])->withInput();
            }

            // Check if farmer's total crop area doesn't exceed farm size
            if ($farmer->farm_size) {
                $totalCropArea = $farmer->crops()->sum('area_planted') + $validated['area_planted'];
                if ($totalCropArea > $farmer->farm_size) {
                    return back()->withErrors(['area_planted' => 'Total crop area would exceed farmer\'s farm size.'])->withInput();
                }
            }

            // Enforce harvest at least 3 calendar months after planting (if provided)
            if (!empty($validated['expected_harvest_date'])) {
                $p = \Carbon\Carbon::parse($validated['planting_date'])->startOfDay();
                $h = \Carbon\Carbon::parse($validated['expected_harvest_date'])->startOfDay();
                if ($h->lessThanOrEqualTo($p)) {
                    return back()->withErrors(['expected_harvest_date' => 'Expected harvest date must be after the planting date.'])->withInput();
                }
                $minHarvest = $p->copy()->addMonthsNoOverflow(3);
                if ($h->lt($minHarvest)) {
                    return back()->withErrors(['expected_harvest_date' => 'Expected harvest date should be at least 3 months after the planting date.'])->withInput();
                }
            }

            // Handle image upload
            if ($request->hasFile('harvest_image')) {
                $validated['harvest_image'] = $request->file('harvest_image')->store('crops', 'public');
            }

            // Set initial status based on planting date
            $plantingDate = \Carbon\Carbon::parse($validated['planting_date'])->startOfDay();
            $today = now()->startOfDay();
            $validated['status'] = $plantingDate->gt($today) ? 'scheduled' : 'planted';

            $crop = Crop::create($validated);

            return redirect()->route('crops.show', $crop)
                ->with('success', 'Crop registered successfully!');

        } catch (\Illuminate\Database\QueryException $e) {
            $message = $e->getMessage();
            $field = null;
            if (str_contains($message, 'farmer_id')) $field = 'farmer_id';
            elseif (str_contains($message, 'crop_name')) $field = 'crop_name';
            elseif (str_contains($message, 'variety')) $field = 'variety';
            elseif (str_contains($message, 'area_planted')) $field = 'area_planted';
            elseif (str_contains($message, 'planting_date')) $field = 'planting_date';
            elseif (str_contains($message, 'expected_harvest_date')) $field = 'expected_harvest_date';
            elseif (str_contains($message, 'season')) $field = 'season';
            elseif (str_contains($message, 'expected_yield')) $field = 'expected_yield';
            elseif (str_contains($message, 'notes')) $field = 'notes';

            if ($field) {
                return back()->withErrors([$field => 'Database error on this field. Please review and try again.'])->withInput();
            }
            $driverMsg = $e->errorInfo[2] ?? Str::limit($message, 300);
            return back()->withErrors(['error' => 'Database error while saving crop: '.$driverMsg])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unexpected error: '.$e->getMessage()])->withInput();
        }
    }

    public function edit(Crop $crop): View
    {
        $farmers = Farmer::where('status', 'active')->get();
        return view('crops.edit', compact('crop', 'farmers'));
    }

    public function update(Request $request, Crop $crop): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'farmer_id' => 'required|exists:farmers,id',
                'crop_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'variety' => 'required|string|max:255',
                'custom_variety' => 'nullable|string|max:255|regex:/^[A-Z][a-zA-Z]+(?: [a-zA-Z]+)*$/',
                'area_planted' => 'required|numeric|min:0.01|max:1000',
                'planting_date' => 'required|date',
                'expected_harvest_date' => 'required|date|after:planting_date|before:2030-12-31',
                'actual_harvest_date' => 'nullable|date|after_or_equal:planting_date',
                'season' => 'required|in:wet,dry,year-round',
                'status' => 'required|in:scheduled,planted,harvested,failed',
                'expected_yield' => 'required|numeric|min:0|max:500000',
                'actual_yield' => 'nullable|numeric|min:0|max:500000',
                'harvest_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'remove_harvest_image' => 'nullable',
                'notes' => 'nullable|string|max:1000',
            ], [
                'farmer_id.required' => 'This field is required.',
                'farmer_id.exists' => 'Selected farmer does not exist.',
                'crop_name.required' => 'This field is required.',
                'crop_name.regex' => 'Crop name should only contain letters and spaces.',
                'variety.required' => 'This field is required.',
                'custom_variety.regex' => 'Rice variety must start with a capital letter, contain only letters and spaces, and no double spaces.',
                'area_planted.required' => 'This field is required.',
                'area_planted.min' => 'Area planted must be at least 0.01 hectares.',
                'area_planted.max' => 'Area planted cannot exceed 1,000 hectares.',
                'planting_date.required' => 'This field is required.',
                'expected_harvest_date.required' => 'This field is required.',
                'expected_harvest_date.after' => 'Expected harvest date must be after planting date.',
                'expected_harvest_date.before' => 'Expected harvest date must be before 2030.',
                'season.required' => 'This field is required.',
                'status.required' => 'This field is required.',
                'expected_yield.required' => 'This field is required.',
                'expected_yield.max' => 'Expected yield cannot exceed 500,000 kg.',
                'actual_yield.max' => 'Actual yield cannot exceed 500,000 kg.',
                'notes.max' => 'Notes cannot exceed 1,000 characters.',
            ]);

            // Handle custom variety
            if ($validated['variety'] === 'others') {
                if (empty($validated['custom_variety'])) {
                    return back()->withErrors(['custom_variety' => 'This field is required.'])->withInput();
                }
                $validated['variety'] = $validated['custom_variety'];
            }
            unset($validated['custom_variety']);

            // Validate status transitions
            if ($validated['status'] === 'harvested' && !$validated['actual_harvest_date']) {
                return back()->withErrors(['actual_harvest_date' => 'Actual harvest date is required when status is harvested.'])->withInput();
            }

            if ($validated['status'] === 'harvested' && !$validated['actual_yield']) {
                return back()->withErrors(['actual_yield' => 'Actual yield is required when status is harvested.'])->withInput();
            }

            // Check if farmer exists and is active
            $farmer = Farmer::find($validated['farmer_id']);
            if ($farmer->status !== 'active') {
                return back()->withErrors(['farmer_id' => 'Cannot assign crops to inactive farmers.'])->withInput();
            }

            // Check farm size constraint (excluding current crop area)
            if ($farmer->farm_size) {
                $otherCropsArea = $farmer->crops()->where('id', '!=', $crop->id)->sum('area_planted');
                $totalCropArea = $otherCropsArea + $validated['area_planted'];
                if ($totalCropArea > $farmer->farm_size) {
                    return back()->withErrors(['area_planted' => 'Total crop area would exceed farmer\'s farm size.'])->withInput();
                }
            }

            // Enforce harvest at least 3 calendar months after planting (if provided)
            if (!empty($validated['expected_harvest_date'])) {
                $p = \Carbon\Carbon::parse($validated['planting_date']);
                $h = \Carbon\Carbon::parse($validated['expected_harvest_date']);
                if ($h->lessThanOrEqualTo($p)) {
                    return back()->withErrors(['expected_harvest_date' => 'Expected harvest date must be after the planting date.'])->withInput();
                }
                $minHarvest = $p->copy()->addMonthsNoOverflow(3);
                if ($h->lt($minHarvest)) {
                    return back()->withErrors(['expected_harvest_date' => 'Expected harvest date should be at least 3 months after the planting date.'])->withInput();
                }
            }

            // Actual harvest date: must be at least 3 calendar months after planting (and cannot be in the future per rule)
            if (!empty($validated['actual_harvest_date'])) {
                $p = \Carbon\Carbon::parse($validated['planting_date']);
                $a = \Carbon\Carbon::parse($validated['actual_harvest_date']);
                if ($a->lessThanOrEqualTo($p)) {
                    return back()->withErrors(['actual_harvest_date' => 'Actual harvest date must be after the planting date.'])->withInput();
                }
                $minActual = $p->copy()->addMonthsNoOverflow(3);
                if ($a->lt($minActual)) {
                    return back()->withErrors(['actual_harvest_date' => 'Actual harvest date should be at least 3 months after the planting date.'])->withInput();
                }
            }

            // Handle image upload
            if ($request->hasFile('harvest_image')) {
                // Delete old image if exists
                if ($crop->harvest_image) {
                    \Storage::disk('public')->delete($crop->harvest_image);
                }
                $validated['harvest_image'] = $request->file('harvest_image')->store('crops', 'public');
            } elseif ($request->has('remove_harvest_image') && $request->input('remove_harvest_image')) {
                // Remove image if checkbox is checked
                if ($crop->harvest_image) {
                    \Storage::disk('public')->delete($crop->harvest_image);
                }
                $validated['harvest_image'] = null;
            }

            // Automatically calculate status
            // If both actual harvest date AND actual yield are filled, status is 'harvested'
            if (!empty($validated['actual_harvest_date']) && !empty($validated['actual_yield'])) {
                $validated['status'] = 'harvested';
            }
            // If expected harvest date has passed and no actual harvest/yield, status is 'failed'
            elseif (!empty($validated['expected_harvest_date']) && 
                    \Carbon\Carbon::parse($validated['expected_harvest_date'])->isPast() &&
                    empty($validated['actual_harvest_date']) && 
                    empty($validated['actual_yield'])) {
                $validated['status'] = 'failed';
            }
            // If planting date is in the future, status is 'scheduled'
            elseif (!empty($validated['planting_date']) && 
                    \Carbon\Carbon::parse($validated['planting_date'])->startOfDay()->gt(now()->startOfDay())) {
                $validated['status'] = 'scheduled';
            }
            // Default to 'planted'
            else {
                $validated['status'] = 'planted';
            }

            $crop->update($validated);

            return redirect()->route('crops.show', $crop)
                ->with('success', 'Crop updated successfully!');

        } catch (\Illuminate\Database\QueryException $e) {
            $message = $e->getMessage();
            $field = null;
            if (str_contains($message, 'farmer_id')) $field = 'farmer_id';
            elseif (str_contains($message, 'crop_name')) $field = 'crop_name';
            elseif (str_contains($message, 'variety')) $field = 'variety';
            elseif (str_contains($message, 'area_planted')) $field = 'area_planted';
            elseif (str_contains($message, 'planting_date')) $field = 'planting_date';
            elseif (str_contains($message, 'expected_harvest_date')) $field = 'expected_harvest_date';
            elseif (str_contains($message, 'actual_harvest_date')) $field = 'actual_harvest_date';
            elseif (str_contains($message, 'season')) $field = 'season';
            elseif (str_contains($message, 'expected_yield')) $field = 'expected_yield';
            elseif (str_contains($message, 'actual_yield')) $field = 'actual_yield';
            elseif (str_contains($message, 'notes')) $field = 'notes';

            if ($field) {
                return back()->withErrors([$field => 'Database error on this field. Please review and try again.'])->withInput();
            }
            $driverMsg = $e->errorInfo[2] ?? Str::limit($message, 300);
            return back()->withErrors(['error' => 'Database error while updating crop: '.$driverMsg])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unexpected error: '.$e->getMessage()])->withInput();
        }
    }

    public function destroy(Crop $crop): RedirectResponse
    {
        try {
            $cropName = $crop->crop_name;
            $crop->delete();

            return redirect()->route('crops.index')
                ->with('success', "Crop '{$cropName}' deleted successfully!");

        } catch (\Exception $e) {
            return redirect()->route('crops.index')
                ->with('error', 'An error occurred while deleting the crop. Please try again.');
        }
    }
}