@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-user-shield me-2"></i>Employee Login Management
                </h1>
                <p class="page-subtitle">Manage employee login credentials for mobile app access</p>
            </div>

            <!-- Action Buttons -->
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    @if($employeesWithoutLogin->count() > 0)
                        <a href="{{ route('admin.employee-logins.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Generate Login Credentials
                        </a>
                    @endif
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-info me-3">{{ $employeeLogins->total() }} Total Logins</span>
                </div>
            </div>

            <!-- Existing Login Credentials -->
            @if($employeeLogins->count() > 0)
                <div class="section-card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-key me-2"></i>Active Login Credentials ({{ $employeeLogins->total() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-user me-1"></i>Employee</th>
                                        <th><i class="fas fa-envelope me-1"></i>Email</th>
                                        <th><i class="fas fa-id-card me-1"></i>Employee ID</th>
                                        <th><i class="fas fa-calendar-alt me-1"></i>Last Login</th>
                                        <th><i class="fas fa-shield-alt me-1"></i>Status</th>
                                        <th><i class="fas fa-cogs me-1"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employeeLogins as $login)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($login->employee && $login->employee->photo)
                                                        <img src="{{ asset('storage/' . $login->employee->photo) }}"
                                                             class="rounded-circle me-2"
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                             style="width: 40px; height: 40px; font-size: 16px; font-weight: bold;">
                                                            {{ strtoupper(substr($login->employee->first_name ?? 'N', 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="mb-0 fw-bold">{{ $login->employee->full_name ?? 'N/A' }}</p>
                                                        <small class="text-muted">{{ $login->employee->position ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-mono">{{ $login->email }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $login->employee->employee_id ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $login->formatted_last_login }}</span>
                                            </td>
                                            <td>
                                                {!! $login->status_badge !!}
                                                @if($login->login_attempts >= 3)
                                                    <br><small class="text-danger">Failed attempts: {{ $login->login_attempts }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.employee-logins.show', $login->id) }}"
                                                       class="btn btn-sm btn-outline-info"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.employee-logins.edit', $login->id) }}"
                                                       class="btn btn-sm btn-outline-warning"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-sm {{ $login->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} toggle-status"
                                                            data-id="{{ $login->id }}"
                                                            title="{{ $login->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas fa-{{ $login->is_active ? 'ban' : 'check' }}"></i>
                                                    </button>
                                                    <form action="{{ route('admin.employee-logins.reset-password', $login->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('POST')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-primary"
                                                                title="Reset Password"
                                                                onclick="return confirm('Are you sure you want to reset the password? This will generate a new random password.')">
                                                            <i class="fas fa-key"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($employeeLogins->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $employeeLogins->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="section-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-key fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Login Credentials Yet</h4>
                        <p class="text-muted mb-4">No employees have login credentials generated yet.</p>
                        @if($employeesWithoutLogin->count() > 0)
                            <a href="{{ route('admin.employee-logins.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Generate First Login Credentials
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Employees Without Login Credentials -->
            @if($employeesWithoutLogin->count() > 0)
                <div class="section-card mt-4">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Employees Without Login Credentials ({{ $employeesWithoutLogin->count() }})</h5>
                        <a href="{{ route('admin.employee-logins.create') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Generate for Selected
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($employeesWithoutLogin as $employee)
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card h-100 border-warning">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                @if($employee->photo)
                                                    <img src="{{ asset('storage/' . $employee->photo) }}"
                                                         class="rounded-circle"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 50px; font-size: 20px; font-weight: bold;">
                                                        {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $employee->full_name }}</h6>
                                                <p class="mb-1 text-muted">{{ $employee->position }}</p>
                                                <small class="text-muted">ID: {{ $employee->employee_id }}</small>
                                            </div>
                                            <div>
                                                <form action="{{ route('admin.employee-logins.generate-for-employee', $employee->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-warning"
                                                            title="Generate Login Credentials">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status functionality
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const loginId = this.getAttribute('data-id');
            const isActive = this.querySelector('i').classList.contains('fa-ban');

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
});
</script>

<style>
.fw-mono {
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection
