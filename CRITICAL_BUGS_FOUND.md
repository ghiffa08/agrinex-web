# CRITICAL BUGS - Data Ingestion akan GAGAL!

**Discovered:** 2026-07-14 01:33 UTC
**Severity:** CRITICAL - BLOCKING
**Impact:** ESP32 tidak bisa kirim data ke backend

## Problem

Setelah normalisasi database, Services dan Repositories masih mencoba INSERT ke tabel yang SUDAH DIHAPUS!

## Affected Code

### 1. SensorDataService.php (Line 52)
```php
$this->sessionRepo->createGetdataLog(array_merge($log, [...]));
```
↓ calls ↓

### 2. EloquentSessionRepository.php (Line 18-27)
```php
public function createGetdataLog(array $data) {
    return DataSession::create([...]);  // OK, uses new table
}
```
**Status:** ✅ SUDAH BENAR (uses `data_sessions` table)

---

### 3. SensorDataService.php (Line 109)
```php
$this->logRepo->createNodeLog($nodeLog);
```
↓ calls ↓

### 4. EloquentLogRepository.php (Line 16-19)
```php
public function createNodeLog(array $data) {
    return NodeLog::create($data);  // ❌ FATAL!
}
```
**Status:** ❌ BROKEN - `NodeLog` model menggunakan tabel `node_logs` yang SUDAH DIHAPUS!

---

### 5. IrrigationService.php (Line 49)
```php
$this->irrigationRepo->createIrrigateLog($mappedLog);
```
↓ calls ↓

### 6. EloquentIrrigationRepository.php (Line 18-21)
```php
public function createIrrigateLog(array $data) {
    return IrrigateLog::create($data);  // ❌ FATAL!
}
```
**Status:** ❌ BROKEN - `IrrigateLog` model menggunakan tabel `irrigate_logs` yang SUDAH DIHAPUS!

---

### 7. IrrigationService.php (Line 67)
```php
$this->logRepo->createNodeLog($nodeLog);
```
Same issue as #4 above.

---

## Impact Analysis

**When ESP32 sends telemetry:**
1. ✅ DataSession record created (OK)
2. ✅ SensorData records created (OK)
3. ✅ WeatherData records created (OK)
4. ❌ NodeLog INSERT fails → **500 Error**
5. ❌ Transaction rollback → **No data saved!**

**When ESP32 sends irrigation event:**
1. ❌ IrrigateLog INSERT fails → **500 Error**
2. ❌ Transaction rollback → **Irrigation not recorded!**
3. ❌ NodeLog INSERT fails → **Double failure**

## Root Cause

Database normalization migration menghapus tabel legacy, tapi:
- Legacy Model files masih exist
- Repository masih reference legacy models
- Services masih call legacy methods

## Tabel yang Sudah Dihapus

```sql
DROP TABLE IF EXISTS node_logs CASCADE;
DROP TABLE IF EXISTS irrigate_logs CASCADE;
DROP TABLE IF EXISTS getdata_logs CASCADE;
DROP TABLE IF EXISTS sensor_node_data CASCADE;
DROP TABLE IF EXISTS sensor_weather_data CASCADE;
DROP TABLE IF EXISTS json_backup CASCADE;
DROP TABLE IF EXISTS push_logs CASCADE;
DROP TABLE IF EXISTS node CASCADE;
DROP TABLE IF EXISTS data_sync_status CASCADE;
```

## Schema Baru (Normalized)

```sql
-- Device logs (replaces node_logs)
CREATE TABLE device_logs (
    id BIGSERIAL PRIMARY KEY,
    device_id INTEGER NOT NULL,
    data_session_id INTEGER,
    rssi_dbm INTEGER,
    snr_db NUMERIC(5,2),
    signal_quality VARCHAR(20),
    status VARCHAR(50),
    logged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (device_id) REFERENCES devices(id) ON DELETE CASCADE
);

-- Irrigation logs (replaces irrigate_logs)
CREATE TABLE irrigation_logs (
    id BIGSERIAL PRIMARY KEY,
    device_id INTEGER NOT NULL,
    started_at TIMESTAMP NOT NULL,
    ended_at TIMESTAMP,
    target_duration_minutes INTEGER,
    actual_duration_minutes INTEGER,
    target_volume_liters NUMERIC(10,2),
    actual_volume_liters NUMERIC(10,2),
    status VARCHAR(50) DEFAULT 'pending',
    notes TEXT,
    FOREIGN KEY (device_id) REFERENCES devices(id) ON DELETE CASCADE
);
```

## Fix Required

### Option 1: Delete Legacy Models + Update Repositories (RECOMMENDED)
1. Delete 7 legacy model files
2. Update `EloquentLogRepository::createNodeLog()` → use `DeviceLog::create()`
3. Update `EloquentIrrigationRepository::createIrrigateLog()` → use `IrrigationLog::create()`
4. Update Services to map field names correctly

### Option 2: Keep Compatibility Layer (NOT RECOMMENDED)
- Keep legacy method names but redirect to new models
- Risk: confusing field mapping, hard to maintain

## Action Plan

**IMMEDIATE (before ESP32 sends next data):**
1. ✅ Document all legacy references
2. ⏳ Wait for comprehensive audit report
3. ⏳ Fix Repository methods to use new models
4. ⏳ Update field mapping in Services
5. ⏳ Test with mock ESP32 payload
6. ⏳ Delete dead legacy models

**Priority:** P0 - BLOCKING
**ETA:** 30-60 minutes

---

## Legacy Models to Delete (After Fix)

1. `app/Models/Node.php` → replaced by `Device.php`
2. `app/Models/NodeLog.php` → replaced by `DeviceLog.php`
3. `app/Models/GetdataLog.php` → replaced by `DataSession.php`
4. `app/Models/IrrigateLog.php` → replaced by `IrrigationLog.php`
5. `app/Models/JsonBackup.php` → no replacement (removed feature)
6. `app/Models/SensorNodeData.php` → replaced by `SensorData.php`
7. `app/Models/SensorWeatherData.php` → replaced by `WeatherData.php`

## Admin Controllers to Delete

1. `app/Http/Controllers/Admin/GetdataLogsController.php`
2. `app/Http/Controllers/Admin/IrrigateLogsController.php`
3. `app/Http/Controllers/Admin/JsonBackupController.php`
4. `app/Http/Controllers/Admin/NodeLogsController.php`
5. `app/Http/Controllers/Admin/SensorNodeDataController.php`
6. `app/Http/Controllers/Admin/ValveLogsController.php` (check if still needed)
7. `app/Http/Controllers/Admin/WeatherDataController.php` (check if still needed)

---

**Next Step:** Wait for comprehensive audit report, then implement fixes.
