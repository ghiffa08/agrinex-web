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
        if (!Schema::hasTable('valve_logs')) {
            Schema::create('valve_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
                $table->foreignId('irrigation_log_id')->constrained('irrigation_logs')->cascadeOnDelete();
                
                $table->string('valve_status', 20)->nullable();
                $table->string('reason', 100)->nullable();
                
                $table->timestamp('logged_at')->useCurrent();
                
                $table->index('logged_at');
                $table->index(['device_id', 'logged_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valve_logs');
    }
};
