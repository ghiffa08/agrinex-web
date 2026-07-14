# Field Mapping Reference - Legacy to Normalized Schema

**Date:** 2026-07-14
**Purpose:** Field mapping untuk migrasi dari legacy tables ke normalized schema

## 1. Node Logs → Device Logs

### Legacy: `node_logs` (DELETED)
```
- sesi_id (integer)
- node_id (integer)
- rssi_dbm (integer)
- snr_db (float)
- signal_quality (string)
- status (string)
- waktu (timestamp)
- type_sesi (string)
- keterangan (text)
```

### New: `device_logs`
```
- id (bigserial)
- device_id (integer) ← node_id
- rssi_dbm (integer)
- snr_db (numeric)
- signal_quality (varchar)
- is_active (boolean)
- session_type (varchar) ← type_sesi
- session_ref_id (integer) ← sesi_id
- remarks (text) ← keterangan
- logged_at (timestamp) ← waktu
```

### Mapping
```php
[
    'device_id' => $data['node_id'],
    'rssi_dbm' => $data['rssi_dbm'],
    'snr_db' => $data['snr_db'],
    'signal_quality' => $data['signal_quality'] ?? null,
    'is_active' => $data['status'] === 'success',
    'session_type' => $data['type_sesi'] ?? 'getdata',
    'session_ref_id' => $data['sesi_id'],
    'remarks' => $data['keterangan'] ?? null,
    'logged_at' => $data['waktu'] ?? now(),
]
```

---

## 2. Irrigate Logs → Irrigation Logs

### Legacy: `irrigate_logs` (DELETED)
```
- id (integer)
- sesi_id_irrigate (integer)
- waktu_mulai (timestamp)
- waktu_akhir (timestamp)
- node_sukses (integer)
- node_gagal (integer)
- valve_on_akhir (integer)
```

### New: `irrigation_logs`
```
- id (bigserial)
- session_id (integer) ← sesi_id_irrigate
- started_at (timestamp) ← waktu_mulai
- ended_at (timestamp) ← waktu_akhir
- success_count (integer) ← node_sukses
- failed_count (integer) ← node_gagal
- valve_on_count (integer) ← valve_on_akhir
- created_at (timestamp)
- updated_at (timestamp)
```

### Mapping
```php
[
    'session_id' => $data['sesi_id_irrigate'],
    'started_at' => $data['waktu_mulai'] ?? now(),
    'ended_at' => $data['waktu_akhir'] ?? $data['waktu_selesai'] ?? null,
    'success_count' => $data['node_sukses'] ?? 0,
    'failed_count' => $data['node_gagal'] ?? 0,
    'valve_on_count' => $data['valve_on_akhir'] ?? 0,
]
```

---

## 3. Getdata Logs → Data Sessions

### Legacy: `getdata_logs` (DELETED)
```
- id (integer)
- sesi_id_getdata (integer)
- waktu_mulai (timestamp)
- waktu_selesai (timestamp)
- jumlah_node (integer)
- node_sukses (integer)
- node_gagal (integer)
- status (varchar)
- keterangan (text)
```

### New: `data_sessions`
```
- id (bigserial)
- session_id (integer) ← sesi_id_getdata
- started_at (timestamp) ← waktu_mulai
- ended_at (timestamp) ← waktu_selesai
- success_count (integer) ← node_sukses
- failed_count (integer) ← node_gagal
- created_at (timestamp)
- updated_at (timestamp)
```

### Mapping
```php
[
    'session_id' => $data['sesi_id_getdata'],
    'started_at' => $data['waktu_mulai'] ?? now(),
    'ended_at' => $data['waktu_selesai'] ?? null,
    'success_count' => $data['node_sukses'] ?? 0,
    'failed_count' => $data['node_gagal'] ?? 0,
]
```

---

## 4. Sensor Node Data → Sensor Data

### Legacy: `sensor_node_data` (DELETED)
```
- sesi_id_getdata (integer)
- node_id (integer)
- voltage_v (float)
- current_ma (float)
- power_mw (float)
- temp_c (float)
- soil_pct (float)
- soil_adc (integer)
- ts_counter (integer)
- received_at (timestamp)
```

### New: `sensor_data`
```
- id (bigserial)
- data_session_id (integer) ← sesi_id_getdata
- device_id (integer) ← node_id
- voltage_v (numeric)
- battery_pct (numeric)
- current_ma (numeric)
- power_mw (numeric)
- flow_rate (numeric)
- total_volume_l (numeric)
- temperature (numeric) ← temp_c
- soil_moisture (numeric) ← soil_pct
- soil_adc (integer)
- ai_valve_decision (varchar)
- adaptive_sleep_duration (integer)
- rssi (integer)
- ts_counter (integer)
- recorded_at (timestamp) ← received_at
```

