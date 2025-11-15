@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-calendar-plus me-2"></i>Create Holiday Consecutive Policy
                </h1>
                <p class="page-subtitle">Configure when holidays falling on weekends should be treated as consecutive leave days</p>
            </div>

            <!-- Main Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Holiday Consecutive Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.office-rules.store') }}" id="ruleForm">
                        @csrf
                        <input type="hidden" name="rule_type" value="holiday_consecutive">
                        <input type="hidden" name="rule_category" value="holiday">

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
                                           value="{{ old('rule_name', 'Holiday Consecutive Policy - ' . date('M Y')) }}"
                                           placeholder="Enter a descriptive rule name"
                                           required>
                                    @error('rule_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Choose a clear, descriptive name for this holiday consecutive policy</small>
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
                                           value="{{ old('priority', 5) }}"
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
                                    <small class="form-text text-muted">Inactive rules won't enforce holiday consecutive treatment</small>
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
                                              placeholder="Describe this holiday consecutive policy and how it handles holidays that fall on weekends...">{{ old('rule_description') }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Explain holiday consecutive day calculations and weekend holiday treatment
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

                        <!-- Holiday Consecutive Settings -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calendar-week me-2"></i>Holiday Consecutive Policy
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Policy Overview:</strong> Define when holidays falling on weekends should count as consecutive holiday or leave combinations.
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="rule_settings[holiday_consecutive][enabled]"
                                               value="1"
                                               id="holiday_consecutive_enabled"
                                               {{ old('rule_settings.holiday_consecutive.enabled', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="holiday_consecutive_enabled">
                                            <strong>Enable Holiday Consecutive Rules</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
<div class="row" id="holidayConsecutiveOptions" style="display: {{ old('rule_settings.holiday_consecutive.enabled', true) ? 'block' : 'none' }};">
                                <div class="col-md-12">
                                    <div class="holiday-workoff-matrix p-4 bg-light rounded">
                                        <h6 class="text-muted mb-3"><i class="fas fa-calendar-days me-2"></i>Holiday Workoff Matrix</h6>
                                        <div class="row">
                                            <!-- Weekend Holiday Rule -->
                                            <div class="col-md-6 mb-3">
                                                <div class="combination-card p-3 border rounded">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h6 class="mb-0"><span class="badge bg-success me-2">Holiday</span> on <span class="badge bg-warning">Weekend</span></h6>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="rule_settings[holiday_consecutive][work_off_required]"
                                                               value="1"
                                                               id="work_off_required"
                                                               {{ old('rule_settings.holiday_consecutive.work_off_required', true) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold" for="work_off_required">
                                                            Work-off Required
                                                        </label>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-6">
                                                            <small><strong>Current:</strong> Holiday counts normally</small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small><strong>If enabled:</strong> Requires equivalent work-off</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Consecutive Holiday Treatment -->
                                            <div class="col-md-6 mb-3">
                                                <div class="combination-card p-3 border rounded">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h6 class="mb-0"><span class="badge bg-success me-2">Holiday</span> → <span class="badge bg-primary">Adjacent Day</span></h6>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="rule_settings[holiday_consecutive][consecutive_treatment]"
                                                               value="1"
                                                               id="consecutive_treatment"
                                                               {{ old('rule_settings.holiday_consecutive.consecutive_treatment', true) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold" for="consecutive_treatment">
                                                            Allow Consecutive Treatment
                                                        </label>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-6">
                                                            <small><strong>Current:</strong> Single holiday leave</small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small><strong>If enabled:</strong> Can combine with adjacent leave</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Work-off Parameters -->
                                        <div class="row mt-3">
                                            <div class="col-md-6 mb-3">
                                                <div class="combination-card p-3 border rounded">
                                                    <h6 class="mb-3"><i class="fas fa-calendar-xmark me-2"></i>Work-off Deadline</h6>
                                                    <label class="form-label fw-bold">Days to Complete Work-off</label>
                                                    <input type="number" class="form-control"
                                                           name="rule_settings[holiday_consecutive][work_off_deadline_days]"
                                                           value="{{ old('rule_settings.holiday_consecutive.work_off_deadline_days', 30) }}"
                                                           min="7" max="90">
                                                    <small class="text-muted">Time limit to complete required work-off days</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="combination-card p-3 border rounded">
                                                    <h6 class="mb-3"><i class="fas fa-percentage me-2"></i>Leave Balance Integration</h6>
                                                    <label class="form-label fw-bold">Work-off Leave Type</label>
                                                    <select class="form-select" name="rule_settings[holiday_consecutive][work_off_leave_type]">
                                                        <option value="casual" {{ old('rule_settings.holiday_consecutive.work_off_leave_type', 'casual') == 'casual' ? 'selected' : '' }}>Casual Leave</option>
                                                        <option value="annual" {{ old('rule_settings.holiday_consecutive.work_off_leave_type', 'casual') == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                                                        <option value="compensatory" {{ old('rule_settings.holiday_consecutive.work_off_leave_type', 'casual') == 'compensatory' ? 'selected' : '' }}>Compensatory Leave</option>
                                                    </select>
                                                    <small class="text-muted">Which leave type to deduct for work-off days</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Holiday Integration -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calendar-alt me-2"></i>Holiday Calendar Integration
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[calendar_integration_enabled]"
                                               value="1"
                                               id="calendar_integration"
                                               {{ old('rule_settings.calendar_integration_enabled', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="calendar_integration">
                                            Integrate with Academic Calendar
                                        </label>
                                    </div>
                                    <small class="text-muted">Check holiday calendar for consecutive calculations</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[public_holiday_weights]"
                                               value="1"
                                               id="public_holiday_weights"
                                               {{ old('rule_settings.public_holiday_weights', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="public_holiday_weights">
                                            Apply Public Holiday Weights
                                        </label>
                                    </div>
                                    <small class="text-muted">Different rules for different types of holidays</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Holiday Categories to Include</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[holiday_categories][national]" value="1"
                                               {{ old('rule_settings.holiday_categories.national', true) ? 'checked' : '' }}>
                                        <label class="form-check-label">National Holidays</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[holiday_categories][regional]" value="1"
                                               {{ old('rule_settings.holiday_categories.regional', false) ? 'checked' : '' }}>
                                        <label class="form-check-label">Regional/State Holidays</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[holiday_categories][religious]" value="1"
                                               {{ old('rule_settings.holiday_categories.religious', true) ? 'checked' : '' }}>
                                        <label class="form-check-label">Religious Festivals</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Adjacent Day Definition</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[adjacent_day_range]"
                                           value="{{ old('rule_settings.adjacent_day_range', 1) }}"
                                           min="1" max="3">
                                    <small class="text-muted">Days to consider as adjacent to holiday (1-3 days)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Approval & Processing Rules -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-clipboard-check me-2"></i>Approval & Processing Requirements
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Approval Requirements</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[holiday_work_off_approval]" value="1"
                                               {{ old('rule_settings.holiday_work_off_approval', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Requires manager/supervisor approval
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[auto_process_consecutive]" value="1"
                                               {{ old('rule_settings.auto_process_consecutive', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Auto-process consecutive combinations
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[require_documentation]" value="1"
                                               {{ old('rule_settings.require_documentation', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Require supporting documentation
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Processing Options</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[flexible_work_off_periods]" value="1"
                                               {{ old('rule_settings.flexible_work_off_periods', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow flexible work-off periods
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[split_work_off_allowed]" value="1"
                                               {{ old('rule_settings.split_work_off_allowed', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow splitting work-off into multiple days
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[carry_forward_work_off]" value="1"
                                               {{ old('rule_settings.carry_forward_work_off', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow carry forward of unused work-off
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Exception & Reporting -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>Exceptions & Reporting
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Exception Categories</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[emergency_exemptions]" value="1"
                                               {{ old('rule_settings.emergency_exemptions', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Emergency exemptions allowed
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[senior_staff_exemptions]" value="1"
                                               {{ old('rule_settings.senior_staff_exemptions', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Senior staff exemptions
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[medical_exemptions]" value="1"
                                               {{ old('rule_settings.medical_exemptions', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Medical/paternity exemptions
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Reporting & Monitoring</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[detailed_reporting]" value="1"
                                               {{ old('rule_settings.detailed_reporting', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Generate detailed reports
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[alert_unresolved_work_off]" value="1"
                                               {{ old('rule_settings.alert_unresolved_work_off', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Alert on unresolved work-off requirements
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[auto_escalation]" value="1"
                                               {{ old('rule_settings.auto_escalation', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Auto-escalation for overdue items
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
                                      placeholder="Any additional policy notes about holiday consecutive rules and work-off requirements...">{{ old('rule_settings.additional_notes') }}</textarea>
                            <small class="text-muted">Will be displayed to employees and managers when applying for leave combining with holidays</small>
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
                                            <i class="fas fa-save me-2"></i>Create Holiday Consecutive Policy
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
// Character counter and settings update
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for description
    document.getElementById('rule_description').addEventListener('input', function() {
        document.getElementById('descriptionCount').textContent = this.value.length;
    });

    // Status checkbox handler
    document.getElementById('is_active').addEventListener('change', function() {
        document.getElementById('statusText').textContent = this.checked ? '✅ Active' : '⏸️ Inactive';
    });

    // Holiday consecutive enable/disable handler
    document.getElementById('holiday_consecutive_enabled').addEventListener('change', function() {
        document.getElementById('holidayConsecutiveOptions').style.display = this.checked ? 'block' : 'none';
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
            const consecutive = settings.holiday_consecutive || {};
            document.getElementById('previewContent').innerHTML = `
                <strong>Holiday Consecutive:</strong> ${consecutive.enabled ? 'Enabled' : 'Disabled'}<br>
                <strong>Work-off Required:</strong> ${consecutive.work_off_required ? 'Yes' : 'No'}<br>
                <strong>Consecutive Treatment:</strong> ${consecutive.consecutive_treatment ? 'Yes' : 'No'}<br>
                <strong>Work-off Deadline:</strong> ${consecutive.work_off_deadline_days || 30} days<br>
                <strong>Leave Type for Work-off:</strong> ${consecutive.work_off_leave_type || 'casual'}<br>
                <strong>Calendar Integration:</strong> ${settings.calendar_integration_enabled ? 'Yes' : 'No'}<br>
                <strong>Approval Required:</strong> ${settings.holiday_work_off_approval ? 'Yes' : 'No'}<br>
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
    // Load standard holiday consecutive settings
    document.getElementById('holiday_consecutive_enabled').checked = true;
    document.getElementById('work_off_required').checked = true;
    document.getElementById('consecutive_treatment').checked = true;
    document.querySelector('input[name="rule_settings[holiday_consecutive][work_off_deadline_days]"]').value = '30';
    document.querySelector('select[name="rule_settings[holiday_consecutive][work_off_leave_type]"]').value = 'casual';

    document.getElementById('holidayConsecutiveOptions').style.display = 'block';
    alert('Standard holiday consecutive settings loaded successfully!');
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

.holiday-workoff-matrix {
    border-left: 4px solid var(--primary-color);
}

.combination-card {
    background: white;
    transition: all 0.3s ease;
}

.combination-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.badge {
    border-radius: 6px;
}
</style>
@endsection
