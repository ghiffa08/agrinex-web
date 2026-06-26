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
            $table->renameColumn('temp_c', 'temperature');
            $table->renameColumn('soil_pct', 'soil_moisture');
            $table->decimal('battery_pct', 5, 2)->nullable()->after('voltage_v');
            $table->decimal('flow_rate', 8, 2)->nullable()->after('power_mw');
            $table->decimal('rssi', 5, 1)->nullable()->after('adaptive_sleep_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->renameColumn('temperature', 'temp_c');
            $table->renameColumn('soil_moisture', 'soil_pct');
            $table->dropColumn(['battery_pct', 'flow_rate', 'rssi']);
        });
    }
};