### Mapping
```php
[
    'data_session_id' => $data['sesi_id_getdata'],
    'device_id' => $data['node_id'],
    'voltage_v' => $data['voltage_v'] ?? null,
    'battery_pct' => $data['battery_pct'] ?? null,
    'current_ma' => $data['current_ma'] ?? null,
    'power_mw' => $data['power_mw'] ?? null,
    'temperature' => $data['temp_c'] ?? null,
    'soil_moisture' => $data['soil_pct'] ?? null,
    'soil_adc' => $data['soil_adc'] ?? null,
    'ts_counter' => $data['ts_counter'] ?? null,
    'recorded_at' => $data['received_at'] ?? now(),
]
```

---

## 5. Sensor Weather Data → Weather Data

### Legacy: `sensor_weather_data` (DELETED)
```
- sesi_id_getdata (integer)
- node_id (integer)
- voltage (float)
- current (float)
- power (float)
- light (float)
- rain (float)
- rain_adc (integer)
- wind (float)
- wind_pulse (integer)
- humidity (float)
- temp_dht (float)
- ts_counter (integer)
- received_at (timestamp)
```

### New: `weather_data`
```
- id (bigserial)
- data_session_id (integer) ← sesi_id_getdata
- device_id (integer) ← node_id
- temp_c (numeric) ← temp_dht
- humidity_pct (numeric) ← humidity
- light_lux (numeric) ← light
- rain_mm (numeric) ← rain
- rain_adc (integer)
- wind_speed_ms (numeric) ← wind
- wind_pulse (integer)
- voltage_v (numeric) ← voltage
- current_ma (numeric) ← current
- power_mw (numeric) ← power
- ts_counter (integer)
- recorded_at (timestamp) ← received_at
```

### Mapping
```php
[
    'data_session_id' => $data['sesi_id_getdata'],
    'device_id' => $data['node_id'],
    'temp_c' => $data['temp_dht'] ?? null,
    'humidity_pct' => $data['humidity'] ?? null,
    'light_lux' => $data['light'] ?? null,
    'rain_mm' => $data['rain'] ?? null,
    'rain_adc' => $data['rain_adc'] ?? null,
    'wind_speed_ms' => $data['wind'] ?? null,
    'wind_pulse' => $data['wind_pulse'] ?? null,
    'voltage_v' => $data['voltage'] ?? null,
    'current_ma' => $data['current'] ?? $data['arus'] ?? null,
    'power_mw' => $data['power'] ?? null,
    'ts_counter' => $data['ts_counter'] ?? null,
    'recorded_at' => $data['received_at'] ?? now(),
]
```

---

## Quick Reference Table

| Legacy Table | New Table | Key Field Mapping |
|--------------|-----------|-------------------|
| `node_logs` | `device_logs` | `node_id` → `device_id`, `waktu` → `logged_at` |
| `irrigate_logs` | `irrigation_logs` | `sesi_id_irrigate` → `session_id`, `waktu_mulai` → `started_at` |
| `getdata_logs` | `data_sessions` | `sesi_id_getdata` → `session_id`, `waktu_mulai` → `started_at` |
| `sensor_node_data` | `sensor_data` | `node_id` → `device_id`, `received_at` → `recorded_at` |
| `sensor_weather_data` | `weather_data` | `node_id` → `device_id`, `temp_dht` → `temp_c` |

---

## Critical Notes

1. **Session ID mapping:**
   - Legacy: `sesi_id_getdata`, `sesi_id_irrigate`
   - New: `session_id` (unified)

2. **Timestamp fields:**
   - Legacy: `waktu`, `waktu_mulai`, `waktu_selesai`, `waktu_akhir`, `received_at`
   - New: `logged_at`, `started_at`, `ended_at`, `recorded_at` (consistent naming)

3. **Count fields:**
   - Legacy: `node_sukses`, `node_gagal`
   - New: `success_count`, `failed_count` (English, consistent)

4. **Boolean conversion:**
   - Legacy: `status` (string: 'success'/'failed')
   - New: `is_active` (boolean)

5. **Temperature fields:**
   - Legacy: `temp_c`, `temp_dht`
   - New: `temperature`, `temp_c` (normalized)

6. **Foreign key changes:**
   - Legacy: `node_id` references `node(id)`
   - New: `device_id` references `devices(id)`
