<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Connection - AgriNex Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .test-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .test-header {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .test-body {
            padding: 20px;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
        .status-warning {
            background: #fff3cd;
            color: #856404;
        }
        .table-custom {
            font-size: 0.9rem;
        }
        .extension-icon {
            font-size: 1.2rem;
            margin-right: 5px;
        }
        .nav-pills .nav-link {
            color: #667eea;
            font-weight: 500;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="test-card">
            <div class="test-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0"><i class="bi bi-clipboard-check"></i> Connection Test</h2>
                        <p class="mb-0 mt-2 opacity-75">AgriNex Laravel - System Testing & Diagnostics</p>
                    </div>
                    <a href="{{ route('welcome') }}" class="btn btn-light">
                        <i class="bi bi-house"></i> Home
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-pills mb-3" id="testTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="php-tab" data-bs-toggle="pill" data-bs-target="#php" type="button">
                    <i class="bi bi-code-slash"></i> PHP Environment
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="database-tab" data-bs-toggle="pill" data-bs-target="#database" type="button">
                    <i class="bi bi-database"></i> Database
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tables-tab" data-bs-toggle="pill" data-bs-target="#tables" type="button">
                    <i class="bi bi-table"></i> Tables
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="storage-tab" data-bs-toggle="pill" data-bs-target="#storage" type="button">
                    <i class="bi bi-folder"></i> Storage
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="config-tab" data-bs-toggle="pill" data-bs-target="#config" type="button">
                    <i class="bi bi-gear"></i> Configuration
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="testTabsContent">
            
            <!-- PHP Environment Tab -->
            <div class="tab-pane fade show active" id="php" role="tabpanel">
                <div class="test-card">
                    <div class="test-body">
                        <h4 class="mb-4"><i class="bi bi-code-slash text-primary"></i> PHP Environment</h4>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> <strong>PHP Version:</strong> {{ $tests['php']['version'] }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle"></i> <strong>Status:</strong> {{ ucfirst($tests['php']['status']) }}
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3">PHP Extensions</h5>
                        <div class="row">
                            @foreach($tests['php']['extensions'] as $extension => $loaded)
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    @if($loaded)
                                        <i class="bi bi-check-circle-fill text-success extension-icon"></i>
                                        <span class="text-success fw-bold">{{ $extension }}</span>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger extension-icon"></i>
                                        <span class="text-danger">{{ $extension }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Tab -->
            <div class="tab-pane fade" id="database" role="tabpanel">
                <div class="test-card">
                    <div class="test-body">
                        <h4 class="mb-4"><i class="bi bi-database text-primary"></i> Database Connection</h4>
                        
                        @if($tests['database']['status'] == 'success')
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill"></i> <strong>Connection Successful!</strong>
                            <p class="mb-0 mt-2">{{ $tests['database']['message'] }}</p>
                        </div>

                        <table class="table table-custom table-bordered mt-4">
                            <tbody>
                                <tr>
                                    <th width="200">Driver</th>
                                    <td><span class="badge bg-primary">{{ $tests['database']['driver'] }}</span></td>
                                </tr>
                                <tr>
                                    <th>Host</th>
                                    <td>{{ $tests['database']['host'] }}</td>
                                </tr>
                                <tr>
                                    <th>Database</th>
                                    <td><strong>{{ $tests['database']['database'] }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Server Version</th>
                                    <td>{{ $tests['database']['server_info'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                        @else
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle-fill"></i> <strong>Connection Failed!</strong>
                            <p class="mb-0 mt-2">{{ $tests['database']['message'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tables Tab -->
            <div class="tab-pane fade" id="tables" role="tabpanel">
                <div class="test-card">
                    <div class="test-body">
                        <h4 class="mb-4"><i class="bi bi-table text-primary"></i> Database Tables</h4>
                        
                        <div class="table-responsive">
                            <table class="table table-custom table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Table Name</th>
                                        <th width="100">Status</th>
                                        <th width="150">Records</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($tests['tables'] as $tableName => $tableInfo)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td><code>{{ $tableName }}</code></td>
                                        <td>
                                            @if($tableInfo['status'] == 'success')
                                                <span class="status-badge status-success">
                                                    <i class="bi bi-check-circle"></i> OK
                                                </span>
                                            @else
                                                <span class="status-badge status-error">
                                                    <i class="bi bi-x-circle"></i> Error
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($tableInfo['exists'])
                                                <strong>{{ number_format($tableInfo['records']) }}</strong> rows
                                            @else
                                                <span class="text-danger">{{ $tableInfo['message'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Storage Tab -->
            <div class="tab-pane fade" id="storage" role="tabpanel">
                <div class="test-card">
                    <div class="test-body">
                        <h4 class="mb-4"><i class="bi bi-folder text-primary"></i> Storage & Permissions</h4>
                        
                        <div class="table-responsive">
                            <table class="table table-custom table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Path</th>
                                        <th width="100">Exists</th>
                                        <th width="100">Writable</th>
                                        <th width="100">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tests['storage'] as $name => $info)
                                    <tr>
                                        <td>
                                            <strong>{{ $name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $info['path'] }}</small>
                                        </td>
                                        <td>
                                            @if($info['exists'])
                                                <i class="bi bi-check-circle-fill text-success"></i> Yes
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger"></i> No
                                            @endif
                                        </td>
                                        <td>
                                            @if($info['writable'])
                                                <i class="bi bi-check-circle-fill text-success"></i> Yes
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger"></i> No
                                            @endif
                                        </td>
                                        <td>
                                            @if($info['status'] == 'success')
                                                <span class="status-badge status-success">OK</span>
                                            @else
                                                <span class="status-badge status-warning">Warning</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration Tab -->
            <div class="tab-pane fade" id="config" role="tabpanel">
                <div class="test-card">
                    <div class="test-body">
                        <h4 class="mb-4"><i class="bi bi-gear text-primary"></i> Laravel Configuration</h4>
                        
                        <table class="table table-custom table-bordered">
                            <tbody>
                                <tr>
                                    <th width="200">Application Name</th>
                                    <td><strong>{{ $tests['config']['app_name'] }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Environment</th>
                                    <td>
                                        @if($tests['config']['app_env'] == 'production')
                                            <span class="badge bg-success">{{ strtoupper($tests['config']['app_env']) }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ strtoupper($tests['config']['app_env']) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Debug Mode</th>
                                    <td>
                                        @if($tests['config']['app_debug'])
                                            <span class="badge bg-warning">Enabled</span>
                                            <small class="text-danger ms-2">(Disable in production!)</small>
                                        @else
                                            <span class="badge bg-success">Disabled</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Application URL</th>
                                    <td><code>{{ $tests['config']['app_url'] }}</code></td>
                                </tr>
                                <tr>
                                    <th>Timezone</th>
                                    <td>{{ $tests['config']['timezone'] }}</td>
                                </tr>
                                <tr>
                                    <th>Locale</th>
                                    <td>{{ $tests['config']['locale'] }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="alert alert-info mt-4">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Note:</strong> Make sure to set <code>APP_DEBUG=false</code> and 
                            <code>APP_ENV=production</code> in your <code>.env</code> file when deploying to production.
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Actions -->
        <div class="test-card mt-4">
            <div class="test-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> Last checked: {{ now()->format('d M Y H:i:s') }}
                        </small>
                    </div>
                    <div>
                        <a href="/test-connection" class="btn btn-primary">
                            <i class="bi bi-arrow-clockwise"></i> Refresh Tests
                        </a>
                        <a href="/monitor" class="btn btn-success">
                            <i class="bi bi-graph-up"></i> Monitor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
