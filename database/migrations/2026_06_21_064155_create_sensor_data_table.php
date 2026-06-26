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
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_session_id')->constrained('data_sessions')->cascadeOnDelete();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            
            $table->decimal('voltage_v', 5, 2)->nullable();
            $table->decimal('current_ma', 7, 2)->nullable();
            $table->decimal('power_mw', 8, 2)->nullable();
            $table->decimal('temp_c', 5, 2)->nullable();
            $table->decimal('soil_pct', 5, 2)->nullable();
            $table->integer('soil_adc')->nullable();
            $table->bigInteger('ts_counter')->nullable();
            
            $table->timestamp('recorded_at')->useCurrent();
            
            $table->index('recorded_at');
            $table->index(['device_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
