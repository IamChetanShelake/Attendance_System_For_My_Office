@extends('admin.layout.master')

@section('content')
<main>
    <!-- Hidden JSON data for JavaScript -->
    <div id="rule-settings-json" style="display: none;">{{ $rule->rule_settings ? json_encode($rule->rule_settings) : '{}' }}</div>

    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-{{ getRuleIcon($rule->rule_type) }} me-2"></i>
                        {{ $rule->rule_name }}
                    </h1>
                    <p class="page-subtitle">Detailed view of office rule configuration and settings</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.office-rules.edit', ['office_rule' => $rule]) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit Rule
                    </a>
                                    <form method="POST" action="{{ route('admin.office-rules.toggle-status', ['rule' => $rule]) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning">
                            <i class="fas fa-{{ $rule->is_active ? 'pause' : 'play' }} me-1"></i>
                            {{ $rule->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.office-rules.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Rules
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Rule Overview -->
                <div class="col-md-8">
                    <div class="section-card mb-4">
                        <div class="card-header bg-gradient-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Rule Overview
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="info-label">
                                            <i class="fas fa-tag me-2"></i>Rule Name
                                        </label>
                                        <div class="info-value">{{ $rule->rule_name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="info-label">
                                            <i class="fas fa-code me-2"></i>Rule Type
                                        </label>
                                        <div class="info-value">
                                            <span class="badge bg-info">{{ $rule->rule_type_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="info-label">
                                            <i class="fas fa-folder me-2"></i>Category
                                        </label>
                                        <div class="info-value">{{ $rule->category_name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="info-label">
                                            <i class="fas fa-sort-numeric-up me-2"></i>Priority Level
                                        </label>
                                        <div class="info-value">{{ $rule->priority ?: 'Default (0)' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <label class="info-label">
                                            <i class="fas fa-toggle-on me-2"></i>Status
                                        </label>
                                        <div class="info-value">
                                            {!! $rule->status_badge !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if($rule->effective_period !== 'Always')
                                        <div class="info-item">
                                            <label class="info-label">
                                                <i class="fas fa-calendar-alt me-2"></i>Effective Period
                                            </label>
                                            <div class="info-value">{{ $rule->effective_period }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($rule->rule_description)
                                <div class="mb-4">
                                    <label class="info-label mb-2">
                                        <i class="fas fa-file-text me-2"></i>Description
                                    </label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <p class="mb-0">{{ $rule->rule_description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Rule Specific Settings -->
                            <div class="settings-display mb-4">
                                <label class="info-label mb-3">
                                    <i class="fas fa-cogs me-2"></i>Rule Configuration
                                </label>
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">{{ $rule->rule_type_name }} Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        @switch($rule->rule_type)
                                            @case('office_timing')
                                                @php $settings = $rule->rule_settings; @endphp
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-clock me-2"></i>Start Time:</strong>
                                                            {{ $settings['start_time'] ?? 'N/A' }}
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-clock me-2"></i>End Time:</strong>
                                                            {{ $settings['end_time'] ?? 'N/A' }}
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-clock me-2"></i>Working Hours:</strong>
                                                            {{ $settings['working_hours'] ?? 'N/A' }} hours
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-clock me-2"></i>Break Start:</strong>
                                                            {{ $settings['break_start'] ?? 'N/A' }}
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-clock me-2"></i>Break End:</strong>
                                                            {{ $settings['break_end'] ?? 'N/A' }}
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-clock me-2"></i>Grace Period:</strong>
                                                            {{ $settings['grace_period_minutes'] ?? 'N/A' }} minutes
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('late_mark')
                                                @php $settings = $rule->rule_settings; @endphp
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-clock me-2"></i>Late Threshold:</strong>
                                                            {{ $settings['late_threshold_minutes'] ?? 'N/A' }} minutes
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-clock me-2"></i>Half-day after:</strong>
                                                            {{ $settings['half_day_threshold_time'] ?? 'N/A' }}
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-clock me-2"></i>Auto half-day if late beyond:</strong>
                                                            {{ $settings['auto_half_day_threshold'] ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-users me-2"></i>Max Late Marks/Month:</strong>
                                                            {{ $settings['max_late_marks_per_month'] ?? 'N/A' }}
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-gavel me-2"></i>Action on Exceed:</strong>
                                                            {{ $settings['action_on_exceed'] ?? 'N/A' }}
                                                        </div>
                                                        <div class="mb-0">
                                                            <strong><i class="fas fa-money-bill me-2"></i>Deduction Percentage:</strong>
                                                            {{ $settings['deduction_percentage'] ?? 'N/A' }}%
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('weekend_policy')
                                                @php $settings = $rule->rule_settings; @endphp
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-calendar-week me-2"></i>2nd Saturday OFF:</strong>
                                                            {{ ($settings['second_saturday_off'] ?? false) ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' }}
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-calendar-week me-2"></i>4th Saturday OFF:</strong>
                                                            {{ ($settings['fourth_saturday_off'] ?? false) ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-calendar-week me-2"></i>Sunday OFF:</strong>
                                                            {{ ($settings['sunday_off'] ?? true) ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' }}
                                                        </div>
                                                        <div class="mb-0">
                                                            <strong><i class="fas fa-calendar-week me-2"></i>Holiday before Sunday:</strong>
                                                            {{ ($settings['holiday_if_falls_on_sunday'] ?? true) ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('consecutive_leave')
                                                @php $settings = $rule->rule_settings['weekend_consecutive'] ?? []; @endphp
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h6 class="text-muted mb-3">Weekend Combinations Policy</h6>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-link me-2"></i>Weekend Consecutive Enabled:</strong>
                                                            {{ ($settings['enabled'] ?? true) ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' }}
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-link me-2"></i>Sunday to Monday:</strong>
                                                            {{ ($settings['sunday_to_monday'] ?? true) ? '<span class="text-success">2 days consecutive</span>' : 'Not consecutive' }}
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-link me-2"></i>Saturday (working) to Monday:</strong>
                                                            {{ ($settings['saturday_to_monday'] ?? true) ? '<span class="text-success">2 days consecutive</span>' : 'Not consecutive' }}
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong><i class="fas fa-link me-2"></i>Friday to Saturday (working):</strong>
                                                            {{ ($settings['friday_to_saturday'] ?? false) ? '<span class="text-success">2 days consecutive</span>' : 'Not consecutive' }}
                                                        </div>
                                                        <div class="mb-0">
                                                            <strong><i class="fas fa-link me-2"></i>Maximum Consecutive Days:</strong>
                                                            {{ $settings['max_consecutive_days'] ?? 2 }} days
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @default
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Custom rule configuration: <pre>{{ json_encode($rule->rule_settings, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                        @endswitch
                                    </div>
                                </div>
                            </div>

                            <!-- Manage Actions -->
                            <div class="action-buttons">
                                <label class="info-label mb-2">
                                    <i class="fas fa-tools me-2"></i>Management Actions
                                </label>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('admin.office-rules.edit', ['office_rule' => $rule]) }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-1"></i>Edit Rule
                                    </a>
                                    <form method="POST" action="{{ route('admin.office-rules.toggle-status', ['rule' => $rule]) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning"
                                                onclick="return confirm('{{ $rule->is_active ? 'Deactivate' : 'Activate' }} this rule?')">
                                            <i class="fas fa-{{ $rule->is_active ? 'pause' : 'play' }} me-1"></i>
                                            {{ $rule->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.office-rules.destroy', ['office_rule' => $rule]) }}"
                                          class="d-inline"
                                          onsubmit="return confirmDelete('{{ $rule->rule_name }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Delete Rule
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metadata Sidebar -->
                <div class="col-md-4">
                    <div class="section-card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                Metadata
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Rule Statistics -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-chart-bar me-1"></i>Usage Statistics
                                </h6>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="small-stat">
                                            <div class="stat-number">{{ $rule->isCurrentlyEffective() ? 'Active' : 'Inactive' }}</div>
                                            <div class="stat-label">Current Status</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small-stat">
                                            <div class="stat-number">{{ $rule->rule_type }}</div>
                                            <div class="stat-label">Rule Type</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Audit Information -->
                            <div class="audit-info">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-user-clock me-1"></i>Audit Trail
                                </h6>

                                <div class="mb-3">
                                    <label class="small fw-bold text-muted">Created</label>
                                    <div class="small">
                                        <i class="fas fa-calendar-plus me-1"></i>{{ $rule->created_at->format('M j, Y \a\t g:i A') }}
                                        <br>
                                        <i class="fas fa-building me-1"></i>Company Policy
                                    </div>
                                </div>

                                @if($rule->updated_at != $rule->created_at)
                                    <div class="mb-3">
                                        <label class="small fw-bold text-muted">Last Updated</label>
                                        <div class="small">
                                            <i class="fas fa-calendar-edit me-1"></i>{{ $rule->updated_at->format('M j, Y \a\t g:i A') }}
                                            <br>
                                            <i class="fas fa-building me-1"></i>Company Policy
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            <!-- Quick Actions -->
                            <div class="quick-actions">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-bolt me-1"></i>Quick Actions
                                </h6>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-sm btn-outline-info"
                                            onclick="exportRule()">
                                        <i class="fas fa-download me-1"></i>Export Configuration
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary"
                                            onclick="duplicateRule()">
                                        <i class="fas fa-copy me-1"></i>Duplicate Rule
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rule Dependencies (if any) -->
                    <div class="section-card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-link me-2"></i>
                                Related Rules
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $relatedRules = \App\Models\OfficeRule::where('rule_category', $rule->rule_category)
                                    ->where('id', '!=', $rule->id)
                                    ->where('is_active', true)
                                    ->take(3)
                                    ->get();
                            @endphp

                            @if($relatedRules->count() > 0)
                                <div class="sms">
                                    <p class="small text-muted mb-2">Other active rules in the same category:</p>
                                    @foreach($relatedRules as $relatedRule)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small">{{ Str::limit($relatedRule->rule_name, 25) }}</span>
                                            <a href="{{ route('admin.office-rules.show', ['office_rule' => $relatedRule]) }}" class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="small text-muted mb-0">
                                    <i class="fas fa-info-circle me-1"></i>No other rules in this category.
                                </p>
                            @endif
                        </div>
                    </div>
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

@php
    function getRuleIcon($ruleType) {
        return match($ruleType) {
            'office_timing' => 'clock',
            'late_mark' => 'exclamation-triangle',
            'half_day' => 'stopwatch',
            'weekend_policy' => 'calendar-week',
            'consecutive_leave' => 'link',
            'holiday_consecutive' => 'calendar-alt',
            default => 'cog'
        };
    }
@endphp

<script>
function confirmDelete(ruleName) {
    return confirm(`Are you sure you want to permanently delete the rule "${ruleName}"?\n\nThis action cannot be undone and may affect other systems.`);
}

function exportRule() {
    // Get settings from a hidden div with proper JSON
    const settingsJson = document.getElementById('rule-settings-json').textContent.trim();
    const settings = JSON.parse(settingsJson);

    const exportData = {
        rule_name: "{{ $rule->rule_name }}",
        rule_type: "{{ $rule->rule_type }}",
        rule_category: "{{ $rule->rule_category }}",
        rule_description: "{{ $rule->rule_description }}",
        settings: settings,
        exported_at: new Date().toISOString()
    };

    const dataStr = JSON.stringify(exportData, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

    const exportFileDefaultName = 'office-rule-' + '{{ $rule->id }}' + '-' + '{{ $rule->rule_type }}' + '.json';

    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
}

function duplicateRule() {
    if (confirm('Create a duplicate of this rule?')) {
        const createUrl = '{{ route("admin.office-rules.create") }}';
        const ruleId = '{{ $rule->id }}';
        window.location.href = createUrl + '?duplicate=' + ruleId;
    }
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

.info-item {
    margin-bottom: 1rem;
}

.info-label {
    display: block;
    font-weight: 600;
    color: var(--secondary-color);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.info-value {
    font-size: 1rem;
    color: #495057;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.settings-display .card {
    border-radius: 10px;
    overflow: hidden;
}

.settings-display .card-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a9bc7 100%) !important;
    border: none;
    font-weight: 600;
}

.small-stat {
    padding: 1rem 0.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 0.5rem;
}

.small-stat .stat-number {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
}

.small-stat .stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 500;
}

.audit-info .small {
    color: #6c757d;
    line-height: 1.4;
}

.quick-actions .btn {
    border-radius: 6px;
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
