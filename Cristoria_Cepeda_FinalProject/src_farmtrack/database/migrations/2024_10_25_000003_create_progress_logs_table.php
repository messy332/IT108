<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_id')->constrained()->onDelete('cascade');
            $table->date('log_date');
            $table->string('activity_type'); // watering, fertilizing, weeding, pest_control, harvesting
            $table->text('description');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('weather_condition')->nullable();
            $table->integer('growth_stage')->nullable(); // 1-10 scale
            $table->text('observations')->nullable();
            $table->json('images')->nullable(); // store image paths
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_logs');
    }
};