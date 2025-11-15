@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-user-shield me-2"></i>Employee Login Details
                </h1>
                <p class="page-subtitle">View and manage login credentials</p>
            </div>

            <!-- Employee & Login Info -->
            <div class="row">
                <!-- Employee Profile Card -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Employee Information</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($employeeLogin->employee && $employeeLogin->employee->photo)
                                <img src="{{ asset('storage/' . $employeeLogin->employee->photo) }}"
                                     class="rounded-circle mb-3"
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 100px; height: 100px; font-size: 40px; font-weight: bold;">
                                    {{ strtoupper(substr($employeeLogin->employee->first_name ?? 'N', 0, 1)) }}
                                </div>
                            @endif

                            <h5 class="mb-1">{{ $employeeLogin->employee->full_name ?? 'N/A' }}</h5>
                            <p class="text-muted mb-2">{{ $employeeLogin->employee->position ?? '' }}</p>
                            <span class="badge bg-secondary">ID: {{ $employeeLogin->employee->employee_id ?? 'N/A' }}</span>

                            <hr>

                            <div class="text-start">
                                <div class="mb-2">
                                    <small class="text-muted">Department</small>
                                    <div class="fw-bold">{{ $employeeLogin->employee->department ?? 'N/A' }}</div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Email</small>
                                    <div class="fw-bold">{{ $employeeLogin->employee->email ?? 'N/A' }}</div>
                                </div>
                                <div class="mb-0">
                                    <small class="text-muted">Contact</small>
                                    <div class="fw-bold">{{ $employeeLogin->employee->phone ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <hr>

                            <a href="{{ route('admin.employees.show', $employeeLogin->employee->id ?? 0) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-external-link-alt me-1"></i>View Full Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Login Credentials Card -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-key me-2"></i>Login Credentials</h5>
                            <div>
                                <a href="{{ route('admin.employee-logins.edit', $employeeLogin->id) }}"
                                   class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-envelope me-1"></i>Email Address:</strong>
                                    <div class="mt-1">
                                        <span class="badge bg-light text-dark fs-6 p-2 fw-mono">{{ $employeeLogin->email }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-calendar-alt me-1"></i>Created:</strong>
                                    <div class="mt-1">
                                        {{ $employeeLogin->created_at->format('M d, Y \a\t h:i A') }}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-shield-alt me-1"></i>Account Status:</strong>
                                    <div class="mt-1">
                                        {!! $employeeLogin->status_badge !!}
                                        @if($employeeLogin->login_attempts >= 3)
                                            <div class="mt-2">
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    Failed login attempts: {{ $employeeLogin->login_attempts }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong><i class="fas fa-clock me-1"></i>Last Login:</strong>
                                    <div class="mt-1">
                                        <span class="text-muted">{{ $employeeLogin->formatted_last_login }}</span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Password Information</h6>
                                <p class="mb-2">
                                    Passwords are stored securely using advanced encryption. For security reasons,
                                    the actual password is never displayed.
                                </p>
                                <p class="mb-0">
                                    If the employee has forgotten their password, you can reset it using the
                                    "Reset Password" button below.
                                </p>
                            </div>

                            <!-- Quick Actions -->
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <form action="{{ route('admin.employee-logins.reset-password', $employeeLogin->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to reset the password? A new random password will be generated.')">
                                        <i class="fas fa-key me-2"></i>Reset Password
                                    </button>
                                </form>

                                <button type="button"
                                        class="btn @if($employeeLogin->is_active) btn-outline-danger @else btn-outline-success @endif toggle-status"
                                        data-id="{{ $employeeLogin->id }}">
                                    <i class="fas fa-@if($employeeLogin->is_active) ban @else check @endif me-2"></i>
                                    @if($employeeLogin->is_active) Deactivate Account @else Activate Account @endif
                                </button>

                                <button type="button"
                                        class="btn btn-outline-danger delete-btn"
                                        data-id="{{ $employeeLogin->id }}">
                                    <i class="fas fa-trash me-2"></i>Delete Account
                                </button>
                            </div>

                            <!-- Security Notes -->
                            <div class="card border-warning">
                                <div class="card-header bg-warning">
                                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Security Notes</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="mb-0 small">
                                        <li>Make sure to communicate the login credentials securely to the employee</li>
                                        <li>Employees should change their password after first login for security</li>
                                        <li>Account will be locked after 5 failed login attempts</li>
                                        <li>Monitor login activity and last login dates regularly</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login History (Future Enhancement) -->
            <div class="section-card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Login History</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Login history tracking</strong> - This feature can be implemented in future updates to track all login attempts and activities.
                    </div>
                    <div class="text-center py-3">
                        <p class="text-muted mb-2">No login history available yet</p>
                        <small class="text-muted">
                            Last login: {{ $employeeLogin->formatted_last_login }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="d-flex justify-content-start mt-4">
                <a href="{{ route('admin.employee-logins.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Login Management
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Delete Confirmation Form -->
<form id="delete-form" action="{{ route('admin.employee-logins.destroy', $employeeLogin->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete(loginId) {
    if (confirm('Are you sure you want to delete this login account? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Toggle status functionality
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const loginId = this.getAttribute('data-id');
            const isActive = button.querySelector('i').classList.contains('fa-ban');

            if (confirm(`Are you sure you want to ${isActive ? 'deactivate' : 'activate'} this login account?`)) {
                // Create a form to submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/employee-logins/${loginId}/toggle-status`;

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfToken);

                // Add method field for PUT request
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Delete functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const loginId = this.getAttribute('data-id');
            confirmDelete(loginId);
        });
    });
});
</script>

<style>
.fw-mono {
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #4eb4e6 0%, #3a9bc7 100%) !important;
}
</style>
@endsection
