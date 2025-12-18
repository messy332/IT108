<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->onDelete('cascade');
            $table->string('crop_name');
            $table->string('variety')->nullable();
            $table->decimal('area_planted', 8, 2); // in hectares
            $table->date('planting_date');
            $table->date('expected_harvest_date')->nullable();
            $table->date('actual_harvest_date')->nullable();
            $table->string('season'); // wet, dry, year-round
            $table->string('status')->default('planted'); // planted, growing, harvested, failed
            $table->decimal('expected_yield', 10, 2)->nullable(); // in kg
            $table->decimal('actual_yield', 10, 2)->nullable(); // in kg
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crops');
    }
};