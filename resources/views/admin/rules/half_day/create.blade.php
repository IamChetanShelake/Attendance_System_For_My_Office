@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-stopwatch me-2"></i>Create Half Day Policy
                </h1>
                <p class="page-subtitle">Configure half-day attendance rules and automatic enforcement</p>
            </div>

            <!-- Main Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Half Day Policy Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.office-rules.store') }}" id="ruleForm">
                        @csrf
                        <input type="hidden" name="rule_type" value="half_day">
                        <input type="hidden" name="rule_category" value="attendance">

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rule_name" class="form-label fw-bold">
                                        <i class="fas fa-tag me-2"></i>Rule Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('rule_name') is-invalid @enderror"
                                           id="rule_name"
                                           name="rule_name"
                                           value="{{ old('rule_name', 'Half Day Policy - ' . date('M Y')) }}"
                                           placeholder="Enter a descriptive rule name"
                                           required>
                                    @error('rule_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Choose a clear, descriptive name for this half-day policy</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="priority" class="form-label fw-bold">
                                        <i class="fas fa-sort-numeric-up me-2"></i>Priority Level
                                    </label>
                                    <input type="number"
                                           class="form-control @error('priority') is-invalid @enderror"
                                           id="priority"
                                           name="priority"
                                           value="{{ old('priority', 7) }}"
                                           min="0"
                                           max="100"
                                           placeholder="0">
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Higher numbers = higher priority</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-toggle-on me-2"></i>Rule Status
                                    </label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            <span id="statusText">✅ Active</span>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Inactive rules won't be enforced</small>
                                </div>
                            </div>
                        </div>

                        <!-- Rule Description -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="rule_description" class="form-label fw-bold">
                                        <i class="fas fa-file-text me-2"></i>Description <span class="text-muted">(Optional)</span>
                                    </label>
                                    <textarea class="form-control @error('rule_description') is-invalid @enderror"
                                              id="rule_description"
                                              name="rule_description"
                                              rows="3"
                                              placeholder="Describe this half-day policy and when it applies...">{{ old('rule_description') }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Provide clear guidelines for half-day attendance
                                        </small>
                                        <small class="text-count">
                                            <span id="descriptionCount">0</span>/500 characters
                                        </small>
                                    </div>
                                    @error('rule_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Half Day Thresholds -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-clock me-2"></i>Half Day Threshold Configuration
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Half-day Hours</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[half_day_hours]"
                                           value="{{ old('rule_settings.half_day_hours', 4) }}"
                                           min="0.5" max="12" step="0.5" required>
                                    <small class="text-muted">Minimum hours to be considered present for half-day</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Half-day Threshold Time</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[half_day_threshold]"
                                           value="{{ old('rule_settings.half_day_threshold', '13:00') }}"
                                           required>
                                    <small class="text-muted">If employee leaves before this time, mark half-day</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Auto Half-day Trigger</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[auto_half_day_trigger]"
                                           value="{{ old('rule_settings.auto_half_day_trigger', '12:00') }}">
                                    <small class="text-muted">Automatic half-day enforcement time</small>
                                </div>
                            </div>
                        </div>

                        <!-- Half Day Types -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-list-check me-2"></i>Half Day Policy Types
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Allowed Half-Day Options</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="rule_settings[allow_morning_half]" value="1"
                                                       {{ old('rule_settings.allow_morning_half', true) ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    Morning Half (Before Break)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="rule_settings[allow_afternoon_half]" value="1"
                                                       {{ old('rule_settings.allow_afternoon_half', true) ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    Afternoon Half (After Break)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">What types of half-days are allowed</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Break Requirements</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[mandatory_break_required]" value="1"
                                               {{ old('rule_settings.mandatory_break_required', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Mandatory break required for half-days
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[allow_partial_leave]" value="1"
                                               {{ old('rule_settings.allow_partial_leave', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow partial day requests
                                        </label>
                                    </div>
                                    <small class="text-muted">Additional half-day restrictions</small>
                                </div>
                            </div>
                        </div>

                        <!-- Salary & Leave Impact -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-money-bill-wave me-2"></i>Salary & Leave Calculations
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Salary Deduction (%)</label>
                                    <input type="number" class="form-control" step="0.1"
                                           name="rule_settings[salary_deduction_percentage]"
                                           value="{{ old('rule_settings.salary_deduction_percentage', 50) }}"
                                           min="0" max="100">
                                    <small class="text-muted">Percentage deduction for half-day absences</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Leave Deduction</label>
                                    <input type="number" class="form-control" step="0.5"
                                           name="rule_settings[leave_deduction_days]"
                                           value="{{ old('rule_settings.leave_deduction_days', 0.5) }}"
                                           min="0" max="1" step="0.25">
                                    <small class="text-muted">Days deducted from leave balance</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Consecutive Half-Days</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[max_consecutive_half_days]"
                                           value="{{ old('rule_settings.max_consecutive_half_days', 3) }}"
                                           min="1" max="10">
                                    <small class="text-muted">Maximum allowed consecutive half-days</small>
                                </div>
                            </div>
                        </div>

                        <!-- Approval & Notification Settings -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-bell me-2"></i>Approval & Notification Settings
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Approval Requirements</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[requires_approval]" value="1"
                                               {{ old('rule_settings.requires_approval', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Requires supervisor/manager approval
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[allow_auto_approval]" value="1"
                                               {{ old('rule_settings.allow_auto_approval', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow automatic approval (under certain conditions)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Notification Settings</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[notify_supervisor]" value="1"
                                               {{ old('rule_settings.notify_supervisor', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Notify supervisor on half-day request
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[notify_hr]" value="1"
                                               {{ old('rule_settings.notify_hr', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Notify HR department
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[notify_payroll]" value="1"
                                               {{ old('rule_settings.notify_payroll', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Notify payroll on salary impact
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Exceptions & Special Cases -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-exception me-2"></i>Exceptions & Special Cases
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Medical Exemptions</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[medical_exemption_allowed]" value="1"
                                               {{ old('rule_settings.medical_exemption_allowed', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow medical certificate exemptions
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[emergency_exemption_allowed]" value="1"
                                               {{ old('rule_settings.emergency_exemption_allowed', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow emergency situation exemptions
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Maximum Half-Days Per Month</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[max_half_days_per_month]"
                                           value="{{ old('rule_settings.max_half_days_per_month', 6) }}"
                                           min="1" max="15">
                                    <small class="text-muted">Monthly limit before warnings/suspensions</small>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-sticky-note me-2"></i>Additional Policy Notes
                            </h6>
                            <textarea class="form-control" name="rule_settings[additional_notes]" rows="4"
                                      placeholder="Any additional policy notes, examples, or special considerations...">{{ old('rule_settings.additional_notes') }}</textarea>
                            <small class="text-muted">Will be displayed to employees and HR for reference</small>
                        </div>

                        <!-- Preview Section -->
                        <div class="row mb-4" id="rulePreview" style="display: none;">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-eye me-2"></i>Policy Preview</h6>
                                    <div id="previewContent"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary" onclick="previewPolicy()">
                                            <i class="fas fa-eye me-1"></i>Preview Policy
                                        </button>
                                        <button type="button" class="btn btn-outline-info" onclick="loadDefaultSettings()">
                                            <i class="fas fa-magic me-1"></i>Load Standard Settings
                                        </button>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.office-rules.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>Back to Rules
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save me-2"></i>Create Half Day Policy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;">
                    <i class="fas fa-check-circle me-2"></i><strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;">
                    <i class="fas fa-exclamation-triangle me-2"></i><strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>
</main>

<script>
// Character counter
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for description
    document.getElementById('rule_description').addEventListener('input', function() {
        document.getElementById('descriptionCount').textContent = this.value.length;
    });

    // Status checkbox handler
    document.getElementById('is_active').addEventListener('change', function() {
        document.getElementById('statusText').textContent = this.checked ? '✅ Active' : '⏸️ Inactive';
    });

    updateDescriptionCount();
});

function updateDescriptionCount() {
    const textarea = document.getElementById('rule_description');
    if (textarea) {
        document.getElementById('descriptionCount').textContent = textarea.value.length;
    }
}

function previewPolicy() {
    const formData = new FormData(document.getElementById('ruleForm'));
    const data = Object.fromEntries(formData.entries());

    fetch('{{ route("admin.office-rules.preview") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const settings = data.preview.settings;
            document.getElementById('previewContent').innerHTML = `
                <strong>Half-day Hours:</strong> ${settings.half_day_hours || 'N/A'} hours<br>
                <strong>Half-day Threshold:</strong> ${settings.half_day_threshold || 'N/A'}<br>
                <strong>Salary Deduction:</strong> ${settings.salary_deduction_percentage || 'N/A'}%<br>
                <strong>Leave Deduction:</strong> ${settings.leave_deduction_days || 'N/A'} days<br>
                <strong>Max Consecutive:</strong> ${settings.max_consecutive_half_days || 'N/A'} half-days<br>
                <strong>Morning Half:</strong> ${settings.allow_morning_half ? 'Yes' : 'No'}<br>
                <strong>Afternoon Half:</strong> ${settings.allow_afternoon_half ? 'Yes' : 'No'}<br>
                <strong>Status:</strong> <span class="badge ${data.preview.rule.is_active ? 'bg-success' : 'bg-secondary'}">${data.preview.rule_status}</span>
            `;
            document.getElementById('rulePreview').style.display = 'block';
        } else {
            alert('Preview failed: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Preview request failed: ' + error.message);
    });
}

function loadDefaultSettings() {
    // Load standard half day settings
    document.querySelector('input[name="rule_settings[half_day_hours]"]').value = '4';
    document.querySelector('input[name="rule_settings[half_day_threshold]"]').value = '13:00';
    document.querySelector('input[name="rule_settings[salary_deduction_percentage]"]').value = '50';
    document.querySelector('input[name="rule_settings[leave_deduction_days]"]').value = '0.5';
    document.querySelector('input[name="rule_settings[max_consecutive_half_days]"]').value = '3';

    alert('Standard half day settings loaded successfully!');
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #0069d9 100%) !important;
    color: white;
}

.form-label {
    margin-bottom: 0.75rem;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(78, 180, 230, 0.25);
}

.text-count {
    font-size: 0.9rem;
    color: #6c757d;
}

.settings-card {
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.settings-title {
    color: var(--secondary-color);
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-color);
}

.alert-dismissible {
    animation: slideInRight 0.5s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>
@endsection
