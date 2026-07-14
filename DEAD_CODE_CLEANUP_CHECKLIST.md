# ☠️ AgriNex SmartDrip - Dead Code Cleanup Checklist

**Migration:** 2026_07_14_000900 dropped 9 legacy tables  
**Checklist Created:** 2026-07-14  
**Purpose:** Track removal of all references to deleted tables/models

---

## 📋 Deleted Tables Reference

```
❌ node_logs              (replaced by device_logs)
❌ sensor_node_data       (replaced by sensor_data)
❌ sensor_weather_data    (replaced by weather_data)
❌ json_backup            (feature removed)
❌ getdata_logs           (replaced by data_sessions)
❌ irrigate_logs          (replaced by irrigation_logs)
❌ node                   (replaced by devices)
❌ push_logs              (feature removed)
❌ data_sync_status       (feature removed)
```

---

## 🎯 Priority 0: BLOCKER - Must Fix Today

### Models to Delete
- [ ] Delete `app/Models/Node.php` (if exists)
- [ ] Delete `app/Models/JsonBackup.php` (if exists)
- [ ] Delete `app/Models/SensorNodeData.php` (if exists)
- [ ] Delete `app/Models/SensorWeatherData.php` (if exists)
- [ ] Delete `app/Models/GetdataLog.php` (if exists)

**Commands:**
```bash
cd /home/ghiffa/Documents/Projects_IoT/PlatformIO_Workspace/Projects/agrinex-smartdrip
find app/Models -name "Node.php" -o -name "JsonBackup.php" -o -name "SensorNodeData.php" -o -name "SensorWeatherData.php" | xargs rm -f
```

---

### Services Using Deleted Tables

#### ✅ app/Services/ExportService.php
- [ ] **Line 160-165:** Delete `getSesiColumnName()` method entirely
- [ ] **Line 138-141:** Update to use `data_session_id` instead of `sesi_id_getdata`
- [ ] **Line 145-150:** Change `received_at` to `recorded_at` for new schema
- [ ] **Test:** `php artisan tinker` → `app(ExportService::class)->exportJson('sensor_data')`

**Before:**
```php
protected function getSesiColumnName($table)
{
    $columnMap = [
        'getdata_logs' => 'sesi_id_getdata',        // ❌ DELETED TABLE
        'sensor_node_data' => 'sesi_id_getdata',    // ❌ DELETED TABLE
        'sensor_weather_data' => 'sesi_id_getdata', // ❌ DELETED TABLE
        'node_logs' => 'sesi_id',                   // ❌ DELETED TABLE
    ];
    return $columnMap[$table] ?? null;
}
```

**After:**
```php
// DELETE ENTIRE METHOD - no longer needed
```

---

### Repositories Using Deleted Models

#### ✅ app/Repositories/Eloquent/EloquentSensorDataRepository.php
- [ ] **Line 6:** Replace `use App\Models\SensorNodeData;` → `// removed - using SensorData`
- [ ] **Line 17-19:** Update `createSensorNodeRecord()` to use `SensorData`
- [ ] **Line 24-26:** Update `getLatestForNode()` to use `SensorData`
- [ ] **Line 39-43:** Update `getLatestForDevices()` subquery table name
- [ ] **Line 48:** Update `getHistory()` query model
- [ ] **Line 88:** Update `getStatistics()` query model
- [ ] **Test:** Run sensor data API endpoint

**Search & Replace:**
```bash
# In EloquentSensorDataRepository.php
sed -i 's/SensorNodeData/SensorData/g' app/Repositories/Eloquent/EloquentSensorDataRepository.php
sed -i 's/sensor_node_data/sensor_data/g' app/Repositories/Eloquent/EloquentSensorDataRepository.php
sed -i 's/sesi_id_getdata/data_session_id/g' app/Repositories/Eloquent/EloquentSensorDataRepository.php
sed -i 's/node_id/device_id/g' app/Repositories/Eloquent/EloquentSensorDataRepository.php
```

