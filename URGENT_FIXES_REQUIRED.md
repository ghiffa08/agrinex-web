# 🚨 AgriNex SmartDrip - Urgent Fixes Required

**Priority:** CRITICAL - Fix dalam 1-3 hari  
**Impact:** Application errors, missing data, security risks  
**Audited:** 2026-07-14  

---

## 📅 Day-by-Day Fix Guide

### DAY 1 - BLOCKER FIXES (3-4 jam)

#### ⚠️ Fix 1: ExportService.php - Dead Table References
**File:** `app/Services/ExportService.php`  
**Lines:** 157-168  
**Issue:** Method `getSesiColumnName()` references 4 deleted tables

**Current Code (BROKEN):**
```php
protected function getSesiColumnName($table)
{
    $columnMap = [
        'getdata_logs' => 'sesi_id_getdata',        // ❌ Table dropped
        'sensor_node_data' => 'sesi_id_getdata',    // ❌ Table dropped
        'sensor_weather_data' => 'sesi_id_getdata', // ❌ Table dropped
        'node_logs' => 'sesi_id',                   // ❌ Table dropped
    ];
    return $columnMap[$table] ?? null;
}
```

**Fix:** DELETE entire method (lines 157-168) and update `getTableData()`:
```php
protected function getTableData($table, $filters = [])
{
    if (!isset($this->tables[$table])) {
        return [];
    }

    $model = $this->tables[$table];
    $query = $model::query();

    // Apply filters - use data_session_id for new schema
    if (!empty($filters['sesi_id'])) {
        // Map to new column names
        if ($table === 'sensor_data' || $table === 'weather_data') {
            $query->where('data_session_id', $filters['sesi_id']);
        }
    }

    if (!empty($filters['start_date'])) {
        $query->whereDate('recorded_at', '>=', $filters['start_date']);
    }

    if (!empty($filters['end_date'])) {
        $query->whereDate('recorded_at', '<=', $filters['end_date']);
    }

    $limit = $filters['limit'] ?? 1000;
    
    return $query->limit($limit)->get()->toArray();
}
```

**Command:**
```bash
# Test after fix
php artisan tinker
>>> app(App\Services\ExportService::class)->exportJson('sensor_data', ['limit' => 10]);
```

---

#### ⚠️ Fix 2: EloquentSensorDataRepository.php - Deleted Model Usage
**File:** `app/Repositories/Eloquent/EloquentSensorDataRepository.php`  
**Lines:** 6, 17-27, 39-43, 48, 88  
**Issue:** Uses `SensorNodeData` model - table deleted

**Current Code (BROKEN):**
```php
use App\Models\SensorNodeData;  // ❌ Model for deleted table

public function createSensorNodeRecord(array $data)
{
    return SensorNodeData::create($data);  // ❌ ERROR
}
```

**Fix:** Replace ALL occurrences of `SensorNodeData` with `SensorData`:

