@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-calendar-week me-2"></i>{{ $rule->rule_name }}
                </h1>
                <p class="page-subtitle">Consecutive leave policy configuration and weekend leave restrictions</p>
            </div>

            <!-- Main Content -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Consecutive Leave Rule Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="info-card mb-4">
                                <h6 class="info-title">
                                    <i class="fas fa-file-alt me-2"></i>Basic Information
                                </h6>
                                <div class="info-content">
                                    <div class="info-item">
                                        <strong>Rule Name:</strong> {{ $rule->rule_name }}
                                    </div>
                                    <div class="info-item">
                                        <strong>Rule Type:</strong> {{ $rule->rule_type_name }}
                                    </div>
                                    <div class="info-item">
                                        <strong>Category:</strong> {{ $rule->category_name }}
                                    </div>
                                    <div class="info-item">
                                        <strong>Status:</strong> {!! $rule->status_badge !!}
                                    </div>
                                    <div class="info-item">
                                        <strong>Priority:</strong> {{ $rule->priority ?: 'Default' }}
                                    </div>
                                    @if($rule->rule_description)
                                    <div class="info-item">
                                        <strong>Description:</strong>
                                        <p class="mb-0">{{ $rule->rule_description }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Creator & Updater Info -->
                            <div class="info-card mb-4">
                                <h6 class="info-title">
                                    <i class="fas fa-users me-2"></i>Audit Information
                                </h6>
                                <div class="info-content">
                                    <div class="info-item">
                                        <strong>Created:</strong> {{ $rule->created_at->format('M j, Y g:i A') }}
                                    </div>
                                    <div class="info-item">
                                        <strong>Created By:</strong> {{ $rule->creator ? $rule->creator->name : 'System' }}
                                    </div>
                                    <div class="info-item">
                                        <strong>Last Updated:</strong> {{ $rule->updated_at->format('M j, Y g:i A') }}
                                    </div>
                                    <div class="info-item">
                                        <strong>Updated By:</strong> {{ $rule->updater ? $rule->updater->name : 'System' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-6">
                            <div class="info-card mb-4">
                                <h6 class="info-title">
                                    <i class="fas fa-tools me-2"></i>Actions
                                </h6>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.office-rules.edit', ['office_rule' => $rule]) }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-1"></i>Edit Rule
                                    </a>
                                    <a href="{{ route('admin.office-rules.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-list me-1"></i>Back to Rules List
                                    </a>
                                    @if($rule->is_active)
                                        <a href="{{ route('admin.office-rules.toggle-status', ['rule' => $rule]) }}"
                                           class="btn btn-outline-warning"
                                           onclick="return confirm('Deactivate this rule?')">
                                            <i class="fas fa-pause me-1"></i>Deactivate Rule
                                        </a>
                                    @else
                                        <a href="{{ route('admin.office-rules.toggle-status', ['rule' => $rule]) }}"
                                           class="btn btn-outline-success">
                                            <i class="fas fa-play me-1"></i>Activate Rule
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Effectiveness -->
                            <div class="info-card mb-4">
                                <h6 class="info-title">
                                    <i class="fas fa-calendar-check me-2"></i>Effectiveness
                                </h6>
                                <div class="info-content">
                                    @if($rule->effective_from)
                                    <div class="info-item">
                                        <strong>Effective From:</strong> {{ $rule->effective_from->format('M j, Y') }}
                                    </div>
                                    @endif
                                    @if($rule->effective_to)
                                    <div class="info-item">
                                        <strong>Effective To:</strong> {{ $rule->effective_to->format('M j, Y') }}
                                    </div>
                                    @endif
                                    <div class="info-item">
                                        <strong>Current Status:</strong>
                                        @if($rule->isCurrentlyEffective())
                                            <span class="badge bg-success">✓ Currently Effective</span>
                                        @else
                                            <span class="badge bg-secondary">✗ Not Currently Effective</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Consecutive Leave Definitions -->
                    <div class="settings-display mb-4">
                        <h6 class="settings-title">
                            <i class="fas fa-calendar-week me-2"></i>Consecutive Leave Definitions
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="setting-item">
                                    <label class="setting-label">Sunday-Monday Consecutive</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['sunday_monday_consecutive']) && $rule->rule_settings['sunday_monday_consecutive'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="setting-item">
                                    <label class="setting-label">Friday-Saturday Consecutive</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['friday_saturday_consecutive']) && $rule->rule_settings['friday_saturday_consecutive'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="setting-item">
                                    <label class="setting-label">Include Holidays</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['include_holidays']) && $rule->rule_settings['include_holidays'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Restrictions -->
                    <div class="settings-display mb-4">
                        <h6 class="settings-title">
                            <i class="fas fa-ban me-2"></i>Leave Restrictions & Limits
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="setting-item">
                                    <label class="setting-label">Max Weekend Leaves/Month</label>
                                    <div class="setting-value">{{ ($rule->rule_settings['max_weekend_leaves_per_month'] ?? '2') . ' leaves' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="setting-item">
                                    <label class="setting-label">Minimum Notice Days</label>
                                    <div class="setting-value">{{ ($rule->rule_settings['min_notice_days'] ?? '3') . ' days' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="setting-item">
                                    <label class="setting-label">Max Pending Applications</label>
                                    <div class="setting-value">{{ ($rule->rule_settings['max_pending_weekend_leaves'] ?? '1') . ' applications' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approval & Information Requirements -->
                    <div class="settings-display mb-4">
                        <h6 class="settings-title">
                            <i class="fas fa-clipboard-check me-2"></i>Approval & Information Requirements
                        </h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Approval Required</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['weekend_leave_requires_approval']) && $rule->rule_settings['weekend_leave_requires_approval'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Require Reason</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['require_reason']) && $rule->rule_settings['require_reason'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Require Documentation</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['require_supporting_docs']) && $rule->rule_settings['require_supporting_docs'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Alternative Coverage</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['require_alternative_coverage']) && $rule->rule_settings['require_alternative_coverage'] ? 'Required' : 'Not Required' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications & Monitoring -->
                    <div class="settings-display mb-4">
                        <h6 class="settings-title">
                            <i class="fas fa-bell me-2"></i>Notifications & Monitoring
                        </h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Notify Supervisor</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['notify_supervisor_on_application']) && $rule->rule_settings['notify_supervisor_on_application'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Notify HR</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['notify_hr_on_weekend_leave']) && $rule->rule_settings['notify_hr_on_weekend_leave'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Notify Team Lead</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['notify_team_lead']) && $rule->rule_settings['notify_team_lead'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Send Reminders</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['send_reminders']) && $rule->rule_settings['send_reminders'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Consider Attendance History</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['consider_attendance_history']) && $rule->rule_settings['consider_attendance_history'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Flag Frequent Weekend Leaves</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['flag_frequent_weekend_leaves']) && $rule->rule_settings['flag_frequent_weekend_leaves'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Show Remaining Quota</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['show_remaining_quota']) && $rule->rule_settings['show_remaining_quota'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Auto-Reject Over Limit</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['auto_reject_over_limit']) && $rule->rule_settings['auto_reject_over_limit'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Special Conditions & Exemptions -->
                    <div class="settings-display mb-4">
                        <h6 class="settings-title">
                            <i class="fas fa-star me-2"></i>Special Conditions & Exemptions
                        </h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Emergency Override</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['allow_emergency_override']) && $rule->rule_settings['allow_emergency_override'] ? 'Allowed' : 'Not Allowed' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Senior Management Exemptions</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['exempt_senior_management']) && $rule->rule_settings['exempt_senior_management'] ? 'Exempted' : 'Not Exempted' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Carry Forward Unused Leaves</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['carry_forward_unused_leaves']) && $rule->rule_settings['carry_forward_unused_leaves'] ? 'Yes' : 'No' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="setting-item">
                                    <label class="setting-label">Quota Reset Frequency</label>
                                    <div class="setting-value">
                                        {{ isset($rule->rule_settings['reset_quota_yearly']) && $rule->rule_settings['reset_quota_yearly'] ? 'Yearly' : 'Monthly' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    @if(isset($rule->rule_settings['policy_notes']) && $rule->rule_settings['policy_notes'])
                    <div class="settings-display">
                        <h6 class="settings-title">
                            <i class="fas fa-sticky-note me-2"></i>Additional Policy Notes & Guidelines
                        </h6>
                        <p>{{ $rule->rule_settings['policy_notes'] }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #0069d9 100%) !important;
    color: white;
}

.info-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1.25rem;
}

.info-title {
    color: var(--secondary-color);
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-color);
}

.info-content {
    background: white;
    border-radius: 6px;
    padding: 1rem;
}

.info-item {
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #edf2f7;
}

.info-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.settings-display {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
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

.setting-item {
    background: white;
    border-radius: 6px;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid #edf2f7;
}

.setting-label {
    font-weight: 600;
    color: var(--secondary-color);
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.setting-value {
    font-size: 1.1rem;
    font-weight: 500;
    color: var(--primary-color);
}

.btn {
    border-radius: 6px;
    font-weight: 500;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: none;
}
</style>
@endsection
