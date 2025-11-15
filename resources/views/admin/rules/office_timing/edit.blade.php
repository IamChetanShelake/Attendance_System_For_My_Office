@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>Edit Office Timing Policy: {{ $rule->rule_name }}
                </h1>
                <p class="page-subtitle">Modify office hours, breaks, and enforcement settings</p>
            </div>

            <!-- Main Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Office Timing Policy Configuration
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
                                    <small class="form-text text-muted">Choose a clear, descriptive name for this timing policy</small>
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
                                           value="{{ old('priority', $rule->priority ?? 10) }}"
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
                                              placeholder="Describe this office timing policy...">{{ old('rule_description', $rule->rule_description) }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Provide details about working hours and expectations
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

                        <!-- Timing Configuration -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-clock me-2"></i>Office Hours Configuration
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Start Time</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[start_time]"
                                           value="{{ old('rule_settings.start_time', $rule->rule_settings['start_time'] ?? '09:00') }}"
                                           required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">End Time</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[end_time]"
                                           value="{{ old('rule_settings.end_time', $rule->rule_settings['end_time'] ?? '18:00') }}"
                                           required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Working Hours</label>
                                    <input type="number" class="form-control"
                                           name="rule_settings[working_hours]"
                                           value="{{ old('rule_settings.working_hours', $rule->rule_settings['working_hours'] ?? 8) }}"
                                           min="1" max="24" step="0.5"
                                           required>
                                    <small class="text-muted">Total working hours per day</small>
                                </div>
                            </div>
                        </div>

                        <!-- Break Configuration -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-mug-hot me-2"></i>Break Times
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Break Start Time</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[break_start]"
                                           value="{{ old('rule_settings.break_start', $rule->rule_settings['break_start'] ?? '13:00') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Break End Time</label>
                                    <input type="time" class="form-control"
                                           name="rule_settings[break_end]"
                                           value="{{ old('rule_settings.break_end', $rule->rule_settings['break_end'] ?? '14:00') }}">
                                </div>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>Leave empty if no official break time
                            </small>
                        </div>

                        <!-- Policy Settings -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-cog me-2"></i>Policy Settings
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Grace Period (minutes)</label>
                                    <input type="number" class="form-control" min="0" max="120"
                                           name="rule_settings[grace_period_minutes]"
                                           value="{{ old('rule_settings.grace_period_minutes', $rule->rule_settings['grace_period_minutes'] ?? 15) }}">
                                    <small class="text-muted">Buffer time before marking as late</small>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="rule_settings[strict_timing]"
                                               value="1"
                                               {{ old('rule_settings.strict_timing', ($rule->rule_settings['strict_timing'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Strict Timing Enforcement
                                        </label>
                                    </div>
                                    <small class="text-muted">No grace period for punctual check-in</small>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="rule_settings[require_card_scan]"
                                               value="1"
                                               {{ old('rule_settings.require_card_scan', ($rule->rule_settings['require_card_scan'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Require Card/Biometric Scan
                                        </label>
                                    </div>
                                    <small class="text-muted">Mandate physical check-in/out</small>
                                </div>
                            </div>
                        </div>

                        <!-- Exceptions and Notes -->
                        <div class="settings-card mb-4">
                            <h6 class="settings-title">
                                <i class="fas fa-calendar-alt me-2"></i>Special Notes & Exceptions
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Additional Comments</label>
                                    <textarea class="form-control" name="rule_settings[additional_notes]" rows="3"
                                              placeholder="Any special conditions, exceptions, or notes...">{{ old('rule_settings.additional_notes', $rule->rule_settings['additional_notes'] ?? '') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Flexible Hours Option</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[allow_flexible_hours]" value="1"
                                               {{ old('rule_settings.allow_flexible_hours', ($rule->rule_settings['allow_flexible_hours'] ?? false)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow Flexible Hours
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[allow_early_leaving]" value="1"
                                               {{ old('rule_settings.allow_early_leaving', ($rule->rule_settings['allow_early_leaving'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow Early Leaving
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="rule_settings[allow_half_day]" value="1"
                                               {{ old('rule_settings.allow_half_day', ($rule->rule_settings['allow_half_day'] ?? true)) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold">
                                            Allow Half-Day Requests
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
                                            <i class="fas fa-save me-2"></i>Update Office Timing Policy
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

// Character counter
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
                <strong>Office Hours:</strong> ${settings.start_time || 'N/A'} - ${settings.end_time || 'N/A'} (${settings.working_hours || 'N/A'} hours)<br>
                <strong>Break Time:</strong> ${settings.break_start || 'N/A'} - ${settings.break_end || 'N/A'}<br>
                <strong>Grace Period:</strong> ${settings.grace_period_minutes || 'N/A'} minutes<br>
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

    // Load standard office timing settings
    document.querySelector('input[name="rule_settings[start_time]"]').value = '09:00';
    document.querySelector('input[name="rule_settings[end_time]"]').value = '18:00';
    document.querySelector('input[name="rule_settings[working_hours]"]').value = '8';
    document.querySelector('input[name="rule_settings[break_start]"]').value = '13:00';
    document.querySelector('input[name="rule_settings[break_end]"]').value = '14:00';
    document.querySelector('input[name="rule_settings[grace_period_minutes]"]').value = '15';

    // Check relevant checkboxes
    const checkboxesToCheck = [
        'rule_settings[require_card_scan]',
        'rule_settings[allow_early_leaving]',
        'rule_settings[allow_half_day]'
    ];

    checkboxesToCheck.forEach(function(selector) {
        var checkbox = document.querySelector('input[name="' + selector + '"]');
        if (checkbox) {
            checkbox.checked = true;
        }
    });

    // Uncheck certain checkboxes
    var checkboxesToUncheck = [
        'rule_settings[strict_timing]',
        'rule_settings[allow_flexible_hours]'
    ];

    checkboxesToUncheck.forEach(function(selector) {
        var checkbox = document.querySelector('input[name="' + selector + '"]');
        if (checkbox) {
            checkbox.checked = false;
        }
    });

    alert('Standard office timing settings loaded successfully!');
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
