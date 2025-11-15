@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>Edit Login Credentials
                </h1>
                <p class="page-subtitle">Modify login credentials for employee access</p>
            </div>

            <!-- Employee Info -->
            <div class="section-card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Employee Information</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                @if($employeeLogin->employee && $employeeLogin->employee->photo)
                                    <img src="{{ asset('storage/' . $employeeLogin->employee->photo) }}"
                                         class="rounded-circle me-3"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width: 60px; height: 60px; font-size: 24px; font-weight: bold;">
                                        {{ strtoupper(substr($employeeLogin->employee->first_name ?? 'N', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $employeeLogin->employee->full_name ?? 'N/A' }}</h5>
                                    <p class="mb-1 text-muted">{{ $employeeLogin->employee->position ?? '' }}</p>
                                    <small class="text-muted">Employee ID: {{ $employeeLogin->employee->employee_id ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.employees.show', $employeeLogin->employee->id ?? 0) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-external-link-alt me-1"></i>View Employee Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Login Credentials</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.employee-logins.update', $employeeLogin->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Security Note:</strong> Make sure to choose a secure password. Passwords are encrypted and cannot be viewed once saved.
                        </div>

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $employeeLogin->email) }}"
                                   required>
                            <div class="form-text">This email will be used as the login ID for mobile app access</div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>New Password
                            </label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   minlength="6">
                            <div class="form-text">
                                Leave blank to keep the current password. Minimum 6 characters required.
                                <button type="button" class="btn btn-link btn-sm p-0 ms-2" onclick="generatePassword()">
                                    <i class="fas fa-magic me-1"></i>Generate Strong Password
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-1"></i>Confirm New Password
                            </label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation">
                            <div class="form-text">Re-enter the new password to confirm</div>
                        </div>

                        <!-- Account Status -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-shield-alt me-1"></i>Account Status <span class="text-danger">*</span>
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="is_active"
                                               id="active"
                                               value="1"
                                               {{ old('is_active', $employeeLogin->is_active ? 1 : 0) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">
                                            <span class="badge bg-success me-2">Active</span>
                                            Account is active and can log in
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="is_active"
                                               id="inactive"
                                               value="0"
                                               {{ old('is_active', $employeeLogin->is_active ? 1 : 0) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inactive">
                                            <span class="badge bg-danger me-2">Inactive</span>
                                            Account is deactivated and cannot log in
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Account Statistics -->
                        <div class="card border-info mb-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Account Statistics</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            <div class="h4 text-primary mb-1">{{ $employeeLogin->login_attempts }}</div>
                                            <small class="text-muted">Failed Login Attempts</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            <div class="h4 text-success mb-1">{{ $employeeLogin->formatted_last_login }}</div>
                                            <small class="text-muted">Last Login</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            <div class="h4 text-info mb-1">{{ $employeeLogin->created_at->format('M Y') }}</div>
                                            <small class="text-muted">Account Created</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin.employee-logins.show', $employeeLogin->id) }}"
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-eye me-2"></i>Cancel & View Details
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.employee-logins.reset-password', $employeeLogin->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to reset the password? A new random password will be generated.')">
                                        <i class="fas fa-key me-2"></i>Reset Password Instead
                                    </button>
                                </form>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Credentials
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function generatePassword() {
    const passwordChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = '';

    for (let i = 0; i < 12; i++) {
        password += passwordChars.charAt(Math.floor(Math.random() * passwordChars.length));
    }

    document.getElementById('password').value = password;
    document.getElementById('password_confirmation').value = password;

    // Add a brief highlight effect
    const passwordField = document.getElementById('password');
    passwordField.style.backgroundColor = '#e8f5e8';
    setTimeout(() => {
        passwordField.style.backgroundColor = '';
    }, 1000);
}

// Password confirmation validation
document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('password_confirmation');

    if (passwordField && confirmField) {
        function validatePasswordConfirmation() {
            if (passwordField.value !== confirmField.value) {
                confirmField.setCustomValidity('Passwords do not match');
            } else {
                confirmField.setCustomValidity('');
            }
        }

        passwordField.addEventListener('input', validatePasswordConfirmation);
        confirmField.addEventListener('input', validatePasswordConfirmation);
    }
});
</script>

<style>
.form-check-label {
    cursor: pointer;
}

.btn-link {
    font-size: 0.875rem;
    text-decoration: none;
}

.btn-link:hover {
    text-decoration: underline;
}

.card.border-info .card-header {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%) !important;
}
</style>
@endsection
