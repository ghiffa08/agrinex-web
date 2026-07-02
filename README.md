# AgriNex Laravel API v1.3

**Smart Agriculture IoT System** - RESTful API untuk monitoring dan kontrol sistem irigasi pintar dengan Raspberry Pi.

## 🚀 Quick Start

```bash
# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database di .env
DB_DATABASE=agri_lara
DB_USERNAME=your_username
DB_PASSWORD=your_password

# AgriNex — Smart Agriculture IoT Platform

This repository contains the AgriNex Laravel backend and the Blade + Alpine.js web dashboard used to monitor a multi-node IoT irrigation system (Nodes 1–12 + Node 65 weather station). It provides ingestion endpoints for sensor data and valve logs, chart data for the dashboard, and utilities for exports and debugging.

## What’s included

- `agrinex-lara/` — Laravel application (controllers, services, models, views)
- `API_DOCUMENTATION.md` — Full API reference (endpoints, payloads, examples)
- `UI_DOCUMENTATION.md` — Web UI documentation (pages, components, data flow, troubleshooting)
- `sensordata.json`, `valveon.json`, `valveoff.json` — example payloads for testing

## Quick start (development)

1. Install dependencies

```bash
composer install
npm ci   # optional, if you are using frontend build tools
```

2. Configure `.env`

```bash
cp .env.example .env
php artisan key:generate
# Update DB_ env vars: DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

3. Run migrations (if you have a local DB)

```bash
php artisan migrate --seed
```

4. Serve the app

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Open the dashboard at: http://127.0.0.1:8000

## Important developer notes

- The UI is primarily server-rendered Blade templates with dynamic behaviour handled by Alpine.js and Chart.js. The main dashboard view is `resources/views/welcome-modular-fix.blade.php`.
- Frontend expects certain API shapes (see `API_DOCUMENTATION.md`). If you change the API payload shape, update `resources/views/partials/dashboard-scripts.blade.php` (`dashboard()` Alpine object) accordingly.
- BMKG forecast is proxied by the backend route `/api/bmkg/forecast` and normalized into an `entries` array consumed by the UI.

## PWA and static assets

- The dashboard includes optional PWA registration. Ensure the following files exist in `public/` to avoid 404s and console errors:
  - `public/manifest.json` — web manifest
  - `public/sw.js` — service worker (can be a minimal placeholder if you do not use PWA features)

If you do not want PWA registration, remove or guard the `navigator.serviceWorker.register(...)` block in the frontend script.

## Running basic tests / API checks

Check health:

```bash
curl http://127.0.0.1:8000/api/v1/ingest/health
```

Check BMKG proxy (should return `{ "entries": [...] }`):

```bash
curl http://127.0.0.1:8000/api/bmkg/forecast | jq '.entries | length'
```

Get dashboard chart data (7 days):

```bash
curl "http://127.0.0.1:8000/api/v1/dashboard/charts?days=7&type=all" | jq '.data'
```

## Troubleshooting quick checklist

- Console shows `manifest.json` or `sw.js` 404 → add minimal files to `public/` or disable PWA registration.
- Console shows repeated forecast processing / duplicate logs → check that `partials/dashboard-scripts.blade.php` is not included more than once in your layout or page.
- Charts say "No 24h/30d data to render" → verify chart API responds with arrays under `data.*.values` and `data.*.labels` and that `data_points` > 0.
- SQL unknown-column errors (e.g. `node_id` vs `id_node`) → confirm DB schema and use model relationships; server code already updated to use relationships where possible.

## Links

- API reference: `API_DOCUMENTATION.md`
- UI reference: `UI_DOCUMENTATION.md`

## License

This project uses open-source components (Laravel, Chart.js, Alpine.js). The repository license follows the project's chosen license (check top-level LICENSE if present).

---

If you want, I can commit this README update and push it to `origin/main`. Let me know and I will run the commit/push for you.
# Import Postman collection
File > Import > "AgriNex Laravel API V1.3.postman_collection.json"

# Set environment variables
base_url: http://localhost:8000/api
sesi_id: 999

# Test endpoints
1. Health Check
2. Submit Sensor Data
3. Get Sensor Data
4. Submit Irrigation Data
5. Export Data
```

### Test dengan cURL

```bash
# Health check
curl http://localhost:8000/api/health

# Submit sensor data
curl -X POST http://localhost:8000/api/v1/sensor-data \
  -H "Content-Type: application/json" \
  -H "X-Sesi-ID: 999" \
  -d @sensordata.json

# Get sensor data
curl "http://localhost:8000/api/v1/sensor-data?limit=10"

# Export data to JSON
curl "http://localhost:8000/api/v1/export?format=json&table=sensor_node_data&limit=10"
```

## 🛠️ Tech Stack

- **Laravel** 11.x
- **PHP** 8.2+
- **MySQL** 5.7+
- **REST API** JSON format

## 📦 Key Features

✅ Real-time sensor data ingestion  
✅ Automatic JSON backup system  
✅ Irrigation control & monitoring  
✅ Data export (JSON, CSV, SQL)  
