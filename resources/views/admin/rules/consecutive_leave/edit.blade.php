@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>Edit Consecutive Leave Policy: {{ $rule->rule_name }}
                </h1>
                <p class="page-subtitle">Modify Sunday-Monday leave adjacency rules and conditions</p>
            </div>

            <!-- Main Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Consecutive Leave Policy Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.office-rules.update', ['office_rule' => $rule]) }}" id="ruleForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="rule_type" value="{{ $rule->rule_type }}">
                        <input type="hidden" name="rule_category" value="{{ $rule->rule_category }}">

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
                                           value="{{ old('rule_name', $rule->rule_name) }}"
                                           placeholder="Enter a descriptive rule name"
                                           required>
                                    @error('rule_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Choose a clear, descriptive name for this consecutive leave policy</small>
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
                                           value="{{ old('priority', $rule->priority ?? 5) }}"
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
                                               {{ old('is_active', $rule->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            <span id="statusText">{{ old('is_active', $rule->is_active) ? '✅ Active' : '⏸️ Inactive' }}</span>
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
                                              placeholder="Describe this consecutive leave policy...">{{ old('rule_description', $rule->rule_description) }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Provide details about weekend leave policies
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

                        <!-- Consecutive Leave Definition -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calendar-week me-2"></i>Consecutive Leave Definitions
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[sunday_monday_consecutive]" value="1"
                                               {{ old('rule_settings.sunday_monday_consecutive', ($rule->rule_settings['sunday_monday_consecutive'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Sunday-Monday are Consecutive
                                        </label>
                                    </div>
                                    <small class="text-muted">Treat Sunday-Monday as one continuous leave period</small>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[friday_saturday_consecutive]" value="1"
                                               {{ old('rule_settings.friday_saturday_consecutive', ($rule->rule_settings['friday_saturday_consecutive'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Friday-Saturday are Consecutive
                                        </label>
                                    </div>
                                    <small class="text-muted">Treat Friday-Saturday as one continuous leave period</small>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[include_holidays]" value="1"
                                               {{ old('rule_settings.include_holidays', ($rule->rule_settings['include_holidays'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Include Holidays in Calculations
                                        </label>
                                    </div>
                                    <small class="text-muted">Consider holidays when counting consecutive days</small>
                                </div>
                            </div>
                        </div>

                        <!-- Leave Restriction Rules -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-ban me-2"></i>Leave Restriction & Approval Rules
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Max Weekend Leaves per Month</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[max_weekend_leaves_per_month]"
                                           value="{{ old('rule_settings.max_weekend_leaves_per_month', $rule->rule_settings['max_weekend_leaves_per_month'] ?? 2) }}"
                                           min="0" max="10">
                                    <small class="text-muted">Maximum weekend leaves allowed per month</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Min Notice Days</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[min_notice_days]"
                                           value="{{ old('rule_settings.min_notice_days', $rule->rule_settings['min_notice_days'] ?? 3) }}"
                                           min="0" max="30">
                                    <small class="text-muted">Advance notice required for weekend leaves</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Max Pending Applications</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[max_pending_weekend_leaves]"
                                           value="{{ old('rule_settings.max_pending_weekend_leaves', $rule->rule_settings['max_pending_weekend_leaves'] ?? 1) }}"
                                           min="1" max="5">
                                    <small class="text-muted">Maximum pending weekend leave applications</small>
                                </div>
                            </div>
                        </div>

                        <!-- Approval & Information Requirements -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-clipboard-check me-2"></i>Approval & Information Requirements
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Approval Requirements</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[weekend_leave_requires_approval]" value="1"
                                               {{ old('rule_settings.weekend_leave_requires_approval', ($rule->rule_settings['weekend_leave_requires_approval'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Requires manager/supervisor approval
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[require_reason]" value="1"
                                               {{ old('rule_settings.require_reason', ($rule->rule_settings['require_reason'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Requires valid reason for weekend leave
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[require_alternative_coverage]" value="1"
                                               {{ old('rule_settings.require_alternative_coverage', ($rule->rule_settings['require_alternative_coverage'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Require alternative coverage arrangement
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Information & Documentation</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[require_supporting_docs]" value="1"
                                               {{ old('rule_settings.require_supporting_docs', ($rule->rule_settings['require_supporting_docs'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Require supporting documentation
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[consider_attendance_history]" value="1"
                                               {{ old('rule_settings.consider_attendance_history', ($rule->rule_settings['consider_attendance_history'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Consider attendance history during approval
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[flag_frequent_weekend_leaves]" value="1"
                                               {{ old('rule_settings.flag_frequent_weekend_leaves', ($rule->rule_settings['flag_frequent_weekend_leaves'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Flag frequent weekend leave patterns
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-bell me-2"></i>Notification & Communication Settings
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Notifications for Approvers</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[notify_supervisor_on_application]" value="1"
                                               {{ old('rule_settings.notify_supervisor_on_application', ($rule->rule_settings['notify_supervisor_on_application'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Notify supervisor when application submitted
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[notify_hr_on_weekend_leave]" value="1"
                                               {{ old('rule_settings.notify_hr_on_weekend_leave', ($rule->rule_settings['notify_hr_on_weekend_leave'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Notify HR department for weekend leaves
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[notify_team_lead]" value="1"
                                               {{ old('rule_settings.notify_team_lead', ($rule->rule_settings['notify_team_lead'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Notify team lead for coverage planning
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Employee Communications</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[send_reminders]" value="1"
                                               {{ old('rule_settings.send_reminders', ($rule->rule_settings['send_reminders'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Send application status reminders
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[auto_reject_over_limit]" value="1"
                                               {{ old('rule_settings.auto_reject_over_limit', ($rule->rule_settings['auto_reject_over_limit'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Auto-reject applications over monthly limit
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[show_remaining_quota]" value="1"
                                               {{ old('rule_settings.show_remaining_quota', ($rule->rule_settings['show_remaining_quota'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Show remaining weekend leave quota to employees
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Special Conditions -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-star me-2"></i>Special Conditions & Exemptions
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Emergency Exemptions</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[allow_emergency_override]" value="1"
                                               {{ old('rule_settings.allow_emergency_override', ($rule->rule_settings['allow_emergency_override'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow emergency situation overrides
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[exempt_senior_management]" value="1"
                                               {{ old('rule_settings.exempt_senior_management', ($rule->rule_settings['exempt_senior_management'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Exempt senior management from restrictions
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Carry Forward & Reset</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[carry_forward_unused_leaves]" value="1"
                                               {{ old('rule_settings.carry_forward_unused_leaves', ($rule->rule_settings['carry_forward_unused_leaves'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow carry forward of unused weekend leaves
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[reset_quota_yearly]" value="1"
                                               {{ old('rule_settings.reset_quota_yearly', ($rule->rule_settings['reset_quota_yearly'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Reset weekend leave quota yearly
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-sticky-note me-2"></i>Additional Policy Notes & Guidelines
                            </h6>
                            <textarea class="form-control" name="rule_settings[policy_notes]" rows="4"
                                      placeholder="Any additional policy notes, guidelines, or special conditions for weekend leaves...">{{ old('rule_settings.policy_notes', $rule->rule_settings['policy_notes'] ?? '') }}</textarea>
                            <small class="text-muted">Will be displayed to employees and managers for reference</small>
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
                                            <i class="fas fa-save me-2"></i>Update Consecutive Leave Policy
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
            @if($errors->any())
                <script>console.log({!! json_encode($errors->all()) !!});</script>
            @endif
        </div>
    </div>
</main>

<script>
// Populate old input values on page load
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for description
    document.getElementById('rule_description').addEventListener('input', function() {
        document.getElementById('descriptionCount').textContent = this.value.length;
    });

    // Status checkbox handler
    document.getElementById('is_active').addEventListener('change', function() {
        document.getElementById('statusText').textContent = this.checked ? '✅ Active' : '⏸️ Inactive';
    });

    // Set initial state
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
                <strong>Sunday-Monday Consecutive:</strong> ${settings.sunday_monday_consecutive ? 'Yes' : 'No'}<br>
                <strong>Max Weekend Leaves/Month:</strong> ${settings.max_weekend_leaves_per_month || 'N/A'}<br>
                <strong>Min Notice Days:</strong> ${settings.min_notice_days || 'N/A'} days<br>
                <strong>Approval Required:</strong> ${settings.weekend_leave_requires_approval ? 'Yes' : 'No'}<br>
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
    // Ask user for confirmation before resetting
    if (!confirm('This will reset all settings to default values. Continue?')) {
        return;
    }

    // Load standard consecutive leave settings
    document.querySelector('input[name="rule_settings[max_weekend_leaves_per_month]"]').value = '2';
    document.querySelector('input[name="rule_settings[min_notice_days]"]').value = '3';
    document.querySelector('input[name="rule_settings[max_pending_weekend_leaves]"]').value = '1';

    // Check relevant checkboxes
    var checkboxesToCheck = [
        'rule_settings[sunday_monday_consecutive]',
        'rule_settings[include_holidays]',
        'rule_settings[weekend_leave_requires_approval]',
        'rule_settings[require_reason]',
        'rule_settings[consider_attendance_history]',
        'rule_settings[flag_frequent_weekend_leaves]',
        'rule_settings[notify_supervisor_on_application]',
        'rule_settings[notify_hr_on_weekend_leave]',
        'rule_settings[send_reminders]',
        'rule_settings[show_remaining_quota]',
        'rule_settings[allow_emergency_override]',
        'rule_settings[reset_quota_yearly]'
    ];

    checkboxesToCheck.forEach(function(selector) {
        var checkbox = document.querySelector('input[name="' + selector + '"]');
        if (checkbox) {
            checkbox.checked = true;
        }
    });

    // Uncheck certain checkboxes
    var checkboxesToUncheck = [
        'rule_settings[friday_saturday_consecutive]',
        'rule_settings[require_alternative_coverage]',
        'rule_settings[require_supporting_docs]',
        'rule_settings[notify_team_lead]',
        'rule_settings[auto_reject_over_limit]',
        'rule_settings[exempt_senior_management]',
        'rule_settings[carry_forward_unused_leaves]'
    ];

    checkboxesToUncheck.forEach(function(selector) {
        var checkbox = document.querySelector('input[name="' + selector + '"]');
        if (checkbox) {
            checkbox.checked = false;
        }
    });

    alert('Standard consecutive leave settings loaded successfully!');
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
