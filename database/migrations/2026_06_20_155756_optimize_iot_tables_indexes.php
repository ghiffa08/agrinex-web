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
        if (!Schema::hasTable('getdata_logs')) {
            return;
        }
        
        Schema::table('getdata_logs', function (Blueprint $table) {
            $table->index('waktu_mulai');
            $table->index('sesi_id_getdata');
        });

        Schema::table('sensor_node_data', function (Blueprint $table) {
            $table->index('received_at');
            $table->index('node_id');
        });

        Schema::table('sensor_weather_data', function (Blueprint $table) {
            $table->index('received_at');
            $table->index('node_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('getdata_logs', function (Blueprint $table) {
            $table->dropIndex(['waktu_mulai']);
            $table->dropIndex(['sesi_id_getdata']);
        });

        Schema::table('sensor_node_data', function (Blueprint $table) {
            $table->dropIndex(['received_at']);
            $table->dropIndex(['node_id']);
        });

        Schema::table('sensor_weather_data', function (Blueprint $table) {
            $table->dropIndex(['received_at']);
            $table->dropIndex(['node_id']);
        });
    }
};
