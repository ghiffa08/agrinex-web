<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor - AgriNex Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .monitor-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .monitor-header {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .monitor-body {
            padding: 20px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .log-item {
            border-left: 4px solid #667eea;
            padding-left: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .log-time {
            color: #6c757d;
            font-size: 0.85rem;
        }
        .refresh-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: all 0.3s;
            z-index: 1000;
        }
        .refresh-btn:hover {
            transform: scale(1.1) rotate(180deg);
        }
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .node-status {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            background: #f8f9fa;
        }
        .status-online {
            border-left: 4px solid #28a745;
        }
        .status-offline {
            border-left: 4px solid #dc3545;
        }
        .auto-refresh-info {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.95);
            padding: 10px 20px;
            border-radius: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 999;
        }
    </style>
</head>
<body>
    <!-- Auto Refresh Info -->
    <div class="auto-refresh-info">
        <i class="bi bi-arrow-clockwise text-primary"></i>
        <small class="text-muted">Auto-refresh in <span id="countdown">30</span>s</small>
    </div>

    <div class="container-fluid">
        <!-- Header -->
        <div class="monitor-card">
            <div class="monitor-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0"><i class="bi bi-graph-up"></i> System Monitor</h2>
                        <p class="mb-0 mt-2 opacity-75">AgriNex IoT Real-time System Monitoring</p>
                    </div>
                    <div>
                        <a href="{{ route('welcome') }}" class="btn btn-light me-2">
                            <i class="bi bi-house"></i> Home
                        </a>
                        <a href="{{ route('test-connection') }}" class="btn btn-outline-light">
                            <i class="bi bi-clipboard-check"></i> Test
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p class="mt-3 text-white">Loading data...</p>
        </div>

        <!-- Statistics Grid -->
        <div id="stats-container" class="row">
            <!-- Stats will be loaded here -->
        </div>

        <!-- Two Column Layout -->
        <div class="row">
            <!-- Left Column: Recent Logs -->
            <div class="col-lg-6">
                <div class="monitor-card">
                    <div class="monitor-body">
                        <h4 class="mb-4">
                            <i class="bi bi-journal-text text-primary"></i> Recent Activity
                        </h4>
                        <div id="recent-logs">
                            <!-- Logs will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Node Status -->
            <div class="col-lg-6">
                <div class="monitor-card">
                    <div class="monitor-body">
                        <h4 class="mb-4">
                            <i class="bi bi-cpu text-success"></i> Node Status
                        </h4>
                        <div id="node-status">
                            <!-- Node status will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="monitor-card">
            <div class="monitor-body">
                <h4 class="mb-4">
                    <i class="bi bi-heart-pulse text-danger"></i> System Health
                </h4>
                <div id="system-health">
                    <!-- Health checks will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Refresh Button -->
    <button class="refresh-btn" onclick="loadAllData()" title="Refresh Data">
        <i class="bi bi-arrow-clockwise"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let countdownTimer;
        let countdown = 30;

        // Load all monitoring data
        async function loadAllData() {
            document.getElementById('loading').style.display = 'block';
            
            try {
                // Load statistics
                await loadStatistics();
                
                // Load recent logs
                await loadRecentLogs();
                
                // Load node status
                await loadNodeStatus();
                
                // Load system health
                await loadSystemHealth();
                
                // Reset countdown
                countdown = 30;
                
            } catch (error) {
                console.error('Error loading data:', error);
                showError('Failed to load monitoring data');
            } finally {
                document.getElementById('loading').style.display = 'none';
            }
        }

        // Load statistics
        async function loadStatistics() {
            try {
                const response = await fetch('/api/v1/monitor/stats');
                const data = await response.json();
                
                if (data.success) {
                    const stats = data.statistics;
                    const container = document.getElementById('stats-container');
                    
                    container.innerHTML = `
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-value">${formatNumber(stats.tables.getdata_logs)}</div>
                                        <div class="stat-label">GetData Sessions</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-cloud-download"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-value">${formatNumber(stats.tables.irrigate_logs)}</div>
                                        <div class="stat-label">Irrigation Sessions</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-droplet"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-value">${formatNumber(stats.tables.sensor_node_data)}</div>
                                        <div class="stat-label">Sensor Readings</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-thermometer-half"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-value">${formatNumber(stats.today.sensor_readings)}</div>
                                        <div class="stat-label">Today's Readings</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        // Load recent logs
        async function loadRecentLogs() {
            try {
                const response = await fetch('/api/v1/monitor/logs?type=all&limit=10');
                const data = await response.json();
                
                if (data.success) {
                    const container = document.getElementById('recent-logs');
                    let html = '';
                    
                    // GetData Logs
                    if (data.logs.getdata && data.logs.getdata.length > 0) {
                        html += '<h6 class="text-primary mb-3"><i class="bi bi-cloud-download"></i> Recent GetData Sessions</h6>';
                        data.logs.getdata.slice(0, 5).forEach(log => {
                            html += `
                                <div class="log-item">
                                    <div class="d-flex justify-content-between">
                                        <strong>Session #${log.sesi_id_getdata}</strong>
                                        <span class="badge bg-${log.status === 'completed' ? 'success' : 'warning'}">
                                            ${log.status}
                                        </span>
                                    </div>
                                    <div class="log-time">
                                        <i class="bi bi-clock"></i> ${formatDateTime(log.created_at)}
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            Nodes: ${log.node_sukses}/${log.jumlah_node} success
                                        </small>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    
                    // Irrigate Logs
                    if (data.logs.irrigate && data.logs.irrigate.length > 0) {
                        html += '<h6 class="text-success mb-3 mt-4"><i class="bi bi-droplet"></i> Recent Irrigation Sessions</h6>';
                        data.logs.irrigate.slice(0, 5).forEach(log => {
                            html += `
                                <div class="log-item">
                                    <div class="d-flex justify-content-between">
                                        <strong>Session #${log.sesi_id_irrigate}</strong>
                                        <span class="badge bg-${log.status === 'completed' ? 'success' : 'warning'}">
                                            ${log.status}
                                        </span>
                                    </div>
                                    <div class="log-time">
                                        <i class="bi bi-clock"></i> ${formatDateTime(log.created_at)}
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            Valves: ${log.valve_sukses}/${log.jumlah_valve} success
                                        </small>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    
                    container.innerHTML = html || '<p class="text-muted">No recent activity</p>';
                }
            } catch (error) {
                console.error('Error loading logs:', error);
            }
        }

        // Load node status
        async function loadNodeStatus() {
            try {
                const response = await fetch('/api/v1/monitor/nodes');
                const data = await response.json();
                
                if (data.success) {
                    const container = document.getElementById('node-status');
                    let html = '';
                    
                    if (data.nodes && data.nodes.length > 0) {
                        data.nodes.forEach(node => {
                            const isOnline = isNodeOnline(node.last_seen);
                            html += `
                                <div class="node-status ${isOnline ? 'status-online' : 'status-offline'}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Node ${node.node_id}</strong>
                                            <br>
                                            <small class="text-muted">
                                                Last seen: ${formatDateTime(node.last_seen)}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <div><small>Temp: ${node.avg_temperature ? node.avg_temperature.toFixed(1) : 'N/A'}°C</small></div>
                                            <div><small>Soil: ${node.avg_soil_moisture ? node.avg_soil_moisture.toFixed(1) : 'N/A'}%</small></div>
                                            <div><small>Battery: ${node.avg_voltage ? node.avg_voltage.toFixed(2) : 'N/A'}V</small></div>
                                        </div>
                                        <div>
                                            <span class="badge bg-${isOnline ? 'success' : 'secondary'}">
                                                ${isOnline ? 'Online' : 'Offline'}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = '<p class="text-muted">No node data available</p>';
                    }
                    
                    container.innerHTML = html;
                }
            } catch (error) {
                console.error('Error loading node status:', error);
            }
        }

        // Load system health
        async function loadSystemHealth() {
            try {
                const response = await fetch('/api/v1/monitor/health');
                const data = await response.json();
                
                if (data.success) {
                    const health = data.health;
                    const container = document.getElementById('system-health');
                    
                    let html = `
                        <div class="alert alert-${health.status === 'healthy' ? 'success' : 'danger'}">
                            <h5><i class="bi bi-${health.status === 'healthy' ? 'check-circle' : 'x-circle'}"></i> 
                            System Status: ${health.status.toUpperCase()}</h5>
                        </div>
                        <div class="row">
                    `;
                    
                    for (const [key, check] of Object.entries(health.checks)) {
                        html += `
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6><i class="bi bi-${check.status === 'ok' ? 'check-circle-fill text-success' : 'x-circle-fill text-danger'}"></i> 
                                        ${key.charAt(0).toUpperCase() + key.slice(1)}</h6>
                                        <small class="text-muted">${check.message || 'OK'}</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    
                    html += '</div>';
                    container.innerHTML = html;
                }
            } catch (error) {
                console.error('Error loading health:', error);
            }
        }

        // Utility functions
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        }

        function formatDateTime(dateStr) {
            if (!dateStr) return 'N/A';
            const date = new Date(dateStr);
            return date.toLocaleString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function isNodeOnline(lastSeen) {
            if (!lastSeen) return false;
            const lastSeenDate = new Date(lastSeen);
            const now = new Date();
            const diffMinutes = (now - lastSeenDate) / 1000 / 60;
            return diffMinutes < 60; // Online if last seen within 60 minutes
        }

        function showError(message) {
            // Simple error display
            console.error(message);
        }

        // Countdown timer
        function startCountdown() {
            countdownTimer = setInterval(() => {
                countdown--;
                document.getElementById('countdown').textContent = countdown;
                
                if (countdown <= 0) {
                    loadAllData();
                }
            }, 1000);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAllData();
            startCountdown();
        });
    </script>
</body>
</html>
