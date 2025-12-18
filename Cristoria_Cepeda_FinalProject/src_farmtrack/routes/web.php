<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\ProgressLogController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\FarmerProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PasswordChangeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::view('/demo', 'demo')->name('demo');

// Auth check endpoint for JavaScript
Route::get('/auth/check', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'redirect' => auth()->check() 
            ? (auth()->user()->isFarmer() ? route('farmer.dashboard') : route('dashboard'))
            : null
    ]);
})->name('auth.check');

// Public stats endpoint for landing page polling
Route::get('/stats/summary', [StatsController::class, 'summary'])->name('stats.summary');

// Password change routes (for users with must_change_password flag)
Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [PasswordChangeController::class, 'show'])->name('password.change');
    Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.update');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'prevent-back-history', 'password.change'])
    ->name('dashboard');

Route::get('/dashboard/search', [DashboardController::class, 'search'])
    ->middleware(['auth', 'prevent-back-history', 'password.change'])
    ->name('dashboard.search');

Route::middleware(['auth', 'prevent-back-history', 'password.change'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes - Only accessible by admin role
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('farmers', FarmerController::class);
        Route::patch('farmers/{farmer}/status', [FarmerController::class, 'updateStatus'])->name('farmers.updateStatus');
        Route::resource('crops', CropController::class);
        Route::resource('progress-logs', ProgressLogController::class);
        Route::resource('users', UserController::class);
    });

    // Farmer Routes - Only accessible by farmer role
    Route::middleware(['role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
        Route::get('/dashboard', [FarmerProfileController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/profile/index', [FarmerProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile', [FarmerProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/create', [FarmerProfileController::class, 'create'])->name('profile.create');
        Route::post('/profile', [FarmerProfileController::class, 'store'])->name('profile.store');
        Route::get('/profile/edit', [FarmerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [FarmerProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [FarmerProfileController::class, 'destroy'])->name('profile.destroy');
        
        // Farmer's own crops management
        Route::get('/crops', [FarmerProfileController::class, 'myCrops'])->name('crops.index');
        Route::get('/crops/create', [FarmerProfileController::class, 'createCrop'])->name('crops.create');
        Route::post('/crops', [FarmerProfileController::class, 'storeCrop'])->name('crops.store');
        Route::get('/crops/{crop}', [FarmerProfileController::class, 'showCrop'])->name('crops.show');
        Route::get('/crops/{crop}/edit', [FarmerProfileController::class, 'editCrop'])->name('crops.edit');
        Route::put('/crops/{crop}', [FarmerProfileController::class, 'updateCrop'])->name('crops.update');
        Route::delete('/crops/{crop}', [FarmerProfileController::class, 'destroyCrop'])->name('crops.destroy');
        
        // Farmer's own progress logs management
        Route::get('/progress-logs', [FarmerProfileController::class, 'myProgressLogs'])->name('progress-logs.index');
        Route::get('/progress-logs/create', [FarmerProfileController::class, 'createProgressLog'])->name('progress-logs.create');
        Route::post('/progress-logs', [FarmerProfileController::class, 'storeProgressLog'])->name('progress-logs.store');
        Route::get('/progress-logs/{progressLog}', [FarmerProfileController::class, 'showProgressLog'])->name('progress-logs.show');
        Route::get('/progress-logs/{progressLog}/edit', [FarmerProfileController::class, 'editProgressLog'])->name('progress-logs.edit');
        Route::put('/progress-logs/{progressLog}', [FarmerProfileController::class, 'updateProgressLog'])->name('progress-logs.update');
        Route::delete('/progress-logs/{progressLog}', [FarmerProfileController::class, 'destroyProgressLog'])->name('progress-logs.destroy');
    });

    // Static pages
    Route::view('/help', 'static.help')->name('help');
    Route::view('/about', 'static.about')->name('about');
});

require __DIR__.'/auth.php';
