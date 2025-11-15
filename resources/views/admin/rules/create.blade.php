@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-plus me-2"></i>Create New Office Rule
                </h1>
                <p class="page-subtitle">Define comprehensive rules for office timing, leave policies, and employee conduct</p>
            </div>

            <!-- Main Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        <span>Rule Configuration</span>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.office-rules.store') }}" id="ruleForm">
                        @csrf

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
                                           value="{{ old('rule_name', $templateSettings ? 'New ' . __('office-rules.types.' . request('rule_type', 'custom')) . ' Rule' : old('rule_name')) }}"
                                           placeholder="Enter a descriptive rule name"
                                           required>
                                    @error('rule_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Choose a clear, descriptive name for this rule</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="rule_category" class="form-label fw-bold">
                                        <i class="fas fa-folder me-2"></i>Category <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg @error('rule_category') is-invalid @enderror"
                                            id="rule_category"
                                            name="rule_category"
                                            required>
                                        <option value="">Select category...</option>
                                        @foreach($ruleCategories as $key => $value)
                                            <option value="{{ $key }}" {{ (old('rule_category') ?? request('category', 'attendance')) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('rule_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="rule_type" class="form-label fw-bold">
                                        <i class="fas fa-code me-2"></i>Rule Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg @error('rule_type') is-invalid @enderror"
                                            id="rule_type"
                                            name="rule_type"
                                            required>
                                        <option value="">Select rule type...</option>
                                        @foreach($ruleTypes as $key => $value)
                                            <option value="{{ $key }}" {{ (old('rule_type') ?? request('rule_type')) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('rule_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                              placeholder="Describe the purpose and application of this rule...">{{ old('rule_description') }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Provide detailed explanation of when and how this rule applies
                                        </small>
                                        <small class="text-count">
                                            <span id="descriptionCount">0</span>/1000 characters
                                        </small>
                                    </div>
                                    @error('rule_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Priority and Status -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="priority" class="form-label fw-bold">
                                        <i class="fas fa-sort-numeric-up me-2"></i>Priority Level
                                    </label>
                                    <input type="number"
                                           class="form-control @error('priority') is-invalid @enderror"
                                           id="priority"
                                           name="priority"
                                           value="{{ old('priority', 0) }}"
                                           min="0"
                                           max="100"
                                           placeholder="0">
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Higher numbers = higher priority (0 = default)</small>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="effective_from" class="form-label fw-bold">
                                        <i class="fas fa-calendar-plus me-2"></i>Effective From
                                    </label>
                                    <input type="date"
                                           class="form-control @error('effective_from') is-invalid @enderror"
                                           id="effective_from"
                                           name="effective_from"
                                           value="{{ old('effective_from') }}">
                                    @error('effective_from')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty for immediate effect</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="effective_to" class="form-label fw-bold">
                                        <i class="fas fa-calendar-minus me-2"></i>Effective Until
                                    </label>
                                    <input type="date"
                                           class="form-control @error('effective_to') is-invalid @enderror"
                                           id="effective_to"
                                           name="effective_to"
                                           value="{{ old('effective_to') }}">
                                    @error('effective_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty for indefinite</small>
                                </div>
                            </div>
                        </div>

                        <!-- Rule Settings (Dynamic based on rule type) -->
                        <div id="ruleSettingsContainer">
                            <div class="rule-settings-card" id="office_timing_settings" style="display: {{ old('rule_type') == 'office_timing' || (!$errors->any() && request('rule_type') == 'office_timing') ? 'block' : 'none' }};">
                                <h6 class="rule-settings-title">
                                    <i class="fas fa-clock me-2"></i>Office Timing Configuration
                                </h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Start Time</label>
                                        <input type="time" class="form-control"
                                               name="rule_settings[start_time]"
                                               value="{{ $templateSettings['start_time'] ?? '09:00' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">End Time</label>
                                        <input type="time" class="form-control"
                                               name="rule_settings[end_time]"
                                               value="{{ $templateSettings['end_time'] ?? '18:00' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Working Hours</label>
                                        <input type="number" class="form-control" min="1" max="24"
                                               name="rule_settings[working_hours]"
                                               value="{{ $templateSettings['working_hours'] ?? 8 }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Grace Period (min)</label>
                                        <input type="number" class="form-control" min="0" max="120"
                                               name="rule_settings[grace_period_minutes]"
                                               value="{{ $templateSettings['grace_period_minutes'] ?? 15 }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Break Start Time</label>
                                        <input type="time" class="form-control"
                                               name="rule_settings[break_start]"
                                               value="{{ $templateSettings['break_start'] ?? '13:00' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Break End Time</label>
                                        <input type="time" class="form-control"
                                               name="rule_settings[break_end]"
                                               value="{{ $templateSettings['break_end'] ?? '14:00' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="rule-settings-card" id="late_mark_settings" style="display: {{ old('rule_type') == 'late_mark' || (!$errors->any() && request('rule_type') == 'late_mark') ? 'block' : 'none' }};">
                                <h6 class="rule-settings-title">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Late Mark Policy
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Late Threshold (minutes)</label>
                                        <input type="number" class="form-control" min="1" max="300"
                                               name="rule_settings[late_threshold_minutes]"
                                               value="{{ $templateSettings['late_threshold_minutes'] ?? 15 }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Half-day after specific time</label>
                                        <input type="time" class="form-control"
                                               name="rule_settings[half_day_threshold_time]"
                                               value="{{ $templateSettings['half_day_threshold_time'] ?? '14:00' }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Auto half-day if late beyond</label>
                                        <input type="time" class="form-control"
                                               name="rule_settings[auto_half_day_threshold]"
                                               value="{{ $templateSettings['auto_half_day_threshold'] ?? '13:30' }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Max Late Marks per Month</label>
                                        <input type="number" class="form-control" min="0" max="31"
                                               name="rule_settings[max_late_marks_per_month]"
                                               value="{{ $templateSettings['max_late_marks_per_month'] ?? 3 }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Action on Exceed</label>
                                        <select class="form-select" name="rule_settings[action_on_exceed]">
                                            <option value="warning" {{ ($templateSettings['action_on_exceed'] ?? 'warning') == 'warning' ? 'selected' : '' }}>Warning</option>
                                            <option value="deduction" {{ ($templateSettings['action_on_exceed'] ?? 'warning') == 'deduction' ? 'selected' : '' }}>Salary Deduction</option>
                                            <option value="suspension" {{ ($templateSettings['action_on_exceed'] ?? 'warning') == 'suspension' ? 'selected' : '' }}>Suspension</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Deduction Percentage (%)</label>
                                        <input type="number" class="form-control" min="0" max="100" step="0.1"
                                               name="rule_settings[deduction_percentage]"
                                               value="{{ $templateSettings['deduction_percentage'] ?? 2 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="rule-settings-card" id="half_day_settings" style="display: {{ old('rule_type') == 'half_day' || (!$errors->any() && request('rule_type') == 'half_day') ? 'block' : 'none' }};">
                                <h6 class="rule-settings-title">
                                    <i class="fas fa-stopwatch me-2"></i>Half Day Rules
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Half-day Hours</label>
                                        <input type="number" class="form-control" min="0.5" max="12" step="0.5"
                                               name="rule_settings[half_day_hours]"
                                               value="{{ $templateSettings['half_day_hours'] ?? 4 }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Half-day Threshold</label>
                                        <input type="time" class="form-control"
                                               name="rule_settings[half_day_threshold]"
                                               value="{{ $templateSettings['half_day_threshold'] ?? '13:00' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Options</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[mandatory_break_required]" value="1"
                                                   {{ ($templateSettings['mandatory_break_required'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Mandatory break required</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[allow_partial_leave]" value="1"
                                                   {{ ($templateSettings['allow_partial_leave'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Allow partial leave</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rule-settings-card" id="weekend_policy_settings" style="display: {{ old('rule_type') == 'weekend_policy' || (!$errors->any() && request('rule_type') == 'weekend_policy') ? 'block' : 'none' }};">
                                <h6 class="rule-settings-title">
                                    <i class="fas fa-calendar-week me-2"></i>Weekend Policy
                                </h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <strong>Saturday Policy:</strong> Configure which Saturdays are working days and which are off days.
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[second_saturday_off]" value="1" id="second_sat"
                                                   {{ ($templateSettings['second_saturday_off'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="second_sat">
                                                2nd Saturday is OFF day
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[fourth_saturday_off]" value="1" id="fourth_sat"
                                                   {{ ($templateSettings['fourth_saturday_off'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="fourth_sat">
                                                4th Saturday is OFF day
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[sunday_off]" value="1" id="sunday_off"
                                                   {{ ($templateSettings['sunday_off'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sunday_off">
                                                Sunday is OFF day
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Working Saturdays (1st, 3rd, 5th)</label>
                                        <input type="text" class="form-control" readonly
                                               value="Automatically calculated based on above settings">
                                        <input type="hidden" name="rule_settings[working_saturdays]" value="[1,3,5]">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[holiday_if_falls_on_sunday]" value="1"
                                                   {{ ($templateSettings['holiday_if_falls_on_sunday'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                Holiday if any holiday falls on Sunday
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rule-settings-card" id="consecutive_leave_settings" style="display: {{ old('rule_type') == 'consecutive_leave' || (!$errors->any() && request('rule_type') == 'consecutive_leave') ? 'block' : 'none' }};">
                                <h6 class="rule-settings-title">
                                    <i class="fas fa-link me-2"></i>Consecutive Leave Rules (Weekends)
                                </h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <strong>Weekend Consecutive:</strong> Rules for leave taken around weekends and working Saturdays.
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Weekend Combinations</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[weekend_consecutive][enabled]" value="1"
                                                   {{ ($templateSettings['weekend_consecutive']['enabled'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Enable weekend consecutive rules</label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[weekend_consecutive][sunday_to_monday]" value="1"
                                                   {{ ($templateSettings['weekend_consecutive']['sunday_to_monday'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Sunday to Monday = 2 days consecutive</label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[weekend_consecutive][saturday_to_monday]" value="1"
                                                   {{ ($templateSettings['weekend_consecutive']['saturday_to_monday'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Saturday (working) to Monday = 2 days consecutive</label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[weekend_consecutive][friday_to_saturday]" value="1"
                                                   {{ ($templateSettings['weekend_consecutive']['friday_to_saturday'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label">Friday to Saturday (working) = 2 days consecutive</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-bold">Maximum Consecutive Days</label>
                                            <input type="number" class="form-control" min="1" max="10"
                                                   name="rule_settings[weekend_consecutive][max_consecutive_days]"
                                                   value="{{ $templateSettings['weekend_consecutive']['max_consecutive_days'] ?? 2 }}">
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[extra_leave_required]" value="1"
                                                   {{ ($templateSettings['extra_leave_required'] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label">Extra leave required for consecutive</label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[warning_enabled]" value="1"
                                                   {{ ($templateSettings['warning_enabled'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Show warnings for consecutive leave</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rule-settings-card" id="holiday_consecutive_settings" style="display: {{ old('rule_type') == 'holiday_consecutive' || (!$errors->any() && request('rule_type') == 'holiday_consecutive') ? 'block' : 'none' }};">
                                <h6 class="rule-settings-title">
                                    <i class="fas fa-calendar-alt me-2"></i>Holiday Consecutive Leave Rules
                                </h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <strong>Holiday Consecutive:</strong> Rules for leave taken before/after declared holidays from the academic calendar.
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[enabled]" value="1"
                                                   {{ ($templateSettings['enabled'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Enable holiday consecutive rules</label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[use_academic_calendar]" value="1"
                                                   {{ ($templateSettings['use_academic_calendar'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Use academic calendar holidays</label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[apply_to_all_holidays]" value="1"
                                                   {{ ($templateSettings['apply_to_all_holidays'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Apply to all holidays</label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[warning_enabled]" value="1"
                                                   {{ ($templateSettings['warning_enabled'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Show warnings for violations</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Before Holiday</h6>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" min="0" max="7"
                                                   name="rule_settings[before_holiday_consecutive][days_before]"
                                                   value="{{ $templateSettings['before_holiday_consecutive']['days_before'] ?? 1 }}">
                                            <span class="input-group-text">days before holiday</span>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" min="1" max="10"
                                                   name="rule_settings[before_holiday_consecutive][max_consecutive]"
                                                   value="{{ $templateSettings['before_holiday_consecutive']['max_consecutive'] ?? 2 }}">
                                            <span class="input-group-text">max consecutive days</span>
                                        </div>

                                        <h6>After Holiday</h6>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" min="0" max="7"
                                                   name="rule_settings[after_holiday_consecutive][days_after]"
                                                   value="{{ $templateSettings['after_holiday_consecutive']['days_after'] ?? 1 }}">
                                            <span class="input-group-text">days after holiday</span>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" min="1" max="10"
                                                   name="rule_settings[after_holiday_consecutive][max_consecutive]"
                                                   value="{{ $templateSettings['after_holiday_consecutive']['max_consecutive'] ?? 2 }}">
                                            <span class="input-group-text">max consecutive days</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="rule_settings[count_weekends_between]" value="1"
                                                   {{ ($templateSettings['count_weekends_between'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                Count weekends/festivals between leave and holiday as part of consecutive calculation
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="row mb-4" id="rulePreview" style="display: none;">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-eye me-2"></i>Rule Preview</h6>
                                    <div id="previewContent"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary" onclick="previewRule()">
                                            <i class="fas fa-eye me-1"></i>Preview Rule
                                        </button>
                                        <button type="button" class="btn btn-outline-info" onclick="clearForm()">
                                            <i class="fas fa-undo me-1"></i>Clear Form
                                        </button>
                                        <button type="button" class="btn btn-outline-warning" onclick="loadTemplate()">
                                            <i class="fas fa-magic me-1"></i>Load Default Template
                                        </button>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.office-rules.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>Back to List
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save me-2"></i>Create Rule
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Rule type change handler
document.getElementById('rule_type').addEventListener('change', function() {
    showRuleSettings(this.value);
    updateRuleName(this.value);
});

// Initially show settings for selected rule type
document.addEventListener('DOMContentLoaded', function() {
    showRuleSettings(document.getElementById('rule_type').value);

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

// Show appropriate settings based on rule type
function showRuleSettings(ruleType) {
    // Hide all settings
    document.querySelectorAll('.rule-settings-card').forEach(card => {
        card.style.display = 'none';
    });

    // Show specific settings
    const settingsCard = document.getElementById(ruleType + '_settings');
    if (settingsCard) {
        settingsCard.style.display = 'block';
    }
}

function updateRuleName(ruleType) {
    if (!ruleType) return;

    const names = {
        'office_timing': 'Office Timing Policy',
        'late_mark': 'Late Mark Policy',
        'half_day': 'Half Day Rules',
        'weekend_policy': 'Weekend and Saturday Policy',
        'consecutive_leave': 'Consecutive Leave Rules',
        'holiday_consecutive': 'Holiday Consecutive Leave Rules'
    };

    if (names[ruleType] && !document.getElementById('rule_name').value) {
        document.getElementById('rule_name').value = names[ruleType];
    }
}

function previewRule() {
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
            document.getElementById('previewContent').innerHTML = `
                <strong>Type:</strong> ${data.preview.rule_type_display}<br>
                <strong>Category:</strong> ${data.preview.category_display}<br>
                <strong>Settings:</strong> ${data.preview.settings_summary}<br>
                <strong>Effective:</strong> ${data.preview.effective_period}<br>
                <strong>Status:</strong> ${data.preview.status_badge}
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

function clearForm() {
    if (confirm('Are you sure you want to clear all form data?')) {
        document.getElementById('ruleForm').reset();
        document.querySelectorAll('.rule-settings-card').forEach(card => {
            card.style.display = 'none';
        });
        document.getElementById('rulePreview').style.display = 'none';
        document.getElementById('statusText').textContent = '✅ Active';
        updateDescriptionCount();
    }
}

function loadTemplate() {
    const ruleType = document.getElementById('rule_type').value;
    if (!ruleType) {
        alert('Please select a rule type first');
        return;
    }

    // Templates are already loaded via $templateSettings in PHP
    // We can enhance this to dynamically load templates via AJAX if needed
    alert('Template settings are already loaded for the selected rule type. Please make your adjustments.');
}

function updateDescriptionCount() {
    const textarea = document.getElementById('rule_description');
    if (textarea) {
        document.getElementById('descriptionCount').textContent = textarea.value.length;
    }
}
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

.rule-settings-card {
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.rule-settings-title {
    color: var(--secondary-color);
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-color);
}

.text-count {
    font-size: 0.9rem;
    color: #6c757d;
}

.alert-info {
    border-left: 4px solid var(--primary-color);
}
</style>
@endsection
