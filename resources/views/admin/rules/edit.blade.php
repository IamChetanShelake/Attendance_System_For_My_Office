@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>Edit Office Rule
                </h1>
                <p class="page-subtitle">Modify existing office rule configuration and settings</p>
            </div>

            <!-- Restore Rules From Backup (if available) -->
            <div class="section-card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Rule Type: {{ $rule->rule_type_name }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="alert alert-info mb-4">
                                <h6><i class="fas fa-arrow-up-right-from-square me-2"></i>Editing: {{ $rule->rule_name }}</h6>
                                <p class="mb-2">
                                    <strong>Rule Type:</strong> {{ $rule->rule_type_name }}<br>
                                    <strong>Category:</strong> {{ $rule->category_name }}<br>
                                    <strong>Status:</strong> {!! $rule->status_badge !!}
                                </p>

                                @if($rule->isCurrentlyEffective())
                                    <span class="badge bg-success">✓ Currently Effective</span>
                                @else
                                    <span class="badge bg-secondary">✗ Not Currently Effective</span>
                                @endif
                            </div>

                            <div class="text-center mb-4">
                                <div class="btn-group btn-group-lg" role="group">
                                    <button type="button" onclick="navigateToRuleForm()" class="btn btn-primary">
                                        <i class="fas fa-edit me-2"></i>Continue Editing
                                    </button>
                                    <a href="{{ route('admin.office-rules.show', ['office_rule' => $rule->id]) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-eye me-1"></i>View Rule
                                    </a>
                                    <a href="{{ route('admin.office-rules.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-list me-1"></i>Back to List
                                    </a>
                                </div>
                            </div>

                            <div class="alert alert-secondary">
                                <h6 class="alert-heading">
                                    <i class="fas fa-info-triangle me-1"></i>Edit Options
                                </h6>
                                <div class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="editType" id="typeSpecific" checked>
                                        <label class="form-check-label" for="typeSpecific">
                                            <strong>Type-Specific Editor</strong>
                                            <p class="text-muted small mb-0">Use the custom form for this rule type (recommended)</p>
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="editType" id="genericEditor">
                                        <label class="form-check-label" for="genericEditor">
                                            <strong>Generic Editor</strong>
                                            <p class="text-muted small mb-0">Manual JSON editing for advanced configurations</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Rule Quick Stats -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Rule Statistics</h6>
                                </div>
                                <div class="card-body">
                                    <div class="small">
                                        <strong>Created:</strong>
                                        <br>{{ $rule->created_at->format('M j, Y g:i A') }}
                                        <br>
                                        <strong>Last Updated:</strong>
                                        <br>{{ $rule->updated_at->format('M j, Y g:i A') }}
                                        <br>
                                        <strong>Priority:</strong> {{ $rule->priority ?: 'Default' }}
                                        <br>
                                        @if($rule->effective_from)
                                            <strong>Effective From:</strong>
                                            <br>{{ $rule->effective_from->format('M j, Y') }}
                                        @endif
                                        @if($rule->effective_to)
                                            <strong>Effective To:</strong>
                                            <br>{{ $rule->effective_to->format('M j, Y') }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-tools me-2"></i>Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-sm btn-outline-info" onclick="duplicateRule()">
                                            <i class="fas fa-copy me-1"></i>Duplicate Rule
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="exportRule()">
                                            <i class="fas fa-download me-1"></i>Export Configuration
                                        </button>
                                        @if($rule->is_active)
                                            <a href="{{ route('admin.office-rules.toggle-status', ['rule' => $rule->id]) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               onclick="return confirm('Deactivate this rule?')">
                                                <i class="fas fa-pause me-1"></i>Deactivate
                                            </a>
                                        @else
                                            <a href="{{ route('admin.office-rules.toggle-status', ['rule' => $rule->id]) }}"
                                               class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-play me-1"></i>Activate
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Check for available edit form and navigate accordingly
document.addEventListener('DOMContentLoaded', function() {
    // Auto-navigate if user hasn't explicitly chosen generic editor
    if (!localStorage.getItem('ruleEditPreference') || localStorage.getItem('ruleEditPreference') === 'typeSpecific') {
        setTimeout(function() {
            navigateToRuleForm();
        }, 2500); // Auto-navigate after 2.5 seconds
    }
});

function navigateToRuleForm() {
    const ruleType = '{{ $rule->rule_type }}';
    const ruleId = '{{ $rule->id }}';

    const selectedType = document.querySelector('input[name="editType"]:checked').id;

    localStorage.setItem('ruleEditPreference', selectedType === 'typeSpecific' ? 'typeSpecific' : 'generic');

    if (selectedType === 'genericEditor') {
        // For now, redirect to a JSON editor or show an error that it's not available
        showGenericEditor(ruleType, ruleId);
        return;
    }

    // Since all rules use the same edit route pattern, we can simplify the redirection
    // Just navigate to the current edit URL which will handle the specific form display
    // The edit controller method (OfficeRulesController@edit) will handle routing to the correct view
    window.location.reload(); // This will cause the controller to show the correct edit form
}

function showGenericEditor(ruleType, ruleId) {
    // Show a modal or notification that specific form is not available
    document.getElementById('mainContent').innerHTML = `
        <div class="section-card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Specific Edit Form Not Available
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="alert alert-info mb-4">
                    <h5><i class="fas fa-code me-2"></i>Advanced Configuration Mode</h5>
                    <p>The specific edit form for rule type <strong>"<span id="ruleTypeText"></span>"</strong> is still under development.</p>
                    <p>This generic editor allows you to modify the raw JSON configuration.</p>
                </div>

                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <form method="POST" action="{{ route('admin.office-rules.update', ['office_rule' => $rule->id]) }}">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="rule_type" value="{{ $rule->rule_type }}">
                            <input type="hidden" name="rule_category" value="{{ $rule->rule_category }}">
                            <input type="hidden" name="is_active" value="{{ $rule->is_active }}">
                            <input type="hidden" name="priority" value="{{ $rule->priority }}">

                            <div class="form-group">
                                <label for="rule_name" class="form-label fw-bold">Rule Name</label>
                                <input type="text" class="form-control form-control-lg"
                                       name="rule_name" value="{{ $rule->rule_name }}" required>
                            </div>

                            <div class="form-group">
                                <label for="rule_description" class="form-label fw-bold">Description</label>
                                <textarea class="form-control" name="rule_description" rows="3">{{ $rule->rule_description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label fw-bold">JSON Configuration</label>
                                <textarea class="form-control font-monospace" rows="20" name="rule_settings" required
                                          placeholder='{"key": "value"}'>{{ json_encode($rule->rule_settings, JSON_PRETTY_PRINT) }}</textarea>
                                <small class="text-muted">Enter valid JSON configuration for this rule.</small>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="history.back()">
                                    <i class="fas fa-arrow-left me-1"></i>Go Back
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function duplicateRule() {
    if (confirm('Create a duplicate of this rule?')) {
        const createUrl = '{{ route("admin.office-rules.create") }}';
        const ruleId = '{{ $rule->id }}';
        window.location.href = createUrl + '?duplicate=' + ruleId;
    }
}

function exportRule() {
    const settingsJson = '{{ json_encode($rule->rule_settings) }}';
    const settings = JSON.parse(settingsJson);

    const exportData = {
        rule_name: '{{ json_encode($rule->rule_name) }}',
        rule_type: '{{ json_encode($rule->rule_type) }}',
        rule_category: '{{ json_encode($rule->rule_category) }}',
        rule_description: '{{ json_encode($rule->rule_description) }}',
        priority: '{{ json_encode($rule->priority) }}',
        settings: settings,
        exported_at: new Date().toISOString(),
        exported_by: 'System'
    };

    const dataStr = JSON.stringify(exportData, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

    const exportFileDefaultName = 'office-rule-' + '{{ $rule->id }}' + '-' + '{{ $rule->rule_type }}' + '.json';

    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
}

// Add click handler for radio buttons
document.addEventListener('change', function(e) {
    if (e.target.name === 'editType') {
        // Highlight selected option
        document.querySelectorAll('input[name="editType"]').forEach(radio => {
            const label = radio.parentElement;
            if (radio.checked) {
                label.classList.add('text-primary', 'fw-bold');
            } else {
                label.classList.remove('text-primary', 'fw-bold');
            }
        });
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) => {
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

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.btn-group-lg .btn {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.5rem;
}

.form-check-label {
    transition: all 0.3s ease;
    cursor: pointer;
}

.form-check-label:hover {
    color: var(--primary-color);
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

.font-monospace {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
}
</style>
@endsection
