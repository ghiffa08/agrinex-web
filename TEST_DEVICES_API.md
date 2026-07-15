# TEST DEVICE & DETAIL API - AgriNex SmartDrip

## TEST 1: Dashboard Devices List
```bash
curl http://localhost:8000/api/v1/dashboard/devices
```

Expected: Array devices dengan data dari tabel devices + sensor_data terbaru

## TEST 2: Sleep History
```bash
curl http://localhost:8000/api/v1/devices/1/sleep-history?period=week
```

Expected: Riwayat deep sleep dari field `adaptive_sleep_duration` di sensor_data

## TEST 3: Battery History
```bash
curl http://localhost:8000/api/v1/devices/1/battery-history?period=week
```

Expected: Riwayat voltage dan battery_pct dari sensor_data

## TEST 4: Chart Data
```bash
curl http://localhost:8000/api/v1/devices/1/chart-data
```

Expected: Data chart temperature & soil_moisture 100 readings terakhir

## TEST 5: Open Browser
```
http://localhost:8000/devices
http://localhost:8000/node/1
```

Expected: Halaman devices list dan detail menampilkan data real dari database
