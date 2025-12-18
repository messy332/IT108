<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FarmerController extends Controller
{
    public function index(): View
    {
        $q = request('q');
        $gender = request('gender');
        $ageRange = request('age_range');
        $farmSizeRange = request('farm_size_range');

        $farmers = Farmer::with(['crops', 'activeCrops'])
            ->withCount(['crops', 'activeCrops'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%")
                        ->orWhere('phone', 'like', "%$q%")
                        ->orWhere('address', 'like', "%$q%")
                        ->orWhere('farm_type', 'like', "%$q%");
                });
            })
            ->when($gender, function ($query) use ($gender) {
                $query->where('gender', $gender);
            })
            ->when($ageRange, function ($query) use ($ageRange) {
                switch ($ageRange) {
                    case '18-30':
                        $query->whereBetween('age', [18, 30]);
                        break;
                    case '31-45':
                        $query->whereBetween('age', [31, 45]);
                        break;
                    case '46-60':
                        $query->whereBetween('age', [46, 60]);
                        break;
                    case '60+':
                        $query->where('age', '>=', 60);
                        break;
                }
            })
            ->when($farmSizeRange, function ($query) use ($farmSizeRange) {
                switch ($farmSizeRange) {
                    case '0-1':
                        $query->whereBetween('farm_size', [0, 1]);
                        break;
                    case '1-3':
                        $query->whereBetween('farm_size', [1, 3]);
                        break;
                    case '3-5':
                        $query->whereBetween('farm_size', [3, 5]);
                        break;
                    case '5+':
                        $query->where('farm_size', '>=', 5);
                        break;
                }
            })
            ->orderBy('name')
            ->paginate(10)
            ->appends([
                'q' => $q,
                'gender' => $gender,
                'age_range' => $ageRange,
                'farm_size_range' => $farmSizeRange
            ]);

        return view('farmers.index', compact('farmers'));
    }

    public function show(Farmer $farmer): View
    {
        $farmer->load(['crops.progressLogs', 'crops.latestProgressLog']);
        
        return view('farmers.show', compact('farmer'));
    }

    public function create(): View
    {
        return view('farmers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Run validation outside try/catch so field errors are shown properly
        $validated = $request->validate([
                // Name: capitalized words separated by single spaces, letters only
                'name' => [
                    'required','string','max:255',
                    'regex:/^[A-Z][a-zA-Z]+(?: [A-Z][a-zA-Z]+)*$/'
                ],
                'email' => 'required|email|unique:farmers,email|unique:users,email|max:255',
                // Phone: digits only, 10-15 length
                'phone' => 'required|string|regex:/^\d{10,15}$/',
                // Address: allow single spaces only, no double spaces
                'address' => 'required|string|max:500|regex:/^[^\s]+(?: [^\s]+)*$/',
                'birthdate' => 'required|date|before:today|after:1900-01-01',
                'age' => 'required|integer|min:18|max:75',
                'gender' => 'required|in:male,female',
                // Farm size: numeric, 0.01-10000, up to 2 decimals
                'farm_size' => 'required|numeric|min:0.01|max:10000|regex:/^\d{1,6}(\.\d{1,2})?$/',
                'farm_type' => 'required|in:crop,livestock,mixed',
                'supporting_document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            ], [
                'name.required' => 'This field is required.',
                'name.regex' => 'Name must have capitalized words, letters only, separated by single spaces (e.g., "Juan Dela Cruz").',
                'email.required' => 'This field is required.',
                'email.email' => 'This field is required.',
                'email.unique' => 'This email is already registered.',
                'phone.required' => 'This field is required.',
                'phone.regex' => 'Phone must be digits only (10-15 digits).',
                'address.required' => 'This field is required.',
                'address.regex' => 'Address cannot contain double spaces.',
                'birthdate.required' => 'This field is required.',
                'birthdate.date' => 'This field is required.',
                'birthdate.before' => 'Invalid birth date.',
                'birthdate.after' => 'Invalid birth date.',
                'age.required' => 'This field is required.',
                'age.integer' => 'This field is required.',
                'age.min' => 'Must be at least 18 years old.',
                'age.max' => 'Age cannot exceed 75 years old.',
                'gender.required' => 'This field is required.',
                'gender.in' => 'This field is required.',
                'farm_size.required' => 'This field is required.',
                'farm_size.numeric' => 'This field is required.',
                'farm_size.min' => 'Farm size must be at least 0.01 hectares.',
                'farm_size.max' => 'Farm size cannot exceed 10,000 hectares.',
                'farm_size.regex' => 'Farm size must be a number with up to 2 decimal places.',
                'farm_type.required' => 'This field is required.',
                'farm_type.in' => 'This field is required.',
                'supporting_document.required' => 'This field is required.',
                'supporting_document.file' => 'This field is required.',
                'supporting_document.mimes' => 'Supporting document must be a PDF, JPG, PNG, DOC, or DOCX file.',
                'supporting_document.max' => 'Supporting document must not exceed 5MB.',
            ]);

        try {
            // Handle file upload
            if ($request->hasFile('supporting_document')) {
                $file = $request->file('supporting_document');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('supporting_documents', $filename, 'public');
                $validated['supporting_document'] = $path;
            }

            // Validate age matches birthdate using Carbon age calculation
            $calculatedAge = \Carbon\Carbon::parse($validated['birthdate'])->age;
            if ((int)$validated['age'] !== (int)$calculatedAge) {
                return back()->withErrors(['age' => 'Age does not match the birth date.'])->withInput();
            }
            // Align saved age to calculated age
            $validated['age'] = $calculatedAge;

            // Ensure default status
            $validated['status'] = 'active';

            // Use transaction to create both user and farmer
            DB::transaction(function () use ($validated) {
                // Get farmer role
                $farmerRole = Role::where('slug', 'farmer')->first();
                
                // Create user account with email as username and default password
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make('farmer123'), // Default password
                    'role_id' => $farmerRole->id,
                    'must_change_password' => true, // Force password change on first login
                ]);

                // Create farmer linked to user
                $validated['user_id'] = $user->id;
                Farmer::create($validated);
            });

            return redirect()->route('farmers.index')
                ->with('success', 'Farmer registered successfully! Login credentials: Email as username, Password: farmer123');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while saving the farmer. Please try again.'])->withInput();
        }
    }

    public function edit(Farmer $farmer): View
    {
        return view('farmers.edit', compact('farmer'));
    }

    public function update(Request $request, Farmer $farmer): RedirectResponse
    {
        // Run validation outside try/catch so field errors are shown properly
        $validated = $request->validate([
                'name' => [
                    'required','string','max:255',
                    'regex:/^[A-Z][a-zA-Z]+(?: [A-Z][a-zA-Z]+)*$/'
                ],
                'email' => 'required|email|unique:farmers,email,' . $farmer->id . '|max:255',
                'phone' => 'required|string|regex:/^\d{10,15}$/',
                'address' => 'required|string|max:500|regex:/^[^\s]+(?: [^\s]+)*$/',
                'birthdate' => 'required|date|before:today|after:1900-01-01',
                'age' => 'required|integer|min:18|max:75',
                'gender' => 'required|in:male,female',
                'farm_size' => 'required|numeric|min:0.01|max:10000|regex:/^\d{1,6}(\.\d{1,2})?$/',
                'farm_type' => 'required|in:crop,livestock,mixed',
                'supporting_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
                'status' => 'required|in:active,inactive',
            ], [
                'name.required' => 'This field is required.',
                'name.regex' => 'Name must have capitalized words, letters only, separated by single spaces (e.g., "Juan Dela Cruz").',
                'email.required' => 'This field is required.',
                'email.email' => 'This field is required.',
                'email.unique' => 'This email is already registered.',
                'phone.required' => 'This field is required.',
                'phone.regex' => 'Phone must be digits only (10-15 digits).',
                'address.required' => 'This field is required.',
                'address.regex' => 'Address cannot contain double spaces.',
                'birthdate.required' => 'This field is required.',
                'birthdate.date' => 'This field is required.',
                'birthdate.before' => 'Invalid birth date.',
                'birthdate.after' => 'Invalid birth date.',
                'age.required' => 'This field is required.',
                'age.integer' => 'This field is required.',
                'age.min' => 'Must be at least 18 years old.',
                'age.max' => 'Age cannot exceed 75 years old.',
                'gender.required' => 'This field is required.',
                'gender.in' => 'This field is required.',
                'farm_size.required' => 'This field is required.',
                'farm_size.numeric' => 'This field is required.',
                'farm_size.min' => 'Farm size must be at least 0.01 hectares.',
                'farm_size.max' => 'Farm size cannot exceed 10,000 hectares.',
                'farm_size.regex' => 'Farm size must be a number with up to 2 decimal places.',
                'farm_type.required' => 'This field is required.',
                'farm_type.in' => 'This field is required.',
                'supporting_document.required' => 'This field is required.',
                'supporting_document.file' => 'This field is required.',
                'supporting_document.mimes' => 'Supporting document must be a PDF, JPG, PNG, DOC, or DOCX file.',
                'supporting_document.max' => 'Supporting document must not exceed 5MB.',
                'status.required' => 'This field is required.',
                'status.in' => 'This field is required.',
            ]);

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

            // Validate age matches birthdate using Carbon age calculation
            $calculatedAge = \Carbon\Carbon::parse($validated['birthdate'])->age;
            if ((int)$validated['age'] !== (int)$calculatedAge) {
                return back()->withErrors(['age' => 'Age does not match the birth date.'])->withInput();
            }
            // Align saved age to calculated age
            $validated['age'] = $calculatedAge;

            $farmer->update($validated);

            return redirect()->route('farmers.show', $farmer)
                ->with('success', 'Farmer updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while updating the farmer. Please try again.'])->withInput();
        }
    }

    public function updateStatus(Request $request, Farmer $farmer)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $farmer->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'status' => $farmer->status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the status.',
            ], 500);
        }
    }

    public function destroy(Farmer $farmer): RedirectResponse
    {
        try {
            $farmerName = $farmer->name;
            $farmer->delete();

            return redirect()->route('farmers.index')
                ->with('success', "Farmer '{$farmerName}' deleted successfully!");

        } catch (\Exception $e) {
            return redirect()->route('farmers.index')
                ->with('error', 'An error occurred while deleting the farmer. Please try again.');
        }
    }
}