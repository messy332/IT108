<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user = new User();
        $user->name = (string) $request->input('name');
        $user->email = strtolower((string) $request->input('email'));
        $user->password = Hash::make((string) $request->input('password'));
        $user->role_id = $request->input('role_id', 3); // Default to farmer role (id: 3)
        $user->save();

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role
        if ($user->isFarmer()) {
            return redirect()->route('farmer.profile.create');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
