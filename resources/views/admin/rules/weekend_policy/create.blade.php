@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-calendar-week me-2"></i>Create Weekend Policy
                </h1>
                <p class="page-subtitle">Configure Saturday working conditions and weekend holiday policies</p>
            </div>

            <!-- Main Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Weekend Policy Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.office-rules.store') }}" id="ruleForm">
                        @csrf
                        <input type="hidden" name="rule_type" value="weekend_policy">
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
                                           value="{{ old('rule_name', 'Weekend Policy - ' . date('M Y')) }}"
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
                                           value="{{ old('priority', 6) }}"
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
                                    <small class="form-text text-muted">Inactive rules won't be applied</small>
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
                                              placeholder="Describe this weekend policy and Saturday working arrangements...">{{ old('rule_description') }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Explain weekend policies and Saturday working conditions
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

                        <!-- Saturday Working Configuration -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calendar-day me-2"></i>Saturday Working Policy
                            </h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Policy Overview:</strong> Saturdays are typically flexible. Configure which weeks require Saturday work and which allow holidays.
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">2nd Saturday Policy</label>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="rule_settings[second_saturday_off]"
                                               value="1"
                                               id="second_sat"
                                               {{ old('rule_settings.second_saturday_off', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="second_sat">
                                            <strong>2nd Saturday is OFF day</strong>
                                        </label>
                                    </div>
                                    <small class="text-muted mb-2 d-block">If enabled, 2nd Saturday of each month is a holiday</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">4th Saturday Policy</label>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="rule_settings[fourth_saturday_off]"
                                               value="1"
                                               id="fourth_sat"
                                               {{ old('rule_settings.fourth_saturday_off', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fourth_sat">
                                            <strong>4th Saturday is OFF day</strong>
                                        </label>
                                    </div>
                                    <small class="text-muted mb-2 d-block">If enabled, 4th Saturday of each month is a holiday</small>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="working-saturday-info bg-light p-3 rounded">
                                        <h6><i class="fas fa-calendar-check me-2"></i>Working Saturdays Schedule</h6>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Based on your selections:</strong>
                                                <ul class="mb-1">
                                                    <li>1st Saturday: <span class="text-success">Working</span></li>
                                                    <li>3rd Saturday: <span class="text-success">Working</span></li>
                                                    <li>5th Saturday: <span class="text-success">Working</span></li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Conditional Holidays:</strong>
                                                <ul class="mb-1">
                                                    <li>2nd Saturday: <span class="conditional-status text-warning">Conditional</span></li>
                                                    <li>4th Saturday: <span class="conditional-status text-warning">Conditional</span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Weekend Holiday Policy -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calendar-alt me-2"></i>Weekend Holiday Policy
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Sunday Policy</label>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="rule_settings[sunday_off]"
                                               value="1"
                                               id="sunday_off"
                                               {{ old('rule_settings.sunday_off', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sunday_off">
                                            <strong>Sunday is OFF day</strong>
                                        </label>
                                    </div>
                                    <small class="text-muted">All Sundays are considered holidays</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Holiday Fallback Policy</label>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="rule_settings[holiday_if_falls_on_sunday]"
                                               value="1"
                                               id="holiday_fallback"
                                               {{ old('rule_settings.holiday_if_falls_on_sunday', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="holiday_fallback">
                                            <strong>Holiday if any holiday falls on Sunday</strong>
                                        </label>
                                    </div>
                                    <small class="text-muted">If a holiday falls on Sunday, following Monday becomes holiday</small>
                                </div>
                            </div>
                        </div>

                        <!-- Saturday Working Hours (when working) -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-clock me-2"></i>Saturday Working Hours
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Saturday Start Time</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[saturday_start_time]"
                                           value="{{ old('rule_settings.saturday_start_time', '09:00') }}">
                                    <small class="text-muted">When Saturday is a working day</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Saturday End Time</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[saturday_end_time]"
                                           value="{{ old('rule_settings.saturday_end_time', '17:00') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Saturday Working Hours</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[saturday_working_hours]"
                                           value="{{ old('rule_settings.saturday_working_hours', 6) }}"
                                           min="1" max="12" step="0.5">
                                    <small class="text-muted">Typical hours worked on Saturdays</small>
                                </div>
                            </div>
                        </div>

                        <!-- Overtime & Compensation -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-money-bill-wave me-2"></i>Saturday Overtime & Compensation
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Saturday Overtime Rate</label>
                                    <input type="number" class="form-control" step="0.1"
                                           name="rule_settings[saturday_ot_rate]"
                                           value="{{ old('rule_settings.saturday_ot_rate', 1.5) }}"
                                           min="1.0" max="3.0">
                                    <small class="text-muted">Multiplier for overtime work</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Minimum OT Hours</label>
                                    <input type="number" class="form-control" step="0.5"
                                           name="rule_settings[min_ot_hours]"
                                           value="{{ old('rule_settings.min_ot_hours', 2) }}"
                                           min="0.5" max="8">
                                    <small class="text-muted">Minimum hours for OT calculation</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Approval Required</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="rule_settings[saturday_ot_approval_required]"
                                               value="1"
                                               {{ old('rule_settings.saturday_ot_approval_required', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Saturday OT needs approval
                                        </label>
                                    </div>
                                    <small class="text-muted">Whether overtime work requires supervisor approval</small>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Integration -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-clipboard-check me-2"></i>Attendance & Leave Integration
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Saturday Attendance Rules</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[saturday_attendance_mandatory]"
                                               value="1"
                                               {{ old('rule_settings.saturday_attendance_mandatory', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Attendance mandatory on working Saturdays
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[saturday_late_marks_apply]"
                                               value="1"
                                               {{ old('rule_settings.saturday_late_marks_apply', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Late mark rules apply to Saturdays
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[saturday_count_as_full_day]"
                                               value="1"
                                               {{ old('rule_settings.saturday_count_as_full_day', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Saturday counts as full workday for monthly totals
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Leave Considerations</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[saturday_leave_allowed]"
                                               value="1"
                                               {{ old('rule_settings.saturday_leave_allowed', false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Leave can be applied for working Saturdays
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[saturday_compensation_overtime]"
                                               value="1"
                                               {{ old('rule_settings.saturday_compensation_overtime', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Comp-time/overtime options available
                                        </label>
                                    </div>
                                    <small class="text-muted">Leave and overtime policies for Saturdays</small>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-sticky-note me-2"></i>Additional Policy Notes
                            </h6>
                            <textarea class="form-control" name="rule_settings[additional_notes]" rows="4"
                                      placeholder="Any additional policy notes, exceptions, or special considerations for weekend work...">{{ old('rule_settings.additional_notes') }}</textarea>
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
                                            <i class="fas fa-save me-2"></i>Create Weekend Policy
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
// Character counter and status handler
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
    updateSaturdayInfo(); // Update conditional status indicators

    // Listen for Saturday checkbox changes
    ['second_sat', 'fourth_sat'].forEach(id => {
        const checkbox = document.getElementById(id);
        if (checkbox) {
            checkbox.addEventListener('change', updateSaturdayInfo);
        }
    });
});

function updateDescriptionCount() {
    const textarea = document.getElementById('rule_description');
    if (textarea) {
        document.getElementById('descriptionCount').textContent = textarea.value.length;
    }
}

function updateSaturdayInfo() {
    const secondOff = document.getElementById('second_sat').checked;
    const fourthOff = document.getElementById('fourth_sat').checked;

    const secondStatus = document.querySelector('.conditional-status');
    if (secondStatus) {
        secondStatus.textContent = secondOff ? 'Holiday' : 'Working';
        secondStatus.className = secondOff ? 'conditional-status text-danger' : 'conditional-status text-success';
    }
    const fourthStatus = document.querySelectorAll('.conditional-status')[1];
    if (fourthStatus) {
        fourthStatus.textContent = fourthOff ? 'Holiday' : 'Working';
        fourthStatus.className = fourthOff ? 'conditional-status text-danger' : 'conditional-status text-success';
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
                <strong>Working Saturdays:</strong> 1st, 3rd, 5th<br>
                <strong>2nd Saturday Off:</strong> ${settings.second_saturday_off ? 'Yes' : 'No'}<br>
                <strong>4th Saturday Off:</strong> ${settings.fourth_saturday_off ? 'Yes' : 'No'}<br>
                <strong>Sunday Off:</strong> ${settings.sunday_off ? 'Yes' : 'No'}<br>
                <strong>Holiday Fallback:</strong> ${settings.holiday_if_falls_on_sunday ? 'Yes' : 'No'}<br>
                <strong>Saturday Hours:</strong> ${settings.saturday_start_time || 'N/A'} - ${settings.saturday_end_time || 'N/A'}<br>
                <strong>Saturday OT Rate:</strong> ${settings.saturday_ot_rate || 'N/A'}x<br>
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
    // Load standard weekend policy settings
    document.getElementById('second_sat').checked = true;
    document.getElementById('fourth_sat').checked = true;
    document.getElementById('sunday_off').checked = true;
    document.getElementById('holiday_fallback').checked = true;
    document.querySelector('input[name="rule_settings[saturday_start_time]"]').value = '09:00';
    document.querySelector('input[name="rule_settings[saturday_end_time]"]').value = '17:00';
    document.querySelector('input[name="rule_settings[saturday_working_hours]"]').value = '6';
    document.querySelector('input[name="rule_settings[saturday_ot_rate]"]').value = '1.5';

    updateSaturdayInfo();
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

.working-saturday-info {
    border-left: 4px solid var(--primary-color);
}

.conditional-status {
    font-weight: 600;
}
</style>
@endsection