```php
<?php

namespace App\Repositories\Eloquent;

use App\Models\SensorData;  // ✅ Active model
use App\Repositories\Contracts\SensorDataRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EloquentSensorDataRepository implements SensorDataRepositoryInterface
{
    public function createSensorRecord(array $data)
    {
        return SensorData::create($data);
    }

    public function createSensorNodeRecord(array $data)
    {
        // Legacy method - now uses sensor_data table
        return SensorData::create($data);
    }

    public function getLatestForNode($nodeId)
    {
        return SensorData::where('device_id', $nodeId)
            ->latest('recorded_at')
            ->first();
    }

    public function getLatestForDevice($deviceId)
    {
        return SensorData::where('device_id', $deviceId)
            ->latest('recorded_at')
            ->first();
    }

    public function getLatestForDevices()
    {
        // FIX N+1: Get latest record per device
        return SensorData::whereIn('id', function($query) {
            $query->select(DB::raw('MAX(id)'))
                ->from('sensor_data')
                ->groupBy('device_id');
        })->get();
    }

    public function getHistory($filters, $limit)
    {
        $query = SensorData::query();

        if (!empty($filters['sesi_id'])) {
            $query->where('data_session_id', $filters['sesi_id']);
        }

        if (!empty($filters['device_id'])) {
            $query->where('device_id', $filters['device_id']);
        }

        $orderBy = $filters['order_by'] ?? 'recorded_at';
        $orderDir = $filters['order_dir'] ?? 'desc';

        return $query->orderBy($orderBy, $orderDir)
            ->limit($limit)
            ->get();
    }

    public function getSensorDataHistory($filters, $limit)
    {
        $query = SensorData::query();

        if (!empty($filters['device_id'])) {
            $query->where('device_id', $filters['device_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->where('recorded_at', '>=', $filters['start_date']);
        }

        $orderBy = $filters['order_by'] ?? 'recorded_at';
        $orderDir = $filters['order_dir'] ?? 'asc';

        return $query->orderBy($orderBy, $orderDir)
            ->limit($limit)
            ->get();
    }

    public function getStatistics($sesiId = null)
    {
        $query = SensorData::query();

        if ($sesiId) {
            $query->where('data_session_id', $sesiId);
        }

        return [
            'total_readings' => $query->count(),
            'avg_temperature' => round($query->avg('temperature'), 2),
            'avg_soil_moisture' => round($query->avg('soil_moisture'), 2),
            'avg_voltage' => round($query->avg('voltage_v'), 2),
            'min_temperature' => $query->min('temperature'),
            'max_temperature' => $query->max('temperature'),
            'devices_count' => $query->distinct('device_id')->count('device_id'),
        ];
    }
}
```

---

#### ⚠️ Fix 3: EloquentWeatherDataRepository.php - Deleted Model Usage
**File:** `app/Repositories/Eloquent/EloquentWeatherDataRepository.php`  
**Lines:** 6, 16-19  
**Issue:** Uses `SensorWeatherData` model

**Fix:** Replace dengan WeatherData:
```php
<?php

namespace App\Repositories\Eloquent;

use App\Models\WeatherData;
use App\Repositories\Contracts\WeatherDataRepositoryInterface;

class EloquentWeatherDataRepository implements WeatherDataRepositoryInterface
{
    public function createWeatherRecord(array $data)
    {
        return WeatherData::create($data);
    }

    public function createSensorWeatherRecord(array $data)
    {
        // Legacy method - now uses weather_data table
        return WeatherData::create($data);
    }

    public function getLatest($sessionId = null)
    {
        if ($sessionId) {
            return WeatherData::where('data_session_id', $sessionId)->first();
        }
        return WeatherData::latest('recorded_at')->first();
    }
    
    public function getLatestWeatherData()
    {
        return WeatherData::latest('recorded_at')->first();
    }
}
```

---

#### ⚠️ Fix 4: Remove JsonBackup Dependencies
**Files to DELETE:**
1. `app/Repositories/Eloquent/EloquentJsonBackupRepository.php`
2. `app/Repositories/Contracts/JsonBackupRepositoryInterface.php`

**File to UPDATE:** `app/Providers/RepositoryServiceProvider.php`

**Remove lines 34-37:**
```php
// DELETE THIS:
$this->app->bind(
    \App\Repositories\Contracts\JsonBackupRepositoryInterface::class,
    \App\Repositories\Eloquent\EloquentJsonBackupRepository::class
);
```

**Files to UPDATE:** Remove JsonBackup dependency injections

**File:** `app/Http/Controllers/Api/DataIngestionController.php`
```php
// Line 8: DELETE
use App\Repositories\Contracts\JsonBackupRepositoryInterface;

// Line 22: DELETE
JsonBackupRepositoryInterface $backupRepo

// Line 17: DELETE
protected $backupRepo;

// Line 26: DELETE
$this->backupRepo = $backupRepo;

// Lines 69-79: COMMENT OUT backup creation
/*
$this->backupRepo->createBackup([
    'sesi_id_getdata' => $request->input('metadata.sesi_id_getdata'),
    ...
]);
*/
// Add comment: "JSON backup feature removed - data already in sensor_data/weather_data"
```

**File:** `app/Http/Controllers/Api/DashboardApiController.php`
```php
// Line 9: DELETE
use App\Repositories\Contracts\JsonBackupRepositoryInterface;

// Line 17: DELETE
protected JsonBackupRepositoryInterface $backupRepo,

// Line 181-end: DELETE method getJsonBackup()
```