#### ✅ app/Repositories/Eloquent/EloquentWeatherDataRepository.php
- [ ] **Line 6:** Replace `use App\Models\SensorWeatherData;` → `// removed`
- [ ] **Line 16-18:** Update `createSensorWeatherRecord()` to use `WeatherData`
- [ ] **Test:** Weather data API endpoint

**Search & Replace:**
```bash
sed -i 's/SensorWeatherData/WeatherData/g' app/Repositories/Eloquent/EloquentWeatherDataRepository.php
```

#### ✅ app/Repositories/Eloquent/EloquentJsonBackupRepository.php
- [ ] **DELETE ENTIRE FILE** - json_backup table dropped
- [ ] Verify no imports remain

```bash
rm app/Repositories/Eloquent/EloquentJsonBackupRepository.php
```

#### ✅ app/Repositories/Contracts/JsonBackupRepositoryInterface.php
- [ ] **DELETE ENTIRE FILE** - interface for deleted feature

```bash
rm app/Repositories/Contracts/JsonBackupRepositoryInterface.php
```

---

### Controllers Using Deleted Models

#### ✅ app/Http/Controllers/Admin/ValveLogsController.php
- [ ] **Line 7:** Replace `use App\Models\Node;` → `use App\Models\Device;`
- [ ] **Line 14:** Replace `->with('node')` → `->with('device')`
- [ ] **Line 41:** Replace `Node::where()` → `Device::where()`
- [ ] **Line 48:** Replace `->with('node')` → `->with('device')`
- [ ] **Line 54:** Replace `->with('node')` → `->with('device')`
- [ ] **Line 55:** Replace `Node::where()` → `Device::where()`
- [ ] **Update Blade views:** Replace `$log->node` → `$log->device`

**Search & Replace:**
```bash
sed -i 's/use App\\Models\\Node;/use App\\Models\\Device;/g' app/Http/Controllers/Admin/ValveLogsController.php
sed -i 's/Node::/Device::/g' app/Http/Controllers/Admin/ValveLogsController.php
sed -i "s/with('node')/with('device')/g" app/Http/Controllers/Admin/ValveLogsController.php
```

#### ✅ app/Http/Controllers/Api/DataIngestionController.php
- [ ] **Line 8:** DELETE `use App\Repositories\Contracts\JsonBackupRepositoryInterface;`
- [ ] **Line 17:** DELETE `protected $backupRepo;`
- [ ] **Line 22:** REMOVE `JsonBackupRepositoryInterface $backupRepo` from constructor
- [ ] **Line 26:** DELETE `$this->backupRepo = $backupRepo;`
- [ ] **Line 69-79:** COMMENT OUT or DELETE backup creation logic
- [ ] **Line 150-160:** COMMENT OUT or DELETE backup creation (valve ON)
- [ ] **Line 230-240:** COMMENT OUT or DELETE backup creation (valve OFF)
- [ ] **Test:** POST to `/api/v1/sensor-data` endpoint

**Manual Edit Required** (dependency injection changes)

#### ✅ app/Http/Controllers/Api/DashboardApiController.php
- [ ] **Line 9:** DELETE `use App\Repositories\Contracts\JsonBackupRepositoryInterface;`
- [ ] **Line 17:** REMOVE `protected JsonBackupRepositoryInterface $backupRepo,`
- [ ] **Line 181:** DELETE `getJsonBackup()` method entirely
- [ ] **Update routes:** Remove backup endpoint if exists

---

### Provider Configuration

#### ✅ app/Providers/RepositoryServiceProvider.php
- [ ] **Line 34-37:** DELETE JsonBackup binding
- [ ] **Verify:** No other JsonBackup references

**Remove:**
```php
$this->app->bind(
    \App\Repositories\Contracts\JsonBackupRepositoryInterface::class,
    \App\Repositories\Eloquent\EloquentJsonBackupRepository::class
);
```

---

## 🎯 Priority 1: HIGH - Fix This Week

### Service Layer Dead Code

