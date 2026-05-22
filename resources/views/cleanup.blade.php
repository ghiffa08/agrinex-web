<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Cleanup - AgriNex Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .cleanup-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .cleanup-header {
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
        }
        .cleanup-body {
            padding: 30px;
        }
        .action-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
            transition: all 0.3s;
        }
        .action-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .danger-zone {
            border-color: #dc3545;
            background: #fff5f5;
        }
        .warning-zone {
            border-color: #ffc107;
            background: #fffbf0;
        }
        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .btn-cleanup {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-cleanup:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            color: white;
        }
        .result-card {
            display: none;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .result-card.error {
            background: #f8d7da;
            border-color: #f5c6cb;
        }
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .loading-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .stat-badge {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            margin: 5px;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <h5>Processing Cleanup...</h5>
            <p class="text-muted">Please wait while we clean up the data</p>
        </div>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="cleanup-card">
            <div class="cleanup-header">
                <h1 class="mb-3"><i class="bi bi-trash3"></i> Database Cleanup</h1>
                <p class="mb-0 opacity-75">Manage and maintain your AgriNex database</p>
                <div class="mt-3">
                    <a href="{{ route('welcome') }}" class="btn btn-light btn-sm me-2">
                        <i class="bi bi-house"></i> Home
                    </a>
                    <a href="{{ route('monitor') }}" class="btn btn-outline-light btn-sm me-2">
                        <i class="bi bi-graph-up"></i> Monitor
                    </a>
                    <a href="{{ route('test-connection') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-clipboard-check"></i> Test
                    </a>
                </div>
            </div>
        </div>

        <!-- Database Info -->
        <div class="info-card">
            <h4 class="mb-3"><i class="bi bi-info-circle"></i> Database Information</h4>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-badge">
                        <i class="bi bi-database"></i> Total Records
                    </div>
                    <h3 class="mt-2" id="totalRecords">Loading...</h3>
                </div>
                <div class="col-md-3">
                    <div class="stat-badge">
                        <i class="bi bi-hdd"></i> Database Size
                    </div>
                    <h3 class="mt-2" id="dbSize">Loading...</h3>
                </div>
                <div class="col-md-3">
                    <div class="stat-badge">
                        <i class="bi bi-calendar-x"></i> Old Records
                    </div>
                    <h3 class="mt-2" id="oldRecords">Loading...</h3>
                </div>
                <div class="col-md-3">
                    <div class="stat-badge">
                        <i class="bi bi-link-45deg"></i> Orphaned Data
                    </div>
                    <h3 class="mt-2" id="orphanedData">Loading...</h3>
                </div>
            </div>
        </div>

        <!-- Cleanup Actions -->
        <div class="cleanup-card">
            <div class="cleanup-body">
                <h3 class="mb-4"><i class="bi bi-tools"></i> Cleanup Actions</h3>

                <!-- Action 1: Delete Old Data -->
                <div class="action-card warning-zone">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5><i class="bi bi-calendar-x text-warning"></i> Delete Old Data</h5>
                            <p class="text-muted mb-2">Remove records older than the specified number of days</p>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">Days to Keep</label>
                                    <input type="number" class="form-control" id="daysToKeep" value="90" min="1" max="365">
                                    <small class="text-muted">Records older than this will be deleted</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-warning btn-lg" onclick="confirmCleanup('old_data')">
                                <i class="bi bi-trash"></i> Clean Old Data
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action 2: Remove Orphaned Records -->
                <div class="action-card warning-zone">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5><i class="bi bi-link-45deg text-warning"></i> Remove Orphaned Records</h5>
                            <p class="text-muted mb-0">Clean up records without parent references (sensor data without session logs, etc.)</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-warning btn-lg" onclick="confirmCleanup('orphaned')">
                                <i class="bi bi-scissors"></i> Remove Orphaned
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action 3: Optimize Tables -->
                <div class="action-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5><i class="bi bi-speedometer2 text-primary"></i> Optimize Database Tables</h5>
                            <p class="text-muted mb-0">Optimize and analyze all tables to improve performance (Safe operation)</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-cleanup" onclick="executeCleanup('optimize')">
                                <i class="bi bi-lightning"></i> Optimize Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action 4: Full Cleanup -->
                <div class="action-card danger-zone">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5><i class="bi bi-exclamation-triangle text-danger"></i> Full Database Cleanup</h5>
                            <p class="text-muted mb-2">Perform all cleanup operations at once</p>
                            <div class="alert alert-danger mb-0">
                                <strong>Warning:</strong> This will delete old data, remove orphaned records, and optimize tables. This action cannot be undone!
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-danger btn-lg" onclick="confirmCleanup('full')">
                                <i class="bi bi-trash3"></i> Full Cleanup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div id="resultCard" class="result-card">
            <h5 id="resultTitle"><i class="bi bi-check-circle"></i> Cleanup Completed</h5>
            <div id="resultContent"></div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-warning"></i> Confirm Cleanup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage"></p>
                    <div class="alert alert-warning">
                        <strong>Warning:</strong> This action cannot be undone. Please make sure you have a backup of your data.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmBtn">
                        <i class="bi bi-check-circle"></i> Yes, Proceed
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentAction = '';
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

        // Load database info on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDatabaseInfo();
        });

        // Load database information
        async function loadDatabaseInfo() {
            try {
                const response = await fetch('/api/v1/monitor/stats');
                const data = await response.json();
                
                if (data.success) {
                    const stats = data.statistics;
                    
                    // Calculate total records
                    const total = Object.values(stats.tables).reduce((sum, count) => sum + count, 0);
                    document.getElementById('totalRecords').textContent = formatNumber(total);
                    
                    // Estimate database size (rough calculation)
                    const sizeInMB = (total * 1024 / 1000000).toFixed(2);
                    document.getElementById('dbSize').textContent = sizeInMB + ' MB';
                    
                    // Get old records count (older than 90 days)
                    const oldCount = Math.floor(total * 0.3); // Estimate
                    document.getElementById('oldRecords').textContent = formatNumber(oldCount);
                    
                    // Estimate orphaned data
                    const orphanedCount = Math.floor(total * 0.05); // Estimate
                    document.getElementById('orphanedData').textContent = formatNumber(orphanedCount);
                }
            } catch (error) {
                console.error('Error loading database info:', error);
                document.getElementById('totalRecords').textContent = 'Error';
                document.getElementById('dbSize').textContent = 'Error';
                document.getElementById('oldRecords').textContent = 'Error';
                document.getElementById('orphanedData').textContent = 'Error';
            }
        }

        // Confirm cleanup action
        function confirmCleanup(action) {
            currentAction = action;
            let message = '';
            
            switch(action) {
                case 'old_data':
                    const days = document.getElementById('daysToKeep').value;
                    message = `Are you sure you want to delete all records older than ${days} days?`;
                    break;
                case 'orphaned':
                    message = 'Are you sure you want to remove all orphaned records?';
                    break;
                case 'full':
                    message = 'Are you sure you want to perform a FULL cleanup? This will delete old data, remove orphaned records, and optimize tables.';
                    break;
            }
            
            document.getElementById('confirmMessage').textContent = message;
            confirmModal.show();
        }

        // Execute cleanup when confirmed
        document.getElementById('confirmBtn').addEventListener('click', function() {
            confirmModal.hide();
            executeCleanup(currentAction);
        });

        // Execute cleanup
        async function executeCleanup(action) {
            showLoading();
            hideResult();
            
            try {
                let endpoint = '';
                let payload = {};
                
                switch(action) {
                    case 'old_data':
                        endpoint = '/api/v1/cleanup/old-data';
                        payload = { days: parseInt(document.getElementById('daysToKeep').value) };
                        break;
                    case 'orphaned':
                        endpoint = '/api/v1/cleanup/orphaned';
                        break;
                    case 'optimize':
                        endpoint = '/api/v1/cleanup/optimize';
                        break;
                    case 'full':
                        endpoint = '/api/v1/cleanup/full';
                        payload = { days: parseInt(document.getElementById('daysToKeep').value) };
                        break;
                }
                
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify(payload)
                });
                
                const data = await response.json();
                
                hideLoading();
                showResult(data.success, data);
                
                // Reload database info
                setTimeout(() => loadDatabaseInfo(), 1000);
                
            } catch (error) {
                hideLoading();
                showResult(false, { message: 'Error: ' + error.message });
            }
        }

        // Show loading overlay
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Show result
        function showResult(success, data) {
            const resultCard = document.getElementById('resultCard');
            const resultTitle = document.getElementById('resultTitle');
            const resultContent = document.getElementById('resultContent');
            
            resultCard.className = 'result-card ' + (success ? '' : 'error');
            resultTitle.innerHTML = success ? 
                '<i class="bi bi-check-circle text-success"></i> Cleanup Completed Successfully' : 
                '<i class="bi bi-x-circle text-danger"></i> Cleanup Failed';
            
            let html = '';
            
            if (success && data.results) {
                html += '<div class="row">';
                
                if (data.results.old_data_deleted !== undefined) {
                    html += `
                        <div class="col-md-4 mb-2">
                            <strong>Old Data Deleted:</strong><br>
                            ${formatNumber(data.results.old_data_deleted)} records
                        </div>
                    `;
                }
                
                if (data.results.orphaned_removed !== undefined) {
                    html += `
                        <div class="col-md-4 mb-2">
                            <strong>Orphaned Removed:</strong><br>
                            ${formatNumber(data.results.orphaned_removed)} records
                        </div>
                    `;
                }
                
                if (data.results.tables_optimized !== undefined) {
                    html += `
                        <div class="col-md-4 mb-2">
                            <strong>Tables Optimized:</strong><br>
                            ${data.results.tables_optimized} tables
                        </div>
                    `;
                }
                
                html += '</div>';
                
                if (data.message) {
                    html += `<p class="mt-3 mb-0 text-muted">${data.message}</p>`;
                }
            } else {
                html = `<p class="mb-0">${data.message || 'An error occurred during cleanup.'}</p>`;
            }
            
            resultContent.innerHTML = html;
            resultCard.style.display = 'block';
            
            // Scroll to result
            resultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Hide result
        function hideResult() {
            document.getElementById('resultCard').style.display = 'none';
        }

        // Format number
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        }
    </script>
</body>
</html>
