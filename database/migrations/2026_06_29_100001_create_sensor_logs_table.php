<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sensor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->decimal('temperature', 5, 2);
            $table->decimal('humidity', 5, 2);
            $table->decimal('soil_moisture', 5, 2);
            $table->decimal('battery_level', 5, 2);
            $table->timestamp('created_at')->useCurrent();

            // Indexes for fast analytics retrieval
            $table->index('device_id');
            $table->index('created_at');
            $table->index(['device_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_logs');
    }
};
