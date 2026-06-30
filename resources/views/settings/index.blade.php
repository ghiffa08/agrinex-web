@extends('layouts.app')

@section('title', 'Settings')

@section('page-title', 'System Settings')

@section('content')

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Settings Tabs -->
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button">
                <i class="bi bi-people me-1"></i> User Management
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button">
                <i class="bi bi-gear me-1"></i> System Info
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="treatments-tab" data-bs-toggle="tab" data-bs-target="#treatments" type="button">
                <i class="bi bi-water me-1"></i> Treatments
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications"
                type="button">
                <i class="bi bi-bell me-1"></i> Notifications
            </button>
        </li>
    </ul>

    <div class="tab-content" id="settingsTabContent">

        <!-- Users Tab -->
        <div class="tab-pane fade show active" id="users" role="tabpanel">
            <div class="card-custom">
                <div class="card-custom-header">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>User Management ({{ count($users) }})</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-plus-circle"></i> Add User
                    </button>
                </div>

                <div class="card-custom-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <strong>{{ $user->username }}</strong>
                                            @if ($user->id == auth()->id())
                                                <span class="badge bg-info badge-sm">You</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm"
                                                onclick="editUser({{ $user->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            @if ($user->id != auth()->id())
                                                <button class="btn btn-outline-danger btn-sm"
                                                    onclick="deleteUser({{ $user->id }}, '{{ $user->username }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="bi bi-inbox fs-1 text-muted"></i>
                                            <p class="text-muted mt-2">No users found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info Tab -->
        <div class="tab-pane fade" id="system" role="tabpanel">
            <div class="row g-4">

                <!-- Database Info -->
                <div class="col-md-6">
                    <div class="card-custom">
                        <div class="card-custom-header">
                            <h5 class="mb-0"><i class="bi bi-database me-2"></i>Database Information</h5>
                        </div>
                        <div class="card-custom-body">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Database Name:</th>
                                    <td>{{ config('database.connections.mysql.database') }}</td>
                                </tr>
                                <tr>
                                    <th>Host:</th>
                                    <td>{{ config('database.connections.mysql.host') }}</td>
                                </tr>
                                <tr>
                                    <th>Port:</th>
                                    <td>{{ config('database.connections.mysql.port') }}</td>
                                </tr>
                                <tr>
                                    <th>Connection:</th>
                                    <td><span class="badge bg-success">Connected</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Application Info -->
                <div class="col-md-6">
                    <div class="card-custom">
                        <div class="card-custom-header">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Application Information</h5>
                        </div>
                        <div class="card-custom-body">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">App Name:</th>
                                    <td>{{ config('app.name') }}</td>
                                </tr>
                                <tr>
                                    <th>Environment:</th>
                                    <td>
                                        <span
                                            class="badge bg-{{ config('app.env') == 'production' ? 'success' : 'warning' }}">
                                            {{ ucfirst(config('app.env')) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Debug Mode:</th>
                                    <td>
                                        <span class="badge bg-{{ config('app.debug') ? 'danger' : 'success' }}">
                                            {{ config('app.debug') ? 'ON' : 'OFF' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Laravel Version:</th>
                                    <td>{{ app()->version() }}</td>
                                </tr>
                                <tr>
                                    <th>PHP Version:</th>
                                    <td>{{ PHP_VERSION }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="col-md-12">
                    <div class="card-custom">
                        <div class="card-custom-header">
                            <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>System Statistics</h5>
                        </div>
                        <div class="card-custom-body">
                            <div class="row g-4">
                                <div class="col-md-3">
                                    <div class="text-center p-3 border rounded">
                                        <i class="bi bi-people fs-1 text-primary"></i>
                                        <h4 class="mt-2 mb-0">{{ App\Models\User::count() }}</h4>
                                        <small class="text-muted">Total Users</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 border rounded">
                                        <i class="bi bi-cpu fs-1 text-success"></i>
                                        <h4 class="mt-2 mb-0">{{ App\Models\Node::count() }}</h4>
                                        <small class="text-muted">Sensor Nodes</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 border rounded">
                                        <i class="bi bi-graph-up fs-1 text-info"></i>
                                        <h4 class="mt-2 mb-0">{{ App\Models\SensorNodeData::count() }}</h4>
                                        <small class="text-muted">Sensor Readings</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 border rounded">
                                        <i class="bi bi-water fs-1 text-primary"></i>
                                        <h4 class="mt-2 mb-0">{{ App\Models\IrrigateLog::count() }}</h4>
                                        <small class="text-muted">Irrigation Events</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Treatments Tab -->
        <div class="tab-pane fade" id="treatments" role="tabpanel">
            <div class="card-custom">
                <div class="card-custom-header">
                    <h5 class="mb-0"><i class="bi bi-water me-2"></i>Treatment Management</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTreatmentModal">
                        <i class="bi bi-plus-circle"></i> Add Treatment
                    </button>
                </div>
                <div class="card-custom-body">
                    <div class="row g-4">
                        @forelse ($treatments as $treatment)
                            <div class="col-md-4">
                                <div class="border rounded p-3"
                                    style="border-left: 4px solid {{ $treatment->color_code }} !important;">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="mb-0">{{ $treatment->treatment_name }}</h6>
                                        <div>
                                            <button class="btn btn-outline-primary btn-sm"
                                                onclick="editTreatment({{ $treatment->treatment_id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="text-muted">FC Target</small>
                                            <div><strong>{{ $treatment->target_fc_percent }}%</strong></div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Threshold</small>
                                            <div><strong>{{ $treatment->irrigation_threshold_percent }}%</strong></div>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <small class="text-muted">Description</small>
                                            <div><small>{{ $treatment->description }}</small></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="bi bi-water fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">No treatments configured yet</p>
                                    <small class="text-muted">Click "Add Treatment" button to create a new treatment configuration</small>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div class="tab-pane fade" id="notifications" role="tabpanel">
            <div class="card-custom">
                <div class="card-custom-header">
                    <h5 class="mb-0"><i class="bi bi-bell me-2"></i>Notification Settings</h5>
                    <button class="btn btn-success btn-sm" onclick="saveNotificationSettings()">
                        <i class="bi bi-save"></i> Save Settings
                    </button>
                </div>
                <div class="card-custom-body">
                    <form id="notificationSettingsForm">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <h6 class="mb-3">Alert Notifications</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="emailAlerts" checked>
                                        <label class="form-check-label" for="emailAlerts">
                                            Email notifications for alerts
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="criticalOnly">
                                        <label class="form-check-label" for="criticalOnly">
                                            Critical alerts only
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="smsAlerts">
                                        <label class="form-check-label" for="smsAlerts">
                                            SMS notifications (if available)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <h6 class="mb-3">System Notifications</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="irrigationNotif" checked>
                                        <label class="form-check-label" for="irrigationNotif">
                                            Irrigation events
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="sensorNotif" checked>
                                        <label class="form-check-label" for="sensorNotif">
                                            Sensor anomalies
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dailyReport">
                                        <label class="form-check-label" for="dailyReport">
                                            Daily summary reports
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="border rounded p-3">
                                    <h6 class="mb-3">Email Recipients</h6>
                                    <textarea class="form-control" rows="3"
                                        placeholder="Enter email addresses (comma-separated)&#10;example: user1@example.com, user2@example.com"></textarea>
                                    <small class="text-muted">Add email addresses to receive notifications</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('settings.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Username *</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone_number" placeholder="Optional">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" required minlength="6">
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role *</label>
                            <select class="form-select" name="role" required>
                                <option value="viewer">Viewer (Read-only access)</option>
                                <option value="operator" selected>Operator (Can edit data)</option>
                                <option value="admin">Admin (Full access)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-person-check me-2"></i>Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Username *</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="edit_phone_number" name="phone_number" placeholder="Optional">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" id="edit_password" name="password" minlength="6">
                            <small class="text-muted">Leave blank to keep current password. Minimum 6 characters if changing.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role *</label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="viewer">Viewer (Read-only access)</option>
                                <option value="operator">Operator (Can edit data)</option>
                                <option value="admin">Admin (Full access)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Treatment Modal -->
    <div class="modal fade" id="addTreatmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-water me-2"></i>Add New Treatment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addTreatmentForm">
                        <div class="mb-3">
                            <label class="form-label">Treatment Name *</label>
                            <input type="text" class="form-control" name="treatment_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Target FC (%)</label>
                                <input type="number" class="form-control" name="target_fc_percent" min="0"
                                    max="100" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Threshold (%)</label>
                                <input type="number" class="form-control" name="irrigation_threshold_percent"
                                    min="0" max="100" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color Code</label>
                            <input type="color" class="form-control form-control-color" name="color_code"
                                value="#0d6efd">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addTreatment()">
                        <i class="bi bi-save"></i> Add Treatment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Form (hidden) -->
    <form id="deleteUserForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function deleteUser(userId, username) {
            if (confirm(`Are you sure you want to delete user "${username}"?\n\nThis action cannot be undone.`)) {
                const form = document.getElementById('deleteUserForm');
                form.action = `/settings/users/${userId}`;
                form.submit();
            }
        }

        function editUser(userId) {
            // Get user data (you'll need to pass this via data attributes or AJAX)
            const users = @json($users);
            const user = users.find(u => u.id === userId);
            
            if (user) {
                // Populate the form
                document.getElementById('edit_username').value = user.username || '';
                document.getElementById('edit_full_name').value = user.full_name || '';
                document.getElementById('edit_email').value = user.email || '';
                document.getElementById('edit_phone_number').value = user.phone_number || '';
                document.getElementById('edit_role').value = user.role || 'viewer';
                document.getElementById('edit_password').value = '';
                
                // Set form action
                document.getElementById('editUserForm').action = `/settings/users/${userId}`;
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                modal.show();
            }
        }

        function editTreatment(treatmentId) {
            alert('Edit treatment - ID: ' + treatmentId);
        }

        function addTreatment() {
            const form = document.getElementById('addTreatmentForm');
            if (form.checkValidity()) {
                alert('Treatment added successfully!\nThis feature will be fully implemented with backend integration.');
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTreatmentModal'));
                modal.hide();
            } else {
                form.reportValidity();
            }
        }

        function saveNotificationSettings() {
            alert('Saving notification settings...\nThis feature will be fully implemented with backend integration.');
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

@endsection
