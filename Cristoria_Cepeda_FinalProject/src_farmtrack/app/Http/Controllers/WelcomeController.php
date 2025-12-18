<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Crop;
use App\Models\ProgressLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function index(): View|RedirectResponse
    {
        // If user is already authenticated, redirect to their dashboard
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->isFarmer()) {
                return redirect()->route('farmer.dashboard');
            }

            return redirect()->route('dashboard');
        }

        try {
            $stats = [
                'total_farmers' => Farmer::count(),
                'active_farmers' => Farmer::where('status', 'active')->count(),
                'total_crops' => Crop::count(),
                'active_crops' => Crop::whereIn('status', ['planted', 'growing'])->count(),
                'total_logs' => ProgressLog::count(),
                'total_farm_area' => Farmer::sum('farm_size') ?? 0,
            ];
        } catch (\Exception $e) {
            // If tables don't exist yet, show default stats
            $stats = [
                'total_farmers' => 0,
                'active_farmers' => 0,
                'total_crops' => 0,
                'active_crops' => 0,
                'total_logs' => 0,
                'total_farm_area' => 0,
            ];
        }

        return view('welcome', compact('stats'));
    }
}
