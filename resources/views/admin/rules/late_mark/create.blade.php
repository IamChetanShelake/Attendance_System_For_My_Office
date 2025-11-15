@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Create Late Mark Policy
                </h1>
                <p class="page-subtitle">Configure automatic late marking, half-day enforcement, and disciplinary actions</p>
            </div>

            <!-- Main Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Late Mark Policy Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.office-rules.store') }}" id="ruleForm">
                        @csrf
                        <input type="hidden" name="rule_type" value="late_mark">
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
                                           value="{{ old('rule_name', 'Late Mark Policy - ' . date('M Y')) }}"
                                           placeholder="Enter a descriptive rule name"
                                           required>
                                    @error('rule_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Choose a clear, descriptive name for this late policy</small>
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
                                           value="{{ old('priority', 8) }}"
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
                                              placeholder="Describe this late mark policy and its enforcement...">{{ old('rule_description') }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Provide clear guidelines for late attendance consequences
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
                                <i class="fas fa-clock me-2"></i>Late Arrival Thresholds
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Late Threshold (minutes)</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[late_threshold_minutes]"
                                           value="{{ old('rule_settings.late_threshold_minutes', 15) }}"
                                           min="1" max="300" step="1" required>
                                    <small class="text-muted">Minutes after start time to be considered late</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Half-day after specific time</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[half_day_threshold_time]"
                                           value="{{ old('rule_settings.half_day_threshold_time', '14:00') }}"
                                           required>
                                    <small class="text-muted">If employee arrives after this time, mark half-day</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Auto half-day if late beyond</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[auto_half_day_threshold]"
                                           value="{{ old('rule_settings.auto_half_day_threshold', '13:30') }}"
                                           required>
                                    <small class="text-muted">Automatic half-day enforcement time</small>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Limits -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calendar-check me-2"></i>Monthly Late Mark Limits
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Max Late Marks per Month</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[max_late_marks_per_month]"
                                           value="{{ old('rule_settings.max_late_marks_per_month', 3) }}"
                                           min="0" max="31" step="1" required>
                                    <small class="text-muted">Maximum allowed late marks per calendar month</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Action on Exceed</label>
                                    <select class="form-select" name="rule_settings[action_on_exceed]" required>
                                        <option value="warning" {{ old('rule_settings.action_on_exceed', 'warning') == 'warning' ? 'selected' : '' }}>Warning</option>
                                        <option value="deduction" {{ old('rule_settings.action_on_exceed', 'warning') == 'deduction' ? 'selected' : '' }}>Salary Deduction</option>
                                        <option value="suspension" {{ old('rule_settings.action_on_exceed', 'warning') == 'suspension' ? 'selected' : '' }}>Suspension</option>
                                        <option value="termination" {{ old('rule_settings.action_on_exceed', 'warning') == 'termination' ? 'selected' : '' }}>Termination</option>
                                    </select>
                                    <small class="text-muted">Action to take when monthly limit exceeded</small>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[reset_monthly_on_ranking]" value="1"
                                               {{ old('rule_settings.reset_monthly_on_ranking', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Reset count on rank promotion
                                        </label>
                                    </div>
                                    <small class="text-muted">Clear late mark count when employee is promoted</small>
                                </div>
                            </div>
                        </div>

                        <!-- Penalty Configuration -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>Penalty & Disciplinary Actions
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Salary Deduction (%)</label>
                                    <input type="number" class="form-control" step="0.1"
                                           name="rule_settings[deduction_percentage]"
                                           value="{{ old('rule_settings.deduction_percentage', 2) }}"
                                           min="0" max="100">
                                    <small class="text-muted">Percentage deduction for each late mark</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Notice Period</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[notice_period_days]"
                                           value="{{ old('rule_settings.notice_period_days', 3) }}"
                                           min="1" max="30">
                                    <small class="text-muted">Days notice given before deductions</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Warning Levels</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm"
                                                   name="rule_settings[first_warning_at]"
                                                   value="{{ old('rule_settings.first_warning_at', 2) }}"
                                                   min="1" max="10" placeholder="1st">
                                            <small class="text-muted">1st Warning</small>
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm"
                                                   name="rule_settings[final_warning_at]"
                                                   value="{{ old('rule_settings.final_warning_at', 5) }}"
                                                   min="1" max="10" placeholder="Final">
                                            <small class="text-muted">Final Warning</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Special Cases & Exemptions -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-user-shield me-2"></i>Exemptions & Special Cases
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Exempt Roles</label>
                                    <input type="text" class="form-control" readonly value="Configured in Attendance Policy"
                                           placeholder="Select roles that are exempt...">
                                    <small class="text-muted">Different roles may have different threshold</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Emergency Contingency</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[allow_emergency_excuse]" value="1"
                                               {{ old('rule_settings.allow_emergency_excuse', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow emergency excuse submissions
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[medical_request_allowed]" value="1"
                                               {{ old('rule_settings.medical_request_allowed', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Medical certificate acceptable
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[allow_one_free_late]" value="1"
                                               {{ old('rule_settings.allow_one_free_late', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            One free late mark per quarter
                                        </label>
                                    </div>
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
                                            <i class="fas fa-save me-2"></i>Create Late Mark Policy
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
                <strong>Late Threshold:</strong> ${settings.late_threshold_minutes || 'N/A'} minutes<br>
                <strong>Half-day after:</strong> ${settings.half_day_threshold_time || 'N/A'}<br>
                <strong>Auto half-day beyond:</strong> ${settings.auto_half_day_threshold || 'N/A'}<br>
                <strong>Max Late Marks/Month:</strong> ${settings.max_late_marks_per_month || 'N/A'}<br>
                <strong>Action on Exceed:</strong> ${settings.action_on_exceed || 'N/A'}<br>
                <strong>Salary Deduction:</strong> ${settings.deduction_percentage || 'N/A'}%<br>
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
    // Load standard late mark settings
    document.querySelector('input[name="rule_settings[late_threshold_minutes]"]').value = '15';
    document.querySelector('input[name="rule_settings[half_day_threshold_time]"]').value = '14:00';
    document.querySelector('input[name="rule_settings[auto_half_day_threshold]"]').value = '13:30';
    document.querySelector('input[name="rule_settings[max_late_marks_per_month]"]').value = '3';
    document.querySelector('input[name="rule_settings[deduction_percentage]"]').value = '2';

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
