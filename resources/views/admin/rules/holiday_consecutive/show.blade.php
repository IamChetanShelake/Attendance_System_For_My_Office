@extends('admin.layout.master')

@section('title', 'View Holiday Consecutive Rule - ' . $rule->rule_name)

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">{{ $rule->rule_name }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.office-rules.index') }}">Office Rules</a></li>
                        <li class="breadcrumb-item active">{{ $rule->rule_name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.office-rules.edit', $rule) }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit"></i> Edit Rule
                </a>
                <button class="btn btn-outline-secondary" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
            </div>
        </div>

        <!-- Rule Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="section-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="mb-3">Rule Overview</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="200"><strong>Rule Type:</strong></td>
                                        <td>{{ $rule->rule_type_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Category:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $rule->rule_category === 'holiday' ? 'success' : ($rule->rule_category === 'leave' ? 'info' : 'secondary') }}">
                                                {{ $rule->category_name }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>{!! $rule->status_badge !!}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Priority:</strong></td>
                                        <td>{{ $rule->priority }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Effective Period:</strong></td>
                                        <td>{{ $rule->effective_period }}</td>
                                    </tr>
                                    @if($rule->rule_description)
                                    <tr>
                                        <td><strong>Description:</strong></td>
                                        <td>{{ $rule->rule_description }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Created By:</strong></td>
                                        <td>{{ $rule->creator ? $rule->creator->name : 'System' }} on {{ $rule->created_at->format('M j, Y g:i A') }}</td>
                                    </tr>
                                    @if($rule->updater)
                                    <tr>
                                        <td><strong>Last Updated By:</strong></td>
                                        <td>{{ $rule->updater->name }} on {{ $rule->updated_at->format('M j, Y g:i A') }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="icon mb-3">
                                        <i class="fas fa-calendar-times fa-3x text-primary"></i>
                                    </div>
                                    <h6>Holiday Consecutive Leave Rules</h6>
                                    <p class="text-muted small">Rules for leave taken consecutively around public holidays</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rule Settings -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-cogs"></i> Rule Configuration</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $settings = $rule->rule_settings;
                        @endphp

                        <!-- General Settings -->
                        <div class="mb-4">
                            <h6 class="mb-3 text-primary">
                                <i class="fas fa-info-circle"></i> General Settings
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Rule Enabled:</strong></label>
                                        <div>
                                            @if($settings['enabled'] ?? false)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Use Academic Calendar:</strong></label>
                                        <div>
                                            @if($settings['use_academic_calendar'] ?? true)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Apply to All Holidays:</strong></label>
                                        <div>
                                            @if($settings['apply_to_all_holidays'] ?? true)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Before Holiday Consecutive -->
                        @if(isset($settings['before_holiday_consecutive']) && $settings['before_holiday_consecutive']['days_before'] > 0)
                        <div class="mb-4">
                            <h6 class="mb-3 text-primary">
                                <i class="fas fa-arrow-left"></i> Before Holiday Consecutive Rules
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Days Before Holiday:</strong></label>
                                        <div>{{ $settings['before_holiday_consecutive']['days_before'] }} days</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Maximum Consecutive Days:</strong></label>
                                        <div>{{ $settings['before_holiday_consecutive']['max_consecutive'] }} days</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- After Holiday Consecutive -->
                        @if(isset($settings['after_holiday_consecutive']) && $settings['after_holiday_consecutive']['days_after'] > 0)
                        <div class="mb-4">
                            <h6 class="mb-3 text-primary">
                                <i class="fas fa-arrow-right"></i> After Holiday Consecutive Rules
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Days After Holiday:</strong></label>
                                        <div>{{ $settings['after_holiday_consecutive']['days_after'] }} days</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Maximum Consecutive Days:</strong></label>
                                        <div>{{ $settings['after_holiday_consecutive']['max_consecutive'] }} days</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Warning Settings -->
                        <div class="mb-4">
                            <h6 class="mb-3 text-warning">
                                <i class="fas fa-exclamation-triangle"></i> Warning Settings
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Warnings Enabled:</strong></label>
                                        <div>
                                            @if($settings['warning_enabled'] ?? true)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><strong>Count Weekends Between:</strong></label>
                                        <div>
                                            @if($settings['count_weekends_between'] ?? true)
                                                <span class="badge bg-info">Yes</span>
                                            @else
                                                <span class="badge bg-light">No</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Specific Holidays -->
                        @if(isset($settings['specific_holidays']) && !empty($settings['specific_holidays']))
                        <div class="mb-4">
                            <h6 class="mb-3 text-info">
                                <i class="fas fa-list"></i> Specific Holidays ({{ count($settings['specific_holidays']) }} selected)
                            </h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <small>This rule applies only to the following specific holidays. If no holidays are selected, it applies to all holidays.</small>
                                    </div>
                                    <ul class="list-inline">
                                        @foreach($settings['specific_holidays'] as $holidayId)
                                            <li class="list-inline-item">
                                                <span class="badge bg-secondary">Holiday ID: {{ $holidayId }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="section-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <form action="{{ route('admin.office-rules.toggle-status', $rule) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-{{ $rule->is_active ? 'warning' : 'success' }} w-100">
                                        <i class="fas {{ $rule->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                        {{ $rule->is_active ? 'Deactivate' : 'Activate' }} Rule
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.office-rules.edit', $rule) }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-edit"></i> Edit Settings
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.office-rules.create', ['rule_type' => 'holiday_consecutive']) }}" class="btn btn-outline-info w-100">
                                    <i class="fas fa-copy"></i> Create Similar Rule
                                </a>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-secondary w-100" onclick="window.history.back()">
                                    <i class="fas fa-arrow-left"></i> Back to Rules List
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any client-side functionality here
});
</script>
@endsection
