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
        Schema::table('devices', function (Blueprint $table) {
            // Add missing columns that code expects but DB doesn't have
            if (!Schema::hasColumn('devices', 'name')) {
                $table->string('name', 100)->nullable()->after('id');
            }
            
            if (!Schema::hasColumn('devices', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('image_url');
            }
            
            if (!Schema::hasColumn('devices', 'location')) {
                $table->string('location', 255)->nullable()->after('is_active');
            }
        });
        
        // Populate name column from existing data
        DB::statement("UPDATE devices SET name = CONCAT('Device ', id) WHERE name IS NULL");
        DB::statement("UPDATE devices SET location = lokasi WHERE location IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            if (Schema::hasColumn('devices', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('devices', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('devices', 'location')) {
                $table->dropColumn('location');
            }
        });
    }
};