**Commands:**
```bash
rm app/Repositories/Eloquent/EloquentJsonBackupRepository.php
rm app/Repositories/Contracts/JsonBackupRepositoryInterface.php
```

---

#### ⚠️ Fix 5: ValveLogsController.php - Deleted Node Model
**File:** `app/Http/Controllers/Admin/ValveLogsController.php`  
**Line:** 7  
**Issue:** `use App\Models\Node;` - Model doesn't exist

**Fix:**
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ValveLog;
use App\Models\Device;  // ✅ Replace Node with Device
use Illuminate\Http\Request;

class ValveLogsController extends Controller
{
    public function index(Request $request)
    {
        // Remove ->with('node') - relation doesn't exist
        $query = ValveLog::with('device')->orderBy('waktu', 'desc');
        
        // Filter by device_id (not node_id)
        if ($request->has('device_id') && $request->device_id != '') {
            $query->where('device_id', $request->device_id);
        }
        
        // ... rest unchanged
        
        $logs = $query->simplePaginate(25);
        $devices = Device::where('id', '!=', 65)->orderBy('id')->get();
        
        return view('admin.valve-logs.index', compact('logs', 'devices'));
    }
    
    public function show($id)
    {
        $log = ValveLog::with('device')->findOrFail($id);
        return view('admin.valve-logs.show', compact('log'));
    }
    
    public function edit($id)
    {
        $log = ValveLog::with('device')->findOrFail($id);
        $devices = Device::where('id', '!=', 65)->orderBy('id')->get();
        return view('admin.valve-logs.edit', compact('log', 'devices'));
    }
    
    // ... rest of methods unchanged
}
```

**Also update views:** `resources/views/admin/valve-logs/*.blade.php`
```blade
{{-- Replace $log->node with $log->device --}}
{{ $log->device->name ?? 'Unknown' }}
```

---

### DAY 2 - SECURITY FIXES (2-3 jam)

#### 🔒 Fix 6: Remove Console.log Statements
**File:** `resources/views/partials/chart-fix.blade.php`  
**Issue:** 21 console.log statements exposing sensor data

**Options:**

**Option A - Complete Removal (Recommended for Production):**
```bash
cd resources/views/partials
sed -i '/console\.log/d' chart-fix.blade.php
```

**Option B - Convert to Silent Mode:**
```javascript
// Replace all console.log with commented version
// console.log('🚀 [CHART-FIX] ...');
```

**Option C - Production Guard:**
```javascript
@if(config('app.debug'))
    console.log('🚀 [CHART-FIX] DOM Ready');
@endif
```

---

#### 🔒 Fix 7: Standardize Mass Assignment Protection
**Issue:** `SensorData` model has BOTH $guarded AND $fillable

**File:** `app/Models/SensorData.php`

**Current (CONFLICTING):**
```php
protected $guarded = ['id'];
protected $fillable = [
    'data_session_id',
    'device_id',
    // ... 15 more fields
];
```

**Fix - Choose ONE approach:**
```php
// Option 1: Use $guarded only (Recommended)
protected $guarded = ['id'];
// DELETE $fillable array

// Option 2: Use $fillable only
protected $fillable = [
    'data_session_id',
    'device_id',
    'voltage_v',
    'battery_pct',
    'current_ma',
    'power_mw',
    'temperature',
    'soil_moisture',
    'flow_rate',
    'total_volume_l',
    'soil_adc',
    'ai_valve_decision',
    'adaptive_sleep_duration',
    'rssi',
    'recorded_at',
];
// DELETE $guarded
```

---

### DAY 3 - CODE QUALITY (2-3 jam)

#### 📦 Fix 8: Deduplicate Auto-Registration Logic
**Issue:** Auto-registration code repeated 3x in different services

**Current duplicates:**
- `SensorDataService.php` lines 59-68 (getdata_logs)
- `SensorDataService.php` lines 111-119 (sensor_node_data)
- `IrrigationService.php` lines 82-90 (node_logs)

**Fix:** Create dedicated method in DeviceService:

**File:** `app/Services/DeviceService.php`
```php
/**
 * Ensure device exists, log warning if not found
 * Devices should be pre-registered via admin panel
 */
