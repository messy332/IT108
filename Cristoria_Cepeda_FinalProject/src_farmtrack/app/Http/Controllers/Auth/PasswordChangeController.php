<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    /**
     * Show the password change form.
     */
    public function show()
    {
        return view('auth.change-password');
    }

    /**
     * Handle the password change request.
     */
    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.required' => 'Please enter a new password.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        // Prevent using the default password
        if ($request->password === 'farmer123') {
            return back()->withErrors(['password' => 'You cannot use the default password. Please choose a different password.']);
        }

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        // Redirect based on role
        if ($user->isFarmer()) {
            return redirect()->route('farmer.dashboard')
                ->with('success', 'Password changed successfully! Welcome to your dashboard.');
        }

        return redirect()->route('dashboard')
            ->with('success', 'Password changed successfully!');
    }
}
