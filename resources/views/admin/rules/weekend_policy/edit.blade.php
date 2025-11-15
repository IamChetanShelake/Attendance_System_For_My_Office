@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>Edit Weekend Policy: {{ $rule->rule_name }}
                </h1>
                <p class="page-subtitle">Modify Saturday working rules and overtime policies</p>
            </div>

            <!-- Main Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Weekend Policy Configuration
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
                                    <small class="form-text text-muted">Choose a clear, descriptive name for this weekend policy</small>
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
                                           value="{{ old('priority', $rule->priority ?? 6) }}"
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
                                              placeholder="Describe this weekend policy...">{{ old('rule_description', $rule->rule_description) }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Provide details about weekend working conditions
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

                        <!-- Weekend Work Policy -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calendar-check me-2"></i>Weekend Work Requirements
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Weekend Working</label>
                                    <select class="form-select" name="rule_settings[weekend_working_policy]">
                                        <option value="optional" {{ old('rule_settings.weekend_working_policy', $rule->rule_settings['weekend_working_policy'] ?? 'optional') == 'optional' ? 'selected' : '' }}>Optional</option>
                                        <option value="required" {{ old('rule_settings.weekend_working_policy', $rule->rule_settings['weekend_working_policy'] ?? 'optional') == 'required' ? 'selected' : '' }}>Required</option>
                                        <option value="voluntary" {{ old('rule_settings.weekend_working_policy', $rule->rule_settings['weekend_working_policy'] ?? 'optional') == 'voluntary' ? 'selected' : '' }}>Voluntary Only</option>
                                    </select>
                                    <small class="text-muted">When Saturday working is allowed</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Minimum Hours</label>
                                    <input type="number" class="form-control" step="0.5"
                                           name="rule_settings[min_weekend_hours]"
                                           value="{{ old('rule_settings.min_weekend_hours', $rule->rule_settings['min_weekend_hours'] ?? 4) }}"
                                           min="0" max="12">
                                    <small class="text-muted">Minimum hours required for weekend work</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Maximum Hours</label>
                                    <input type="number" class="form-control" step="0.5"
                                           name="rule_settings[max_weekend_hours]"
                                           value="{{ old('rule_settings.max_weekend_hours', $rule->rule_settings['max_weekend_hours'] ?? 8) }}"
                                           min="0" max="24">
                                    <small class="text-muted">Maximum hours allowed per weekend day</small>
                                </div>
                            </div>
                        </div>

                        <!-- Overtime Calculation -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calculator me-2"></i>Overtime Calculation & Compensation
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Overtime Rate (Weekly)</label>
                                    <input type="number" class="form-control" step="0.1"
                                           name="rule_settings[overtime_day_rate]"
                                           value="{{ old('rule_settings.overtime_day_rate', $rule->rule_settings['overtime_day_rate'] ?? 1.5) }}"
                                           min="1" max="3">
                                    <small class="text-muted">Multiplication factor for weekend work</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Saturday Rate</label>
                                    <input type="number" class="form-control" step="0.1"
                                           name="rule_settings[saturday_rate]"
                                           value="{{ old('rule_settings.saturday_rate', $rule->rule_settings['saturday_rate'] ?? 2) }}"
                                           min="1" max="3">
                                    <small class="text-muted">Pay rate for Saturday work</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Sunday Rate</label>
                                    <input type="number" class="form-control" step="0.1"
                                           name="rule_settings[sunday_rate]"
                                           value="{{ old('rule_settings.sunday_rate', $rule->rule_settings['sunday_rate'] ?? 3) }}"
                                           min="1" max="3">
                                    <small class="text-muted">Pay rate for Sunday work</small>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Preferences -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-clock me-2"></i>Timing & Scheduling
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Preferred Start Time</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[weekend_start_time]"
                                           value="{{ old('rule_settings.weekend_start_time', $rule->rule_settings['weekend_start_time'] ?? '09:00') }}">
                                    <small class="text-muted">Suggested start time for weekend work</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Preferred End Time</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[weekend_end_time]"
                                           value="{{ old('rule_settings.weekend_end_time', $rule->rule_settings['weekend_end_time'] ?? '17:00') }}">
                                    <small class="text-muted">Suggested end time for weekend work</small>
                                </div>
                            </div>
                        </div>

                        <!-- Administrative Controls -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-user-shield me-2"></i>Administrative Controls & Notifications
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Approval Requirements</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[weekend_requires_approval]" value="1"
                                               {{ old('rule_settings.weekend_requires_approval', ($rule->rule_settings['weekend_requires_approval'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Requires manager approval for weekend work
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[notify_weekend_supervisor]" value="1"
                                               {{ old('rule_settings.notify_weekend_supervisor', ($rule->rule_settings['notify_weekend_supervisor'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Notify supervisor for weekend scheduling
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[notify_hr_weekends]" value="1"
                                               {{ old('rule_settings.notify_hr_weekends', ($rule->rule_settings['notify_hr_weekends'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Notify HR department
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Special Conditions</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[weekend_regular_pay]" value="1"
                                               {{ old('rule_settings.weekend_regular_pay', ($rule->rule_settings['weekend_regular_pay'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Count weekend hours toward regular pay
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[weekend_affects_leaves]" value="1"
                                               {{ old('rule_settings.weekend_affects_leaves', ($rule->rule_settings['weekend_affects_leaves'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Weekend work affects leave balance
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[weekend_overtime_compensation]" value="1"
                                               {{ old('rule_settings.weekend_overtime_compensation', ($rule->rule_settings['weekend_overtime_compensation'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Weekend work eligible for overtime pay
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
                                      placeholder="Any additional weekend policy notes, special conditions, or guidelines...">{{ old('rule_settings.additional_notes', $rule->rule_settings['additional_notes'] ?? '') }}</textarea>
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
                                            <i class="fas fa-save me-2"></i>Update Weekend Policy
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
                <strong>Weekend Policy:</strong> ${settings.weekend_working_policy || 'N/A'}<br>
                <strong>Hours:</strong> ${settings.min_weekend_hours || 'N/A'} - ${settings.max_weekend_hours || 'N/A'} hours<br>
                <strong>Overtime Rate:</strong> ${settings.saturday_rate || 'N/A'}x for Saturdays<br>
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

    // Load standard weekend policy settings
    document.querySelector('input[name="rule_settings[min_weekend_hours]"]').value = '4';
    document.querySelector('input[name="rule_settings[max_weekend_hours]"]').value = '8';
    document.querySelector('input[name="rule_settings[overtime_day_rate]"]').value = '1.5';
    document.querySelector('input[name="rule_settings[saturday_rate]"]').value = '2';
    document.querySelector('input[name="rule_settings[sunday_rate]"]').value = '3';
    document.querySelector('input[name="rule_settings[weekend_start_time]"]').value = '09:00';
    document.querySelector('input[name="rule_settings[weekend_end_time]"]').value = '17:00';
    document.querySelector('select[name="rule_settings[weekend_working_policy]"]').value = 'optional';

    // Check relevant checkboxes
    var checkboxesToCheck = [
        'rule_settings[weekend_requires_approval]',
        'rule_settings[notify_weekend_supervisor]',
        'rule_settings[notify_hr_weekends]',
        'rule_settings[weekend_regular_pay]',
        'rule_settings[weekend_overtime_compensation]'
    ];

    checkboxesToCheck.forEach(function(selector) {
        var checkbox = document.querySelector('input[name="' + selector + '"]');
        if (checkbox) {
            checkbox.checked = true;
        }
    });

    // Uncheck certain checkboxes
    var checkboxesToUncheck = [
        'rule_settings[weekend_affects_leaves]'
    ];

    checkboxesToUncheck.forEach(function(selector) {
        var checkbox = document.querySelector('input[name="' + selector + '"]');
        if (checkbox) {
            checkbox.checked = false;
        }
    });

    alert('Standard weekend policy settings loaded successfully!');
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