#### ✅ app/Services/SensorDataService.php
- [ ] **Line 49:** Update comment `getdata_logs` → `data_sessions`
- [ ] **Line 59-68:** Review auto-registration logic (move to shared method)
- [ ] **Line 78:** Update comment `sensor_weather_data` → `weather_data`
- [ ] **Line 95:** Update comment `sensor_node_data` → `sensor_data`
- [ ] **Line 111-119:** Deduplicate auto-registration (same as line 59-68)
- [ ] **Line 124:** Update comment `node_logs` → `device_logs`
- [ ] **Line 127-137:** Verify mapping to device_logs table

**Comments to Update:**
```php
// OLD: // 1. Insert getdata_logs (create DataSession first to get ID)
// NEW: // 1. Create data session for this sensor collection run

// OLD: // 2. Insert weather_data
// NEW: // 2. Insert weather readings

// OLD: // 3. Insert sensor_data
// NEW: // 3. Insert sensor readings from all devices

// OLD: // 4. Insert node_logs (device_logs)
// NEW: // 4. Log device communication status
```

#### ✅ app/Services/IrrigationService.php
- [ ] **Line 38:** Update comment referencing `irrigate_logs`
- [ ] **Line 64:** Update comment referencing `node_logs`
- [ ] **Line 82-90:** Deduplicate auto-registration logic
- [ ] **Test:** Irrigation data ingestion

#### ✅ app/Services/DeviceService.php
- [ ] **Line 73-79:** Verify no references to deleted tables
- [ ] **Add:** Shared `ensureDeviceExists()` method for auto-registration

---

### Repository Layer Comments

#### ✅ app/Repositories/Eloquent/EloquentDeviceRepository.php
- [ ] **Line 19:** Update comment "Legacy method - now uses devices table"
- [ ] **Line 30:** Update comment about `node_id` column
- [ ] **Line 37:** Update comment about `firstOrCreateNode()`
- [ ] **Line 44:** Review logic - should devices auto-create?

**Comment Updates:**
```php
// OLD: // Legacy method - node_id column doesn't exist anymore
// NEW: // Get device by ID (devices table has no separate node_id column)
```

---

## 🎯 Priority 2: MEDIUM - Technical Debt

### Variable/Field Name Cleanup

#### ✅ Search for legacy field references
```bash
# Find all references to old field names
cd /home/ghiffa/Documents/Projects_IoT/PlatformIO_Workspace/Projects/agrinex-smartdrip

grep -rn "sesi_id_getdata" app/ --include="*.php"
grep -rn "sesi_id_irrigate" app/ --include="*.php"
grep -rn "node_id" app/ --include="*.php" | grep -v "device_id"
grep -rn "received_at" app/ --include="*.php"
```

**Files to check:**
- [ ] SensorDataController.php
- [ ] DashboardPollingController.php
- [ ] MonitorController.php
- [ ] TelemetryApiController.php

---

### Validation Rules Cleanup

#### ✅ app/Http/Controllers/Api/SensorDataController.php
- [ ] **Line 44-47:** Update validation field names if needed
- [ ] Verify `data.sensor_node_data` → should be `sensor_data`
- [ ] Verify `data.sensor_weather_data` → should be `weather_data`

**Current validation:**
```php
'data.getdata_logs' => 'nullable|array',           // ❌ Old table name
'data.sensor_weather_data' => 'nullable|array',    // ❌ Old table name
'data.sensor_node_data' => 'nullable|array',       // ❌ Old table name
'data.node_logs' => 'nullable|array',              // ❌ Old table name
```

**Should be:**
```php
'data.data_sessions' => 'nullable|array',     // ✅ New table
'data.weather_data' => 'nullable|array',      // ✅ New table
'data.sensor_data' => 'nullable|array',       // ✅ New table
'data.device_logs' => 'nullable|array',       // ✅ New table
```

---

### Blade View References

#### ✅ resources/views/admin/valve-logs/*.blade.php
- [ ] Replace `$log->node` → `$log->device`
- [ ] Replace `$log->node->name` → `$log->device->name`
- [ ] Update table headers "Node" → "Device"

