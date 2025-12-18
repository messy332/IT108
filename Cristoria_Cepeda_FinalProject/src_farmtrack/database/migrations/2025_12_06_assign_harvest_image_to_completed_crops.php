<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Crop;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        // Assign proof image to all crops that have actual_harvest_date OR actual_yield
        Crop::where(function($query) {
            $query->whereNotNull('actual_harvest_date')
                  ->orWhereNotNull('actual_yield');
        })
        ->whereNull('harvest_image')
        ->update(['harvest_image' => 'crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg']);
    }

    public function down(): void
    {
        // Remove proof images that were assigned by this migration
        Crop::where('harvest_image', 'crops/dGk5YbwQOEIYEYvNW19PNTSdpzTyqxtXFd3yKW0i.jpg')
            ->where(function($query) {
                $query->whereNotNull('actual_harvest_date')
                      ->orWhereNotNull('actual_yield');
            })
            ->update(['harvest_image' => null]);
    }
};
