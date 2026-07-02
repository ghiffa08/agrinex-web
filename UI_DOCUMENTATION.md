# AgriNex Web UI Documentation

**Last Updated:** 2025-10-18
**Related:** `API_DOCUMENTATION.md`, `README.md`

This document describes the AgriNex web dashboard (UI) — structure, pages, components, data flows, endpoints used by the frontend, assets (PWA), build & run instructions, troubleshooting tips, and contribution guidelines.

---

## Table of contents

1. Overview
2. Project layout (UI files)
3. Pages & routes
4. Components and responsibilities
5. Data flow and endpoints
6. Frontend build & dev workflow
7. PWA & static assets (manifest, sw.js)
8. Troubleshooting & common console messages
9. Debugging checklist (quick commands)
10. Contribution notes

---

## 1. Overview

The AgriNex Web UI is a Blade + Alpine.js front-end served by Laravel. It visualizes sensor data, irrigation sessions, device status, and weather forecasts. Charts are rendered using Chart.js and maps (if present) use Leaflet.

Key frontend technologies:
- Blade (server-rendered views)
- Alpine.js (reactive UI behavior)
- Chart.js (charts)
- Tailwind CSS (utility CSS; currently loaded via CDN in dev)
- PWA support (service worker + manifest)

Audience: Developers maintaining the dashboard, QA, and contributors adding UI features.

---

## 2. Project layout (UI files)

Important folders and files (relative to `agrinex-lara`):

- `resources/views/`
  - `welcome-modular-fix.blade.php` — main dashboard shell used in the app (contains includes for charts, maps, widgets)
  - `partials/` — shared partials (header, footer, scripts)
  - `components/weekly-tasks.blade.php` — weekly forecast and tasks widget
  - `reports/by-node.blade.php` — node-level report view (CSV export, tables)
  - `partials/dashboard-scripts.blade.php` — Alpine.js `dashboard()` implementation and Chart-FIX boot logic
- `public/`
  - `manifest.json` — PWA manifest (should be present; add if missing)
  - `sw.js` — service worker script (should be present; add if missing)
- `routes/`
  - `web.php` — web routes that return Blade views
  - `api.php` — API routes used by frontend for proxying external data (e.g., BMKG) or exposing app APIs

Notes:
- Blade partials are used to include scripts and widgets; be careful to not include `dashboard-scripts` more than once.
- The UI relies on API endpoints (see section 5) provided by the same Laravel app.

---

## 3. Pages & routes

Common UI pages (what they show) and the typical view file that renders them:

- Dashboard (home)
  - View: `resources/views/welcome-modular-fix.blade.php`
  - Shows: charts (temp, humidity, soil moisture, light, water), device list, weekly forecast widget, weekly tasks, current weather widget

- Node Report
  - View: `resources/views/reports/by-node.blade.php`
  - Shows: node metadata, sensor data table (paginated), irrigation counts, export links (CSV)

- Other pages
  - May include reports list, exports, monitoring pages under `resources/views` and referenced in `web.php` routes.

Routes:
- Web route for dashboard: configured in `routes/web.php` (look for route returning welcome-modular-fix or home)
- API routes used by frontend are under `routes/api.php` (see Data flow)

---

## 4. Components and responsibilities

High-level UI components:

- Header / Navigation (partial)
- Dashboard Charts (Chart-FIX): responsibility to initialize Chart.js instances and update them with API data
- Weekly Tasks (component): shows 24h forecast summary and week view based on BMKG proxy `GET /api/bmkg/forecast`
- Node Table / Reports: paginated table of `sensor_node_data` or `sensor_weather_data` and export button linking to export endpoints
- PWA registration block: registers service worker (only when `sw.js` present)

Important JS responsibilities (in `dashboard()` Alpine object):
- Load devices/
- Load tank & plan
- Build and update charts
- Load current weather and forecast via `loadBMKGDirect()` which calls `/api/bmkg/forecast`
- Build week view (`processForecast`) and current tasks

---

## 5. Data flow and endpoints

The UI is server-rendered but dynamically loads data via the Laravel API. Frontend expects specific endpoint shapes; don't change shapes without updating UI processing logic.

Key endpoints used by the frontend (see `API_DOCUMENTATION.md` for API-level details):
- `GET /api/v1/dashboard/devices` — device list (nodes)
- `GET /api/v1/dashboard/charts?days=7&type=all` — chart data for 7 days (Chart-FIX consumes keys: light_intensity, water_volume, soil_moisture, temperature, humidity)
- `GET /api/v1/dashboard/weather` — current weather (node 65)
- `GET /api/bmkg/forecast` — BMKG forecast proxy (returns JSON `{ entries: [...] }` expected by `processForecast`) — this route is implemented in `routes/api.php` to normalize BMKG responses
- Export routes (if present): e.g. `GET /reports/export?node=1&type=csv` or similar (check route names in `routes/web.php`)

Payload expectations (frontend):
- BMKG proxy must return `{