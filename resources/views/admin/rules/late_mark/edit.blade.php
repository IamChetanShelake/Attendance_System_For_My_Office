@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>Edit Late Mark Policy: {{ $rule->rule_name }}
                </h1>
                <p class="page-subtitle">Modify late mark thresholds and enforcement rules</p>
            </div>

            <!-- Main Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Late Mark Policy Configuration
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
                                    <small class="form-text text-muted">Choose a clear, descriptive name for this late mark policy</small>
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
                                           value="{{ old('priority', $rule->priority ?? 7) }}"
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
                                              placeholder="Describe this late mark policy...">{{ old('rule_description', $rule->rule_description) }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Provide guidelines for late arrival penalties
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

                        <!-- Late Mark Thresholds -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-clock me-2"></i>Late Mark Definition & Thresholds
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Grace Period</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[grace_period_minutes]"
                                           value="{{ old('rule_settings.grace_period_minutes', $rule->rule_settings['grace_period_minutes'] ?? 15) }}"
                                           min="0" max="60" required>
                                    <small class="text-muted">Minutes allowed before late mark</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">One-Hour Cutoff</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[one_hour_cutoff]"
                                           value="{{ old('rule_settings.one_hour_cutoff', $rule->rule_settings['one_hour_cutoff'] ?? '10:00') }}"
                                           required>
                                    <small class="text-muted">Late after this time counts as absent</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Two-Hour Cutoff</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[two_hour_cutoff]"
                                           value="{{ old('rule_settings.two_hour_cutoff', $rule->rule_settings['two_hour_cutoff'] ?? '11:00') }}">
                                    <small class="text-muted">Maximum late arrival allowed</small>
                                </div>
                            </div>
                        </div>

                        <!-- Salary Deductions -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-money-bill-wave me-2"></i>Salary Deduction Rules
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Half-Day Deduction</label>
                                    <input type="number" class="form-control" step="1"
                                           name="rule_settings[half_day_deduction]"
                                           value="{{ old('rule_settings.half_day_deduction', $rule->rule_settings['half_day_deduction'] ?? 50) }}"
                                           min="0" max="100">
                                    <small class="text-muted">Percentage deduction for half-day</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Full-Day Deduction</label>
                                    <input type="number" class="form-control" step="1"
                                           name="rule_settings[full_day_deduction]"
                                           value="{{ old('rule_settings.full_day_deduction', $rule->rule_settings['full_day_deduction'] ?? 100) }}"
                                           min="0" max="100">
                                    <small class="text-muted">Percentage deduction for full-day</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Consecutive Late Limit</label>
                                    <input type="number" class="form-control" step="1"
                                           name="rule_settings[max_consecutive_lates]"
                                           value="{{ old('rule_settings.max_consecutive_lates', $rule->rule_settings['max_consecutive_lates'] ?? 3) }}"
                                           min="1" max="10">
                                    <small class="text-muted">Maximum consecutive late days allowed</small>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-bell me-2"></i>Notification & Monitoring
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Notifications</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[notify_supervisor]" value="1"
                                               {{ old('rule_settings.notify_supervisor', ($rule->rule_settings['notify_supervisor'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Notify supervisor on late arrival
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[notify_hr]" value="1"
                                               {{ old('rule_settings.notify_hr', ($rule->rule_settings['notify_hr'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Notify HR department
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[notify_payroll]" value="1"
                                               {{ old('rule_settings.notify_payroll', ($rule->rule_settings['notify_payroll'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Notify payroll on salary impact
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Late Arrival Policy</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[allow_late_excuses]" value="1"
                                               {{ old('rule_settings.allow_late_excuses', ($rule->rule_settings['allow_late_excuses'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow valid reasons/excuses
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[require_documentation]" value="1"
                                               {{ old('rule_settings.require_documentation', ($rule->rule_settings['require_documentation'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Require proof/documentation
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[log_to_performance]" value="1"
                                               {{ old('rule_settings.log_to_performance', ($rule->rule_settings['log_to_performance'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Include in performance records
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Limits -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calendar-alt me-2"></i>Monthly Limits & Warnings
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Max Lates Per Month</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[max_lates_per_month]"
                                           value="{{ old('rule_settings.max_lates_per_month', $rule->rule_settings['max_lates_per_month'] ?? 6) }}"
                                           min="1" max="15">
                                    <small class="text-muted">Before disciplinary action</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Warning Threshold</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[warning_threshold]"
                                           value="{{ old('rule_settings.warning_threshold', $rule->rule_settings['warning_threshold'] ?? 3) }}"
                                           min="1" max="10">
                                    <small class="text-muted">Send warning after this many lates</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Reset Frequency</label>
                                    <select class="form-select" name="rule_settings[reset_frequency]">
                                        <option value="monthly" {{ old('rule_settings.reset_frequency', $rule->rule_settings['reset_frequency'] ?? 'monthly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('rule_settings.reset_frequency', $rule->rule_settings['reset_frequency'] ?? 'monthly') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="annually" {{ old('rule_settings.reset_frequency', $rule->rule_settings['reset_frequency'] ?? 'monthly') == 'annually' ? 'selected' : '' }}>Annually</option>
                                    </select>
                                    <small class="text-muted">How often to reset late count</small>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-sticky-note me-2"></i>Policy Notes & Exceptions
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Policy Notes</label>
                                    <textarea class="form-control" name="rule_settings[additional_notes]" rows="4"
                                              placeholder="Any additional policy notes, examples, or special considerations...">{{ old('rule_settings.additional_notes', $rule->rule_settings['additional_notes'] ?? '') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Special Conditions</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[allow_medical_exemptions]" value="1"
                                               {{ old('rule_settings.allow_medical_exemptions', ($rule->rule_settings['allow_medical_exemptions'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow medical emergency exemptions
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[allow_transport_exemptions]" value="1"
                                               {{ old('rule_settings.allow_transport_exemptions', ($rule->rule_settings['allow_transport_exemptions'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow transportation issue exemptions
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[auto_warning_letter]" value="1"
                                               {{ old('rule_settings.auto_warning_letter', ($rule->rule_settings['auto_warning_letter'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Auto-send warning letters
                                        </label>
                                    </div>
                                </div>
                            </div>
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
                                            <i class="fas fa-save me-2"></i>Update Late Mark Policy
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
                <strong>Grace Period:</strong> ${settings.grace_period_minutes || 'N/A'} minutes<br>
                <strong>One-Hour Cutoff:</strong> ${settings.one_hour_cutoff || 'N/A'}<br>
                <strong>Salary Deduction:</strong> ${settings.half_day_deduction || 'N/A'}% for half-day<br>
                <strong>Max Lates/Month:</strong> ${settings.max_lates_per_month || 'N/A'}<br>
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

    // Load standard late mark settings
    document.querySelector('input[name="rule_settings[grace_period_minutes]"]').value = '15';
    document.querySelector('input[name="rule_settings[one_hour_cutoff]"]').value = '10:00';
    document.querySelector('input[name="rule_settings[two_hour_cutoff]"]').value = '11:00';
    document.querySelector('input[name="rule_settings[half_day_deduction]"]').value = '50';
    document.querySelector('input[name="rule_settings[full_day_deduction]"]').value = '100';
    document.querySelector('input[name="rule_settings[max_consecutive_lates]"]').value = '3';
    document.querySelector('input[name="rule_settings[max_lates_per_month]"]').value = '6';
    document.querySelector('input[name="rule_settings[warning_threshold]"]').value = '3';
    document.querySelector('select[name="rule_settings[reset_frequency]"]').value = 'monthly';

    // Check relevant checkboxes
    const checkboxesToCheck = [
        'rule_settings[notify_supervisor]',
        'rule_settings[notify_hr]',
        'rule_settings[allow_late_excuses]',
        'rule_settings[log_to_performance]',
        'rule_settings[allow_medical_exemptions]',
        'rule_settings[allow_transport_exemptions]'
    ];

    checkboxesToCheck.forEach(function(selector) {
        var checkbox = document.querySelector('input[name="' + selector + '"]');
        if (checkbox) {
            checkbox.checked = true;
        }
    });

    // Uncheck certain checkboxes
    const checkboxesToUncheck = [
        'rule_settings[notify_payroll]',
        'rule_settings[require_documentation]',
        'rule_settings[auto_warning_letter]'
    ];

    checkboxesToUncheck.forEach(function(selector) {
        var checkbox = document.querySelector('input[name="' + selector + '"]');
        if (checkbox) {
            checkbox.checked = false;
        }
    });

    alert('Standard late mark settings loaded successfully!');
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
