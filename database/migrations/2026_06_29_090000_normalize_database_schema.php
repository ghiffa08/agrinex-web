<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ── 1. Drop Obsolete/Redundant Tables in Dependency Order ────────────
        Schema::dropIfExists('sensor_data');
        Schema::dropIfExists('device_logs');
        Schema::dropIfExists('weather_data');
        Schema::dropIfExists('irrigation_logs');
        Schema::dropIfExists('data_sessions');
        Schema::dropIfExists('devices');

        // ── 2. Deduplicate Records to Allow Unique Index Constraints ────────
        // Deduplicate node table on node_id
        DB::statement("
            DELETE t1 FROM node t1
            INNER JOIN node t2 
            WHERE t1.id > t2.id AND t1.node_id = t2.node_id
        ");

        // Deduplicate getdata_logs table on sesi_id_getdata
        DB::statement("
            DELETE t1 FROM getdata_logs t1
            INNER JOIN getdata_logs t2 
            WHERE t1.id > t2.id AND t1.sesi_id_getdata = t2.sesi_id_getdata
        ");

        // Deduplicate irrigate_logs table on sesi_id_irrigate
        DB::statement("
            DELETE t1 FROM irrigate_logs t1
            INNER JOIN irrigate_logs t2 
            WHERE t1.id > t2.id AND t1.sesi_id_irrigate = t2.sesi_id_irrigate
        ");

        // ── 3. Clean Up Orphaned Data (to satisfy foreign keys) ──────────────
        // Scrub sensor_node_data orphans
        DB::statement("DELETE FROM sensor_node_data WHERE node_id NOT IN (SELECT node_id FROM node)");
        DB::statement("DELETE FROM sensor_node_data WHERE sesi_id_getdata NOT IN (SELECT sesi_id_getdata FROM getdata_logs)");
        
        // Scrub node_logs orphans
        DB::statement("DELETE FROM node_logs WHERE node_id NOT IN (SELECT node_id FROM node)");
        
        // Scrub valve_logs orphans
        DB::statement("DELETE FROM valve_logs WHERE node_id NOT IN (SELECT node_id FROM node)");
        DB::statement("DELETE FROM valve_logs WHERE sesi_id_irrigate NOT IN (SELECT sesi_id_irrigate FROM irrigate_logs)");

        // ── 4. Add Unique Index Constraints Safely ───────────────────────────
        try { DB::statement("ALTER TABLE node DROP INDEX node_node_id_unique"); } catch (\Exception $e) {}
        DB::statement("ALTER TABLE node ADD UNIQUE INDEX node_node_id_unique (node_id)");

        try { DB::statement("ALTER TABLE getdata_logs DROP INDEX getdata_logs_sesi_id_unique"); } catch (\Exception $e) {}
        DB::statement("ALTER TABLE getdata_logs ADD UNIQUE INDEX getdata_logs_sesi_id_unique (sesi_id_getdata)");

        try { DB::statement("ALTER TABLE irrigate_logs DROP INDEX irrigate_logs_sesi_id_unique"); } catch (\Exception $e) {}
        DB::statement("ALTER TABLE irrigate_logs ADD UNIQUE INDEX irrigate_logs_sesi_id_unique (sesi_id_irrigate)");

        // ── 5. Extend sensor_node_data with Telemetry Fields Safely ───────────
        Schema::table('sensor_node_data', function (Blueprint $table) {
            if (!Schema::hasColumn('sensor_node_data', 'battery_pct')) {
                $table->decimal('battery_pct', 5, 2)->nullable()->after('voltage_v');
            }
            if (!Schema::hasColumn('sensor_node_data', 'flow_rate')) {
                $table->decimal('flow_rate', 8, 2)->nullable()->after('power_mw');
            }
            if (!Schema::hasColumn('sensor_node_data', 'total_volume_l')) {
                $table->decimal('total_volume_l', 10, 2)->nullable()->after('flow_rate');
            }
            if (!Schema::hasColumn('sensor_node_data', 'ai_valve_decision')) {
                $table->string('ai_valve_decision', 16)->nullable()->after('soil_adc');
            }
            if (!Schema::hasColumn('sensor_node_data', 'adaptive_sleep_duration')) {
                $table->integer('adaptive_sleep_duration')->nullable()->after('ai_valve_decision');
            }
            if (!Schema::hasColumn('sensor_node_data', 'rssi')) {
                $table->decimal('rssi', 5, 1)->nullable()->after('adaptive_sleep_duration');
            }
        });

        // ── 6. Establish Foreign Key Constraints Safely ──────────────────────
        // Drop existing foreign keys using raw SQL try-catch
        try { DB::statement("ALTER TABLE sensor_node_data DROP FOREIGN KEY sensor_node_data_node_id_foreign"); } catch (\Exception $e) {}
        try { DB::statement("ALTER TABLE sensor_node_data DROP FOREIGN KEY sensor_node_data_sesi_id_getdata_foreign"); } catch (\Exception $e) {}
        try { DB::statement("ALTER TABLE node_logs DROP FOREIGN KEY node_logs_node_id_foreign"); } catch (\Exception $e) {}
        try { DB::statement("ALTER TABLE valve_logs DROP FOREIGN KEY valve_logs_node_id_foreign"); } catch (\Exception $e) {}
        try { DB::statement("ALTER TABLE valve_logs DROP FOREIGN KEY valve_logs_sesi_id_irrigate_foreign"); } catch (\Exception $e) {}

        Schema::table('sensor_node_data', function (Blueprint $table) {
            $table->foreign('node_id')->references('node_id')->on('node')->cascadeOnDelete();
            $table->foreign('sesi_id_getdata')->references('sesi_id_getdata')->on('getdata_logs')->cascadeOnDelete();
        });

        Schema::table('node_logs', function (Blueprint $table) {
            $table->foreign('node_id')->references('node_id')->on('node')->cascadeOnDelete();
        });

        Schema::table('valve_logs', function (Blueprint $table) {
            $table->foreign('node_id')->references('node_id')->on('node')->cascadeOnDelete();
            $table->foreign('sesi_id_irrigate')->references('sesi_id_irrigate')->on('irrigate_logs')->cascadeOnDelete();
        });

        // ── 7. Create Database Triggers ──────────────────────────────────────
        DB::unprepared("DROP TRIGGER IF EXISTS after_sensor_data_insert");
        DB::unprepared("
            CREATE TRIGGER after_sensor_data_insert
            AFTER INSERT ON sensor_node_data
            FOR EACH ROW
            BEGIN
                INSERT INTO node_logs (
                    node_id,
                    rssi_dbm,
                    snr_db,
                    signal_quality,
                    status,
                    waktu,
                    type_sesi,
                    sesi_id,
                    keterangan
                ) VALUES (
                    NEW.node_id,
                    NEW.rssi,
                    NULL,
                    CASE 
                        WHEN NEW.rssi IS NULL THEN 'Unknown'
                        WHEN NEW.rssi >= -70 THEN 'Excellent'
                        WHEN NEW.rssi >= -85 THEN 'Good'
                        WHEN NEW.rssi >= -100 THEN 'Fair'
                        ELSE 'Poor'
                    END,
                    'Aktif',
                    NEW.received_at,
                    'telemetry',
                    CAST(NEW.sesi_id_getdata AS CHAR),
                    CONCAT('LoRa node=SENDER_', LPAD(NEW.node_id, 2, '0'), ' valve=', IFNULL(NEW.ai_valve_decision, '-'), ' sleep=', IFNULL(NEW.adaptive_sleep_duration, '-'), 's')
                );
            END
        ");

        DB::unprepared("DROP TRIGGER IF EXISTS after_valve_log_insert");
        DB::unprepared("
            CREATE TRIGGER after_valve_log_insert
            AFTER INSERT ON valve_logs
            FOR EACH ROW
            BEGIN
                INSERT INTO node_logs (
                    node_id,
                    rssi_dbm,
                    snr_db,
                    signal_quality,
                    status,
                    waktu,
                    type_sesi,
                    sesi_id,
                    keterangan
                ) VALUES (
                    NEW.node_id,
                    NULL,
                    NULL,
                    'Unknown',
                    CASE WHEN NEW.status = 'ON' THEN 'Aktif' ELSE 'Non Aktif' END,
                    NEW.waktu,
                    'irrigate',
                    CAST(NEW.sesi_id_irrigate AS CHAR),
                    CONCAT('Valve turned ', NEW.status, '. Duration: ', IFNULL(NEW.durasi_detik, '-'), 's, Volume: ', IFNULL(NEW.volume_air, '-'), 'L')
                );
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared("DROP TRIGGER IF EXISTS after_sensor_data_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS after_valve_log_insert");

        // Remove foreign keys from valve_logs
        Schema::table('valve_logs', function (Blueprint $table) {
            $table->dropForeign(['node_id']);
            $table->dropForeign(['sesi_id_irrigate']);
        });

        // Remove foreign keys from node_logs
        Schema::table('node_logs', function (Blueprint $table) {
            $table->dropForeign(['node_id']);
        });

        // Remove foreign keys and columns from sensor_node_data
        Schema::table('sensor_node_data', function (Blueprint $table) {
            $table->dropForeign(['node_id']);
            $table->dropForeign(['sesi_id_getdata']);
            
            $table->dropColumn([
                'battery_pct',
                'flow_rate',
                'total_volume_l',
                'ai_valve_decision',
                'adaptive_sleep_duration',
                'rssi'
            ]);
        });

        // Remove unique indexes
        DB::statement("ALTER TABLE node DROP INDEX node_node_id_unique");
        DB::statement("ALTER TABLE getdata_logs DROP INDEX getdata_logs_sesi_id_unique");
        DB::statement("ALTER TABLE irrigate_logs DROP INDEX irrigate_logs_sesi_id_unique");
    }
};