public function ensureDeviceExists(int $deviceId, string $context = 'API'): ?Device
{
    $device = Device::find($deviceId);
    
    if (!$device) {
        \Log::warning("Device ID {$deviceId} not found during {$context} - device should be pre-registered");
    }
    
    return $device;
}
```

**Then update all services:**
```php
// SensorDataService.php - replace lines 59-68 and 111-119
if (isset($log['node_id'])) {
    $this->deviceService->ensureDeviceExists($log['node_id'], 'sensor data ingestion');
}

// IrrigationService.php - replace lines 82-90
if (isset($nodeLog['node_id'])) {
    $this->deviceService->ensureDeviceExists($nodeLog['node_id'], 'irrigation');
}
```

---

#### 📝 Fix 9: Clean Up Legacy Comments
**Files with outdated comments:**

1. `SensorDataService.php` line 49:
```php
// OLD: // 1. Insert getdata_logs (create DataSession first to get ID)
// NEW: // 1. Create DataSession for this data collection run
```

2. `SensorDataService.php` line 77:
```php
// OLD: // 2. Insert weather_data
// NEW: // 2. Insert weather readings
```

3. `SensorDataService.php` line 94:
```php
// OLD: // 3. Insert sensor_data
// NEW: // 3. Insert sensor readings from all devices
```

4. `SensorDataService.php` line 124:
```php
// OLD: // 4. Insert node_logs (device_logs)
// NEW: // 4. Log device communication status
```

5. `EloquentDeviceRepository.php` lines 19, 30, 37:
```php
// Remove all "Legacy method" comments and rewrite:
/**
 * Get all devices with their latest sensor reading
 */
public function allNodes()
{
    return Device::with('lahanPantau')->orderBy('id')->get();
}
```

---

## ✅ Verification Checklist

### After Day 1 Fixes:
```bash
# Test export service
php artisan tinker
>>> app(App\Services\ExportService::class)->exportJson('sensor_data');

# Test sensor data repository
>>> app(App\Repositories\Contracts\SensorDataRepositoryInterface::class)->getLatestForDevices();

# Check for errors
tail -f storage/logs/laravel.log
```

### After Day 2 Fixes:
```bash
# Check blade files
grep -r "console.log" resources/views/

# Test mass assignment
php artisan tinker
>>> $data = ['device_id' => 1, 'temperature' => 25.5, 'id' => 999];
>>> App\Models\SensorData::create($data); // Should ignore 'id'
```

### After Day 3 Fixes:
```bash
# Check for duplicated code
grep -rn "firstOrCreateNode" app/

# Test device service
php artisan tinker
>>> app(App\Services\DeviceService::class)->ensureDeviceExists(1, 'test');
```

---

## 📊 Progress Tracking

| Fix | Priority | Estimated Time | Status |
|-----|----------|----------------|--------|
| ExportService dead tables | P0 | 30 min | ⬜ |
| SensorDataRepository models | P0 | 45 min | ⬜ |
| WeatherDataRepository models | P0 | 30 min | ⬜ |
| Remove JsonBackup | P0 | 45 min | ⬜ |
| ValveLogsController Node | P0 | 30 min | ⬜ |
| Console.log removal | P1 | 20 min | ⬜ |
| Mass assignment fix | P1 | 15 min | ⬜ |
| Deduplicate auto-register | P2 | 60 min | ⬜ |
| Clean legacy comments | P2 | 30 min | ⬜ |

**Total Estimated Time:** 5-6 hours spread over 3 days

---

## 🆘 Rollback Plan

If issues occur after fixes:

```bash
# Rollback using git
git checkout HEAD -- app/Services/ExportService.php
git checkout HEAD -- app/Repositories/Eloquent/

# Restore JsonBackup if needed
git checkout HEAD -- app/Repositories/Contracts/JsonBackupRepositoryInterface.php
git checkout HEAD -- app/Repositories/Eloquent/EloquentJsonBackupRepository.php
```

---

**Document Updated:** 2026-07-14  
**Next Review:** After Day 3 fixes completed