**Files to check:**
```bash
find resources/views/admin/valve-logs -name "*.blade.php"
```

---

## 🎯 Priority 3: LOW - Cleanup & Documentation

### Documentation Comments

#### ✅ Update PHPDoc blocks
- [ ] EloquentDeviceRepository.php - all method docs
- [ ] SensorDataService.php - processing method docs
- [ ] IrrigationService.php - processing method docs

**Example:**
```php
/**
 * Get latest sensor readings for a device
 * 
 * @param int $deviceId Device identifier
 * @return SensorData|null Latest reading or null
 */
public function getLatestForDevice($deviceId)
```

---

### API Documentation

#### ✅ Update API endpoint documentation
- [ ] POST `/api/v1/sensor-data` - request body examples
- [ ] POST `/api/v1/irrigation-data` - request body examples
- [ ] Update Postman/Swagger if exists

---

### Test Files

#### ✅ Update test fixtures
- [ ] Feature tests referencing old table names
- [ ] Factory definitions for deleted models
- [ ] Seeder files with legacy data

**Check:**
```bash
grep -rn "SensorNodeData\|SensorWeatherData\|JsonBackup\|Node" tests/
```

---

## 📊 Progress Summary

### By Category

| Category | Total Items | Completed | Remaining |
|----------|------------|-----------|-----------|
| Models to Delete | 5 | 0 | 5 |
| Services | 3 | 0 | 3 |
| Repositories | 4 | 0 | 4 |
| Controllers | 3 | 0 | 3 |
| Providers | 1 | 0 | 1 |
| Comments | 12 | 0 | 12 |
| Validation | 4 | 0 | 4 |
| Views | 3 | 0 | 3 |
| Docs | 3 | 0 | 3 |
| **TOTAL** | **38** | **0** | **38** |

---

## ✅ Verification Commands

### After Each Fix Category

```bash
# Check for remaining references to deleted tables
grep -r "node_logs\|sensor_node_data\|sensor_weather_data\|json_backup\|getdata_logs\|irrigate_logs" app/ --include="*.php"

# Check for deleted model imports
grep -r "use App\\\\Models\\\\Node;\|use App\\\\Models\\\\JsonBackup;\|use App\\\\Models\\\\SensorNodeData;\|use App\\\\Models\\\\SensorWeatherData;" app/

# Run PHPStan/Psalm (if available)
./vendor/bin/phpstan analyze app/ --level=5

# Check for undefined classes
php artisan optimize:clear
composer dump-autoload

# Run test suite
php artisan test
```

---

## 🚨 Rollback Instructions

If issues occur:

```bash
cd /home/ghiffa/Documents/Projects_IoT/PlatformIO_Workspace/Projects/agrinex-smartdrip

# Stash changes
git stash

# Or revert specific file
git checkout HEAD -- app/Services/ExportService.php

# Restore from backup (if made)
cp -r backup_2026_07_14/* .
```

---

## 📝 Notes

### Why These Tables Were Dropped
- **node_logs** → Consolidated into `device_logs` with `type_sesi` field
- **sensor_node_data** → Renamed to `sensor_data` for clarity
- **sensor_weather_data** → Renamed to `weather_data`
- **json_backup** → Redundant - data already in normalized tables
- **getdata_logs** → Replaced by `data_sessions` table
- **irrigate_logs** → Renamed to `irrigation_logs`
- **node** → Replaced by `devices` table (unified device management)
- **push_logs** → Feature removed
- **data_sync_status** → Feature removed

### Field Name Mappings
```
node_id              → device_id
sesi_id_getdata      → data_session_id (in data_sessions table)
sesi_id_irrigate     → session_id (in irrigation_logs table)
received_at          → recorded_at
waktu_mulai          → started_at
waktu_akhir          → ended_at
```

---

**Checklist Last Updated:** 2026-07-14 13:28:00  
**Estimated Total Time:** 6-8 hours  
**Recommended Approach:** Fix Priority 0 first, then test thoroughly before Priority 1
