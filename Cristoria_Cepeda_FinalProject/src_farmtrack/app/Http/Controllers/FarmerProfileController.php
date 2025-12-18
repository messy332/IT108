<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmerProfileController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('farmer.profile.create');
        }

        // Check if essential profile information is complete
        if (!$farmer->name || !$farmer->phone || !$farmer->address || !$farmer->birthdate || !$farmer->gender) {
            return redirect()->route('farmer.profile.edit')->with('error', 'Please complete your profile information.');
        }

        // Handle search
        $searchQuery = $request->input('q');
        $searchResults = null;
        
        if ($searchQuery) {
            // Search crops
            $cropResults = $farmer->crops()
                ->where(function($q) use ($searchQuery) {
                    $q->where('crop_name', 'like', "%{$searchQuery}%")
                      ->orWhere('variety', 'like', "%{$searchQuery}%")
                      ->orWhere('status', 'like', "%{$searchQuery}%");
                })
                ->with('latestProgressLog')
                ->get();

            // Search activities/progress logs
            $activityResults = \App\Models\ProgressLog::whereHas('crop', function($q) use ($farmer) {
                $q->where('farmer_id', $farmer->id);
            })
            ->where(function($q) use ($searchQuery) {
                $q->where('activity_type', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%")
                  ->orWhere('observations', 'like', "%{$searchQuery}%")
                  ->orWhereHas('crop', function($cropQ) use ($searchQuery) {
                      $cropQ->where('crop_name', 'like', "%{$searchQuery}%");
                  });
            })
            ->with('crop')
            ->orderBy('log_date', 'desc')
            ->get();

            $searchResults = [
                'crops' => $cropResults,
                'activities' => $activityResults,
                'query' => $searchQuery,
            ];
        }

        // Get statistics
        $stats = [
            'total_crops' => $farmer->crops()->count(),
            'active_crops' => $farmer->crops()->whereIn('status', ['planted', 'growing'])->count(),
            'total_area' => $farmer->crops()->sum('area_planted'),
            'avg_growth_stage' => $farmer->average_growth_stage,
        ];

        // Get recent activities (progress logs)
        $recentActivities = \App\Models\ProgressLog::whereHas('crop', function($query) use ($farmer) {
            $query->where('farmer_id', $farmer->id);
        })->with('crop')->orderBy('log_date', 'desc')->take(10)->get();

        // Get crops
        $crops = $farmer->crops()->with('latestProgressLog')->latest()->take(6)->get();

        // Farm status data for chart (last 6 months)
        $farmStatusData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('M Y');
            
            $avgGrowth = \App\Models\ProgressLog::whereHas('crop', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->whereYear('log_date', $month->year)
            ->whereMonth('log_date', $month->month)
            ->avg('growth_stage');
            
            $farmStatusData[$monthKey] = round($avgGrowth ?? 0, 1);
        }

        // Activity by growth rate
        $activityTypes = \App\Models\ProgressLog::getActivityTypes();
        $activityByGrowth = [];
        
        foreach ($activityTypes as $key => $label) {
            $avgGrowth = \App\Models\ProgressLog::whereHas('crop', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->where('activity_type', $key)
            ->avg('growth_stage');
            
            if ($avgGrowth) {
                $activityByGrowth[$label] = round($avgGrowth, 1);
            }
        }

        // Farm area utilization for pie chart
        $totalFarmSize = $farmer->farm_size ?? 0;
        $totalPlantedArea = $farmer->crops()->sum('area_planted');
        $availableArea = max(0, $totalFarmSize - $totalPlantedArea);
        
        $areaUtilization = [
            'planted' => round($totalPlantedArea, 2),
            'available' => round($availableArea, 2),
        ];

        // Get all crops for map visualization (unpaginated)
        $mapCrops = $farmer->crops()->with('latestProgressLog')->get();

        return view('farmer.dashboard', compact(
            'farmer',
            'stats',
            'recentActivities',
            'crops',
            'mapCrops',
            'farmStatusData',
            'activityByGrowth',
            'areaUtilization',
            'searchResults'
        ));
    }

    public function index()
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('farmer.profile.create');
        }

        // Wrap in a paginator-like structure or collection to mimic the admin view if needed, 
        // but since we are creating a specific view for this, we can just pass the farmer.
        // However, to make it look like the admin list with one item, we can pass a collection.
        $farmers = collect([$farmer]);
        
        // Mock pagination for the view if it uses links()
        $farmers = new \Illuminate\Pagination\LengthAwarePaginator(
            $farmers, 
            1, 
            10, 
            1, 
            ['path' => route('farmer.profile.index')]
        );

        return view('farmer.profile.index', compact('farmers'));
    }

    public function show()
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('farmer.profile.create');
        }

        // Get all crops with their latest progress log
        $crops = $farmer->crops()->with(['progressLogs' => function($query) {
            $query->orderBy('log_date', 'desc');
        }, 'latestProgressLog'])->latest()->get();

        // Recent activities (last 10 progress logs)
        $recentActivities = \App\Models\ProgressLog::whereHas('crop', function($query) use ($farmer) {
            $query->where('farmer_id', $farmer->id);
        })->with('crop')->orderBy('log_date', 'desc')->limit(10)->get();

        // Farm status data - growth stages over time (last 6 months)
        $farmStatusData = \App\Models\ProgressLog::whereHas('crop', function($query) use ($farmer) {
            $query->where('farmer_id', $farmer->id);
        })
        ->where('log_date', '>=', now()->subMonths(6))
        ->orderBy('log_date')
        ->get()
        ->groupBy(function($log) {
            return $log->log_date->format('Y-m');
        })
        ->map(function($logs) {
            return round($logs->avg('growth_stage'), 1);
        });

        // Activity types by growth rate (1-10)
        $activityByGrowth = \App\Models\ProgressLog::whereHas('crop', function($query) use ($farmer) {
            $query->where('farmer_id', $farmer->id);
        })
        ->selectRaw('activity_type, ROUND(AVG(growth_stage), 1) as avg_growth')
        ->groupBy('activity_type')
        ->get()
        ->mapWithKeys(function($item) {
            $activityTypes = \App\Models\ProgressLog::getActivityTypes();
            return [$activityTypes[$item->activity_type] ?? $item->activity_type => $item->avg_growth];
        });

        // Farm statistics
        $stats = [
            'total_crops' => $crops->count(),
            'active_crops' => $crops->whereIn('status', ['planted', 'growing'])->count(),
            'total_area' => $crops->sum('area_planted'),
            'total_yield' => $crops->sum('actual_yield'),
            'avg_growth_stage' => $farmer->average_growth_stage,
        ];
        
        return view('farmer.profile.show', compact('farmer', 'crops', 'recentActivities', 'farmStatusData', 'activityByGrowth', 'stats'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->farmer) {
            return redirect()->route('farmer.profile.show');
        }

        return view('farmer.profile.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required','string','max:255',
                'regex:/^[A-Z][a-zA-Z]+(?: [A-Z][a-zA-Z]+)*$/'
            ],
            'phone' => 'required|string|regex:/^\d{10,15}$/',
            'address' => 'required|string|max:500',
            'birthdate' => 'required|date|before:today|after:1900-01-01',
            'gender' => 'required|in:male,female',
            'farm_size' => 'required|numeric|min:0.01|max:10000|regex:/^\d{1,6}(\.\d{1,2})?$/',
            'farm_type' => 'nullable|in:crop,livestock,mixed',
            'supporting_document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ], [
            'name.required' => 'Name is required.',
            'name.regex' => 'Name must have capitalized words, letters only, separated by single spaces.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone must be digits only (10-15 digits).',
            'address.required' => 'Address is required.',
            'birthdate.required' => 'Birth date is required.',
            'birthdate.before' => 'Birth date must be in the past.',
            'birthdate.after' => 'Birth date must be after 1900.',
            'gender.required' => 'Gender is required.',
            'farm_size.required' => 'Farm size is required.',
            'farm_size.min' => 'Farm size must be at least 0.01 hectares.',
            'farm_size.max' => 'Farm size cannot exceed 10,000 hectares.',
            'farm_size.regex' => 'Farm size must be a number with up to 2 decimal places.',
            'supporting_document.required' => 'Supporting document is required.',
            'supporting_document.file' => 'Supporting document must be a file.',
            'supporting_document.mimes' => 'Supporting document must be a PDF, JPG, PNG, DOC, or DOCX file.',
            'supporting_document.max' => 'Supporting document must not exceed 5MB.',
        ]);

        $calculatedAge = \Carbon\Carbon::parse($validated['birthdate'])->age;
        if ($calculatedAge < 18 || $calculatedAge > 75) {
             return back()->withErrors(['birthdate' => 'Age must be between 18 and 75 years old.'])->withInput();
        }

        // Handle file upload
        $supportingDocPath = null;
        if ($request->hasFile('supporting_document')) {
            $file = $request->file('supporting_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $supportingDocPath = $file->storeAs('supporting_documents', $filename, 'public');
        }

        $farmer = Farmer::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => Auth::user()->email,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'birthdate' => $validated['birthdate'],
            'age' => $calculatedAge,
            'gender' => $validated['gender'],
            'farm_size' => $validated['farm_size'],
            'farm_type' => $validated['farm_type'] ?? 'crop',
            'supporting_document' => $supportingDocPath,
            'status' => 'active',
        ]);

        return redirect()->route('farmer.profile.show')->with('success', 'Profile created successfully!');
    }

    public function edit()
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('farmer.profile.create');
        }

        return view('farmer.profile.edit', compact('farmer'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('farmer.profile.create');
        }

        $validated = $request->validate([
            'name' => [
                'required','string','max:255',
                'regex:/^[A-Z][a-zA-Z]+(?: [A-Z][a-zA-Z]+)*$/'
            ],
            'phone' => 'nullable|string|regex:/^\d{10,15}$/',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'farm_boundaries' => 'nullable|json',
            'birthdate' => 'required|date|before:today|after:1900-01-01',
            'gender' => 'required|in:male,female',
            'farm_size' => 'nullable|numeric|min:0.01|max:10000|regex:/^\d{1,6}(\.\d{1,2})?$/',
            'farm_type' => 'nullable|in:crop,livestock,mixed',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ], [
            'name.required' => 'Name is required.',
            'name.regex' => 'Name must have capitalized words, letters only, separated by single spaces.',
            'birthdate.required' => 'Birth date is required.',
            'birthdate.before' => 'Birth date must be in the past.',
            'birthdate.after' => 'Birth date must be after 1900.',
            'phone.regex' => 'Phone must be digits only (10-15 digits).',
            'farm_size.min' => 'Farm size must be at least 0.01 hectares.',
            'farm_size.max' => 'Farm size cannot exceed 10,000 hectares.',
            'farm_size.regex' => 'Farm size must be a number with up to 2 decimal places.',
            'supporting_document.file' => 'Supporting document must be a file.',
            'supporting_document.mimes' => 'Supporting document must be a PDF, JPG, PNG, DOC, or DOCX file.',
            'supporting_document.max' => 'Supporting document must not exceed 5MB.',
        ]);

        $calculatedAge = \Carbon\Carbon::parse($validated['birthdate'])->age;
        if ($calculatedAge < 18 || $calculatedAge > 75) {
             return back()->withErrors(['birthdate' => 'Age must be between 18 and 75 years old.'])->withInput();
        }

        try {
            // Handle file upload
            if ($request->hasFile('supporting_document')) {
                $file = $request->file('supporting_document');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('supporting_documents', $filename, 'public');
                $validated['supporting_document'] = $path;
            } else {
                // Don't update supporting_document if no new file is uploaded
                unset($validated['supporting_document']);
            }

            // Handle farm boundaries JSON
            $farmBoundaries = null;
            if ($request->has('farm_boundaries') && !empty($request->input('farm_boundaries'))) {
                $farmBoundaries = json_decode($request->input('farm_boundaries'), true);
            }

            $farmer->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'latitude' => $validated['latitude'] ?? $farmer->latitude,
                'longitude' => $validated['longitude'] ?? $farmer->longitude,
                'farm_boundaries' => $farmBoundaries ?? $farmer->farm_boundaries,
                'birthdate' => $validated['birthdate'],
                'age' => $calculatedAge,
                'gender' => $validated['gender'],
                'farm_size' => $validated['farm_size'] ?? null,
                'farm_type' => $validated['farm_type'] ?? null,
                'supporting_document' => $validated['supporting_document'] ?? $farmer->supporting_document,
            ]);

            return redirect()->route('farmer.dashboard')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage())->withInput();
        }
    }

    public function myCrops(Request $request)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('farmer.profile.create');
        }

        $query = $farmer->crops()->with('progressLogs');

        // Search by crop name or variety
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('crop_name', 'like', "%{$search}%")
                  ->orWhere('variety', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by season
        if ($request->filled('season')) {
            $query->where('season', $request->input('season'));
        }

        $crops = $query->latest()->paginate(10)->withQueryString();
        
        return view('farmer.crops.index', compact('crops', 'farmer'));
    }

    public function destroy()
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if ($farmer) {
            // Delete all related data (crops and progress logs will be deleted via cascade)
            $farmer->delete();
        }

        // Delete the user account
        $user->delete();

        // Logout the user
        Auth::logout();

        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }

    // Crop Management Methods
    public function createCrop()
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('farmer.profile.create');
        }

        return view('farmer.crops.create', compact('farmer'));
    }

    public function storeCrop(Request $request)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('farmer.profile.create');
        }

        $validated = $request->validate([
            'crop_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'variety' => 'required|string|max:255',
            'custom_variety' => 'nullable|string|max:255|regex:/^[A-Z][a-zA-Z]+(?: [a-zA-Z]+)*$/',
            'area_planted' => 'required|numeric|min:0.01|max:1000',
            'planting_date' => 'required|date|after_or_equal:today',
            'expected_harvest_date' => 'nullable|date|after:planting_date|before:2030-12-31',
            'season' => 'required|in:wet,dry,year-round',
            'expected_yield' => 'nullable|numeric|min:0|max:500000',
            'notes' => 'nullable|string|max:1000',
        ], [
            'crop_name.required' => 'Crop name is required.',
            'crop_name.regex' => 'Crop name should only contain letters and spaces.',
            'variety.required' => 'Rice variety is required.',
            'custom_variety.regex' => 'Rice variety must start with a capital letter, contain only letters and spaces, and no double spaces.',
            'area_planted.required' => 'Area planted is required.',
            'area_planted.min' => 'Area planted must be at least 0.01 hectares.',
            'area_planted.max' => 'Area planted cannot exceed 1,000 hectares.',
            'planting_date.required' => 'Planting date is required.',
            'planting_date.after_or_equal' => 'Planting date cannot be in the past.',
            'expected_harvest_date.after' => 'Expected harvest date must be after planting date.',
            'expected_harvest_date.before' => 'Expected harvest date must be before 2030.',
            'expected_yield.max' => 'Expected yield cannot exceed 500,000 kg.',
            'notes.max' => 'Notes cannot exceed 1,000 characters.',
        ]);

        // Handle custom variety
        if ($validated['variety'] === 'others') {
            if (empty($validated['custom_variety'])) {
                return back()->withErrors(['custom_variety' => 'Please specify the rice variety.'])->withInput();
            }
            $validated['variety'] = $validated['custom_variety'];
        }
        unset($validated['custom_variety']);

        // Check farm size constraint
        if ($farmer->farm_size) {
            $totalCropArea = $farmer->crops()->sum('area_planted') + $validated['area_planted'];
            if ($totalCropArea > $farmer->farm_size) {
                return back()->withErrors(['area_planted' => 'Total crop area would exceed your farm size.'])->withInput();
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

        $validated['farmer_id'] = $farmer->id;
        
        // Set initial status based on planting date (same as admin)
        $plantingDate = \Carbon\Carbon::parse($validated['planting_date'])->startOfDay();
        $today = now()->startOfDay();
        $validated['status'] = $plantingDate->gt($today) ? 'scheduled' : 'planted';

        \App\Models\Crop::create($validated);

        return redirect()->route('farmer.crops.index')->with('success', 'Crop added successfully!');
    }

    public function showCrop(\App\Models\Crop $crop)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer || $crop->farmer_id !== $farmer->id) {
            abort(403, 'Unauthorized access');
        }

        $crop->load(['progressLogs' => function($query) {
            $query->orderBy('log_date', 'desc');
        }]);

        return view('farmer.crops.show', compact('crop', 'farmer'));
    }

    public function editCrop(\App\Models\Crop $crop)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer || $crop->farmer_id !== $farmer->id) {
            abort(403, 'Unauthorized access');
        }

        return view('farmer.crops.edit', compact('crop', 'farmer'));
    }

    public function updateCrop(Request $request, \App\Models\Crop $crop)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer || $crop->farmer_id !== $farmer->id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'crop_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'variety' => 'nullable|string|max:255',
            'area_planted' => 'required|numeric|min:0.01|max:1000',
            'planting_date' => 'required|date',
            'expected_harvest_date' => 'nullable|date|after:planting_date|before:2030-12-31',
            'actual_harvest_date' => 'nullable|date|after_or_equal:planting_date',
            'season' => 'required|in:wet,dry,year-round',
            'status' => 'required|in:scheduled,planted,growing,harvested,failed',
            'expected_yield' => 'nullable|numeric|min:0|max:500000',
            'actual_yield' => 'nullable|numeric|min:0|max:500000',
            'harvest_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'notes' => 'nullable|string|max:1000',
        ], [
            'crop_name.required' => 'Crop name is required.',
            'crop_name.regex' => 'Crop name should only contain letters and spaces.',
            'area_planted.required' => 'Area planted is required.',
            'area_planted.min' => 'Area planted must be at least 0.01 hectares.',
            'area_planted.max' => 'Area planted cannot exceed 1,000 hectares.',
            'planting_date.required' => 'Planting date is required.',
            'expected_harvest_date.after' => 'Expected harvest date must be after planting date.',
            'expected_yield.max' => 'Expected yield cannot exceed 500,000 kg.',
            'actual_yield.max' => 'Actual yield cannot exceed 500,000 kg.',
            'harvest_image.image' => 'The file must be an image.',
            'harvest_image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'harvest_image.max' => 'The image must not be larger than 5MB.',
            'notes.max' => 'Notes cannot exceed 1,000 characters.',
        ]);

        // Check farm size constraint (excluding current crop area)
        if ($farmer->farm_size) {
            $otherCropsArea = $farmer->crops()->where('id', '!=', $crop->id)->sum('area_planted');
            $totalCropArea = $otherCropsArea + $validated['area_planted'];
            if ($totalCropArea > $farmer->farm_size) {
                return back()->withErrors(['area_planted' => 'Total crop area would exceed your farm size.'])->withInput();
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

        // Actual harvest date: must be at least 3 calendar months after planting
        if (!empty($validated['actual_harvest_date'])) {
            $p = \Carbon\Carbon::parse($validated['planting_date'])->startOfDay();
            $a = \Carbon\Carbon::parse($validated['actual_harvest_date'])->startOfDay();
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

        // Automatically calculate status based on dates and yields (same as admin)
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

        return redirect()->route('farmer.crops.index')->with('success', 'Crop updated successfully!');
    }

    public function destroyCrop(\App\Models\Crop $crop)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer || $crop->farmer_id !== $farmer->id) {
            abort(403, 'Unauthorized access');
        }

        $crop->delete();

        return redirect()->route('farmer.crops.index')->with('success', 'Crop deleted successfully!');
    }

    // Progress Log Management Methods
    public function myProgressLogs(Request $request)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('farmer.profile.create');
        }

        $query = \App\Models\ProgressLog::whereHas('crop', function($q) use ($farmer) {
            $q->where('farmer_id', $farmer->id);
        })->with('crop');

        // Search by crop name, variety, or description
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('observations', 'like', "%{$search}%")
                  ->orWhereHas('crop', function($cropQuery) use ($search) {
                      $cropQuery->where('crop_name', 'like', "%{$search}%")
                                ->orWhere('variety', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by activity type
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->input('activity_type'));
        }

        // Filter by growth stage
        if ($request->filled('growth_stage')) {
            $query->where('growth_stage', $request->input('growth_stage'));
        }

        $progressLogs = $query->latest('log_date')->paginate(15)->withQueryString();

        return view('farmer.progress-logs.index', compact('progressLogs', 'farmer'));
    }

    public function createProgressLog()
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('farmer.profile.create');
        }

        // Get all crops (not just planted/growing) so farmers can log activities for any crop
        $crops = $farmer->crops()->get();

        if ($crops->isEmpty()) {
            return redirect()->route('farmer.crops.create')->with('error', 'Please add a crop first before creating progress logs.');
        }

        return view('farmer.progress-logs.create', compact('farmer', 'crops'));
    }

    public function showProgressLog(\App\Models\ProgressLog $progressLog)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer || $progressLog->crop->farmer_id !== $farmer->id) {
            abort(403, 'Unauthorized access');
        }

        $progressLog->load('crop');

        return view('farmer.progress-logs.show', compact('progressLog', 'farmer'));
    }

    public function storeProgressLog(Request $request)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('farmer.profile.create');
        }

        $validated = $request->validate([
            'crop_id' => 'required|exists:crops,id',
            'log_date' => 'required|date|after:2020-01-01',
            'activity_type' => 'required|string|in:' . implode(',', array_keys(\App\Models\ProgressLog::getActivityTypes())),
            'description' => 'nullable|string|max:1000',
            'cost' => 'required|numeric|min:0|max:100000',
            'weather_condition' => 'required|string|in:' . implode(',', array_keys(\App\Models\ProgressLog::getWeatherConditions())),
            'growth_stage' => 'nullable|integer|min:1|max:10',
            'observations' => 'nullable|string|max:1000',
        ], [
            'crop_id.required' => 'Please select a crop.',
            'log_date.required' => 'Activity date is required.',
            'log_date.after' => 'Activity date must be after 2020.',
            'activity_type.required' => 'Activity type is required.',
            'description.max' => 'Description cannot exceed 1,000 characters.',
            'cost.required' => 'Cost is required.',
            'cost.min' => 'Cost cannot be negative.',
            'cost.max' => 'Cost cannot exceed 100,000.',
            'weather_condition.required' => 'Weather condition is required.',
            'growth_stage.min' => 'Growth stage must be between 1 and 10.',
            'growth_stage.max' => 'Growth stage must be between 1 and 10.',
            'observations.max' => 'Observations cannot exceed 1,000 characters.',
        ]);

        // Verify the crop belongs to this farmer
        $crop = \App\Models\Crop::findOrFail($validated['crop_id']);
        if ($crop->farmer_id !== $farmer->id) {
            abort(403, 'Unauthorized access');
        }

        // Validate log date against crop dates
        if ($validated['log_date'] < $crop->planting_date->format('Y-m-d')) {
            return back()->withErrors(['log_date' => 'Activity date cannot be before the crop planting date.'])->withInput();
        }

        if ($crop->actual_harvest_date && $validated['log_date'] > $crop->actual_harvest_date->format('Y-m-d')) {
            return back()->withErrors(['log_date' => 'Activity date cannot be after the crop harvest date.'])->withInput();
        }

        // Check for duplicate logs (same activity type and growth stage)
        if (!empty($validated['growth_stage'])) {
            $existingLog = \App\Models\ProgressLog::where('crop_id', $validated['crop_id'])
                ->where('activity_type', $validated['activity_type'])
                ->where('growth_stage', $validated['growth_stage'])
                ->first();

            if ($existingLog) {
                return back()->withErrors([
                    'activity_type' => 'This activity type with growth stage ' . $validated['growth_stage'] . ' has already been logged for this crop.'
                ])->withInput();
            }
        }

        // Check for duplicate logs on same date
        $existingLog = \App\Models\ProgressLog::where('crop_id', $validated['crop_id'])
            ->where('log_date', $validated['log_date'])
            ->where('activity_type', $validated['activity_type'])
            ->first();

        if ($existingLog) {
            return back()->withErrors(['log_date' => 'A log for this activity already exists on this date for this crop.'])->withInput();
        }

        \App\Models\ProgressLog::create($validated);

        return redirect()->route('farmer.progress-logs.index')->with('success', 'Progress log added successfully!');
    }

    public function editProgressLog(\App\Models\ProgressLog $progressLog)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer || $progressLog->crop->farmer_id !== $farmer->id) {
            abort(403, 'Unauthorized access');
        }

        $crops = $farmer->crops()->whereIn('status', ['planted', 'growing'])->get();

        return view('farmer.progress-logs.edit', compact('progressLog', 'farmer', 'crops'));
    }

    public function updateProgressLog(Request $request, \App\Models\ProgressLog $progressLog)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer || $progressLog->crop->farmer_id !== $farmer->id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'crop_id' => 'required|exists:crops,id',
            'log_date' => 'required|date|after:2020-01-01',
            'activity_type' => 'required|string|in:' . implode(',', array_keys(\App\Models\ProgressLog::getActivityTypes())),
            'description' => 'nullable|string|max:1000',
            'cost' => 'required|numeric|min:0|max:100000',
            'weather_condition' => 'required|string|in:' . implode(',', array_keys(\App\Models\ProgressLog::getWeatherConditions())),
            'growth_stage' => 'nullable|integer|min:1|max:10',
            'observations' => 'nullable|string|max:1000',
        ], [
            'crop_id.required' => 'Please select a crop.',
            'log_date.required' => 'Activity date is required.',
            'log_date.after' => 'Activity date must be after 2020.',
            'activity_type.required' => 'Activity type is required.',
            'description.max' => 'Description cannot exceed 1,000 characters.',
            'cost.required' => 'Cost is required.',
            'cost.min' => 'Cost cannot be negative.',
            'cost.max' => 'Cost cannot exceed 100,000.',
            'weather_condition.required' => 'Weather condition is required.',
            'growth_stage.min' => 'Growth stage must be between 1 and 10.',
            'growth_stage.max' => 'Growth stage must be between 1 and 10.',
            'observations.max' => 'Observations cannot exceed 1,000 characters.',
        ]);

        // Verify the crop belongs to this farmer
        $crop = \App\Models\Crop::findOrFail($validated['crop_id']);
        if ($crop->farmer_id !== $farmer->id) {
            abort(403, 'Unauthorized access');
        }

        // Validate log date against crop dates
        if ($validated['log_date'] < $crop->planting_date->format('Y-m-d')) {
            return back()->withErrors(['log_date' => 'Activity date cannot be before the crop planting date.'])->withInput();
        }

        if ($crop->actual_harvest_date && $validated['log_date'] > $crop->actual_harvest_date->format('Y-m-d')) {
            return back()->withErrors(['log_date' => 'Activity date cannot be after the crop harvest date.'])->withInput();
        }

        // Check for duplicate logs (excluding current)
        $existingLog = \App\Models\ProgressLog::where('crop_id', $validated['crop_id'])
            ->where('log_date', $validated['log_date'])
            ->where('activity_type', $validated['activity_type'])
            ->where('id', '!=', $progressLog->id)
            ->first();

        if ($existingLog) {
            return back()->withErrors(['log_date' => 'A log for this activity already exists on this date for this crop.'])->withInput();
        }

        $progressLog->update($validated);

        return redirect()->route('farmer.progress-logs.index')->with('success', 'Progress log updated successfully!');
    }

    public function destroyProgressLog(\App\Models\ProgressLog $progressLog)
    {
        $user = Auth::user();
        $farmer = $user->farmer;

        if (!$farmer || $progressLog->crop->farmer_id !== $farmer->id) {
            abort(403, 'Unauthorized access');
        }

        $progressLog->delete();

        return redirect()->route('farmer.progress-logs.index')->with('success', 'Progress log deleted successfully!');
    }
}
