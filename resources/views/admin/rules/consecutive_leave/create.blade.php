@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-link me-2"></i>Create Consecutive Leave Policy
                </h1>
                <p class="page-subtitle">Configure Sunday-to-Monday and weekend adjacency rules</p>
            </div>

            <!-- Main Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Consecutive Leave Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.office-rules.store') }}" id="ruleForm">
                        @csrf
                        <input type="hidden" name="rule_type" value="consecutive_leave">
                        <input type="hidden" name="rule_category" value="leave">

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
                                           value="{{ old('rule_name', 'Consecutive Leave Policy - ' . date('M Y')) }}"
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
                                    <small class="form-text text-muted">Inactive rules won't enforce consecutive treatment</small>
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
                                              placeholder="Describe this consecutive leave policy and how it handles weekend adjacencies...">{{ old('rule_description') }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Explain consecutive leave calculations and weekend handling
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

                        <!-- Weekend Consecutive Settings -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calendar-week me-2"></i>Weekend Consecutive Policy
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Policy Overview:</strong> Define when weekend combinations count as 2 consecutive days vs single weekday leave.
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="rule_settings[weekend_consecutive][enabled]"
                                               value="1"
                                               id="weekend_consecutive_enabled"
                                               {{ old('rule_settings.weekend_consecutive.enabled', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="weekend_consecutive_enabled">
                                            <strong>Enable Weekend Consecutive Rules</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="weekendConsecutiveOptions" style="{{ old('rule_settings.weekend_consecutive.enabled', true) ? '' : 'display: none;' }}">
                                <div class="col-md-12">
                                    <div class="weekend-matrix p-4 bg-light rounded">
                                        <h6 class="text-muted mb-3"><i class="fas fa-sitemap me-2"></i>Weekend Combination Matrix</h6>
                                        <div class="row">
                                            <!-- Sunday to Monday -->
                                            <div class="col-md-6 mb-3">
                                                <div class="combination-card p-3 border rounded">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h6 class="mb-0"><span class="badge bg-danger me-2">Sunday</span> → <span class="badge bg-primary">Monday</span></h6>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="rule_settings[weekend_consecutive][sunday_to_monday]"
                                                               value="1"
                                                               id="sunday_monday_consecutive"
                                                               {{ old('rule_settings.weekend_consecutive.sunday_to_monday', true) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold" for="sunday_monday_consecutive">
                                                            Treat as 2 Consecutive Days
                                                        </label>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-6">
                                                            <small><strong>Current:</strong> <span id="sunday_monday_status">1 weekday leave</span></small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small><strong>If enabled:</strong> 2 consecutive days</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Saturday to Monday -->
                                            <div class="col-md-6 mb-3">
                                                <div class="combination-card p-3 border rounded">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h6 class="mb-0"><span class="badge bg-warning me-2">Saturday</span> → <span class="badge bg-primary">Monday</span></h6>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="rule_settings[weekend_consecutive][saturday_to_monday]"
                                                               value="1"
                                                               id="saturday_monday_consecutive"
                                                               {{ old('rule_settings.weekend_consecutive.saturday_to_monday', true) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold" for="saturday_monday_consecutive">
                                                            Treat as 2 Consecutive Days
                                                        </label>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-6">
                                                            <small><strong>Current:</strong> <span id="saturday_monday_status">1 weekday leave</span></small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small><strong>If enabled:</strong> 2 consecutive days</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Friday to Saturday -->
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="combination-card p-3 border rounded">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h6 class="mb-0"><span class="badge bg-secondary me-2">Friday</span> → <span class="badge bg-warning">Saturday</span></h6>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="rule_settings[weekend_consecutive][friday_to_saturday]"
                                                               value="1"
                                                               id="friday_saturday_consecutive"
                                                               {{ old('rule_settings.weekend_consecutive.friday_to_saturday', false) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold" for="friday_saturday_consecutive">
                                                            Treat as 2 Consecutive Days
                                                        </label>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-6">
                                                            <small><strong>Current:</strong> <span id="friday_saturday_status">Separate days</span></small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small><strong>If enabled:</strong> 2 consecutive days</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Maximum Consecutive Days -->
                                            <div class="col-md-6 mb-3">
                                                <div class="combination-card p-3 border rounded">
                                                    <h6 class="mb-3"><i class="fas fa-sliders-h me-2"></i>General Settings</h6>
                                                    <label class="form-label fw-bold">Maximum Consecutive Days Allowed</label>
                                                    <input type="number" class="form-control"
                                                           name="rule_settings[weekend_consecutive][max_consecutive_days]"
                                                           value="{{ old('rule_settings.weekend_consecutive.max_consecutive_days', 2) }}"
                                                           min="1" max="10">
                                                    <small class="text-muted">Limit consecutive leave combinations</small>
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
                                <i class="fas fa-calendar-alt me-2"></i>Holiday Consecutive Integration
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[holiday_integration_enabled]"
                                               value="1"
                                               id="holiday_integration"
                                               {{ old('rule_settings.holiday_integration_enabled', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="holiday_integration">
                                            Integrate with Academic Calendar Holidays
                                        </label>
                                    </div>
                                    <small class="text-muted">Check holiday calendar for consecutive calculations</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[holiday_if_falls_on_weekend]"
                                               value="1"
                                               id="weekend_holiday_fallback"
                                               {{ old('rule_settings.holiday_if_falls_on_weekend', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="weekend_holiday_fallback">
                                            Holiday fallback if falls on weekend
                                        </label>
                                    </div>
                                    <small class="text-muted">Apply consecutive rules to holidays adjacent to weekends</small>
                                </div>
                            </div>
                        </div>

                        <!-- Leave Balance & Calculations -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calculator me-2"></i>Leave Balance Calculations
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Consecutive Day Deduction Strategy</label>
                                    <select class="form-select" name="rule_settings[deduction_strategy]">
                                        <option value="single_day" {{ old('rule_settings.deduction_strategy', 'single_day') == 'single_day' ? 'selected' : '' }}>Single day (normal rate)</option>
                                        <option value="consecutive_bonus" {{ old('rule_settings.deduction_strategy', 'single_day') == 'consecutive_bonus' ? 'selected' : '' }}>Consecutive bonus</option>
                                        <option value="weekend_penalty" {{ old('rule_settings.deduction_strategy', 'single_day') == 'weekend_penalty' ? 'selected' : '' }}>Weekend penalty</option>
                                    </select>
                                    <small class="text-muted">How to handle leave balance deductions for consecutive combinations</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Approval Requirements</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[require_approval_for_consecutive]"
                                               value="1"
                                               {{ old('rule_settings.require_approval_for_consecutive', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Special approval for consecutive combinations
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[auto_approve_single_day]"
                                               value="1"
                                               {{ old('rule_settings.auto_approve_single_day', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Auto-approve single day applications
                                        </label>
                                    </div>
                                    <small class="text-muted">Approval workflow for consecutive leaves</small>
                                </div>
                            </div>
                        </div>

                        <!-- Policy Exceptions -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-exception me-2"></i>Special Cases & Exceptions
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Emergency Exemptions</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[emergency_exemption_allowed]"
                                               value="1"
                                               {{ old('rule_settings.emergency_exemption_allowed', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow emergency consecutive leave requests
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[sick_leave_exemption]"
                                               value="1"
                                               {{ old('rule_settings.sick_leave_exemption', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Sick leave exempt from consecutive rules
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Role-Based Exemptions</label>
                                    <input type="text" class="form-control" readonly
                                           value="Configured in attendance policy roles"
                                           placeholder="Some roles may bypass consecutive rules">
                                    <small class="text-muted">Senior positions may have different consecutive leave treatment</small>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-sticky-note me-2"></i>Additional Policy Notes
                            </h6>
                            <textarea class="form-control" name="rule_settings[additional_notes]" rows="4"
                                      placeholder="Any additional policy notes about consecutive leave combinations...">{{ old('rule_settings.additional_notes') }}</textarea>
                            <small class="text-muted">Will be displayed to employees when applying for consecutive leave</small>
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
                                            <i class="fas fa-save me-2"></i>Create Consecutive Leave Policy
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

    // Weekend consecutive enable/disable handler
    document.getElementById('weekend_consecutive_enabled').addEventListener('change', function() {
        document.getElementById('weekendConsecutiveOptions').style.display = this.checked ? 'block' : 'none';
    });

    // Dynamic status updates for combinations
    ['sunday_monday', 'saturday_monday', 'friday_saturday'].forEach(id => {
        const checkboxId = `${id}_consecutive`;
        const statusElement = document.getElementById(`${id}_status`);

        const checkbox = document.getElementById(checkboxId);
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                statusElement.textContent = this.checked ?
                    `${id.replace('_', '-')} as 2 consecutive days` :
                    `Separate ${id.replace('_', '-')} applications`;
            });
        }
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
            const consecutive = settings.weekend_consecutive || {};
            document.getElementById('previewContent').innerHTML = `
                <strong>Weekend Consecutive:</strong> ${consecutive.enabled ? 'Enabled' : 'Disabled'}<br>
                <strong>Sunday→Monday:</strong> ${consecutive.sunday_to_monday ? '2 consecutive days' : 'Separate applications'}<br>
                <strong>Saturday→Monday:</strong> ${consecutive.saturday_to_monday ? '2 consecutive days' : 'Separate applications'}<br>
                <strong>Friday→Saturday:</strong> ${consecutive.friday_to_saturday ? '2 consecutive days' : 'Separate applications'}<br>
                <strong>Max Consecutive:</strong> ${consecutive.max_consecutive_days || 2} days<br>
                <strong>Holiday Integration:</strong> ${settings.holiday_integration_enabled ? 'Yes' : 'No'}<br>
                <strong>Approval Required:</strong> ${settings.require_approval_for_consecutive ? 'Yes' : 'No'}<br>
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
    // Load standard consecutive leave settings (Sunday→Monday, Saturday→Monday as consecutive)
    document.getElementById('weekend_consecutive_enabled').checked = true;
    document.getElementById('sunday_monday_consecutive').checked = true;
    document.getElementById('saturday_monday_consecutive').checked = true;
    document.getElementById('friday_saturday_consecutive').checked = false;
    document.querySelector('input[name="rule_settings[weekend_consecutive][max_consecutive_days]"]').value = '2';

    document.getElementById('weekendConsecutiveOptions').style.display = 'block';
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

.weekend-matrix {
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
