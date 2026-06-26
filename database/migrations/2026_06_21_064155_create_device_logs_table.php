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
        Schema::create('device_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            
            $table->float('rssi_dbm')->nullable();
            $table->float('snr_db')->nullable();
            $table->string('signal_quality', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('session_type', 64)->nullable();
            $table->string('session_ref_id', 64)->nullable();
            $table->text('remarks')->nullable();
            
            $table->timestamp('logged_at')->useCurrent();
            
            $table->index('logged_at');
            $table->index(['device_id', 'logged_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_logs');
    }
};
