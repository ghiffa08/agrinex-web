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
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->string('ai_valve_decision')->nullable()->after('soil_adc');
            $table->integer('adaptive_sleep_duration')->nullable()->after('ai_valve_decision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->dropColumn(['ai_valve_decision', 'adaptive_sleep_duration']);
        });
    }
};
