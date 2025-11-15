<?php

namespace App\Http\Controllers;

use App\Models\OfficeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OfficeRulesController extends Controller
{
    public function index(Request $request)
    {
        $query = OfficeRule::with(['creator', 'updater'])
            ->orderBy('priority', 'asc')
            ->orderBy('created_at', 'desc');

        // Filter by rule type
        if ($request->filled('rule_type')) {
            $query->where('rule_type', $request->rule_type);
        }

        // Filter by category
        if ($request->filled('rule_category')) {
            $query->where('rule_category', $request->rule_category);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active()->effective();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $rules = $query->paginate(15);

        // Statistics
        $stats = [
            'total_rules' => OfficeRule::count(),
            'active_rules' => OfficeRule::active()->effective()->count(),
            'inactive_rules' => OfficeRule::where('is_active', false)->count(),
            'attendance_rules' => OfficeRule::where('rule_category', 'attendance')->count(),
            'leave_rules' => OfficeRule::where('rule_category', 'leave')->count(),
            'holiday_rules' => OfficeRule::where('rule_category', 'holiday')->count(),
        ];

        $ruleTypes = OfficeRule::getRuleTypes();
        $ruleCategories = OfficeRule::getRuleCategories();

        return view('admin.rules.index', compact('rules', 'stats', 'ruleTypes', 'ruleCategories'));
    }

    public function create(Request $request)
    {
        $ruleTypes = OfficeRule::getRuleTypes();
        $ruleCategories = OfficeRule::getRuleCategories();

        // If a specific rule type is requested, load template settings
        $templateSettings = null;
        if ($request->filled('rule_type')) {
            $templateSettings = $this->getTemplateSettings($request->rule_type);
        }

        return view('admin.rules.create', compact('ruleTypes', 'ruleCategories', 'templateSettings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rule_type' => 'required|in:' . implode(',', array_keys(OfficeRule::getRuleTypes())),
            'rule_category' => 'required|in:' . implode(',', array_keys(OfficeRule::getRuleCategories())),
            'rule_name' => 'required|string|max:255',
            'rule_description' => 'nullable|string|max:1000',
            'rule_settings' => 'required|array',
            'is_active' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:0|max:100',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
        ]);

        try {
            DB::beginTransaction();

            $rule = OfficeRule::create([
                'rule_type' => $request->rule_type,
                'rule_category' => $request->rule_category,
                'rule_name' => $request->rule_name,
                'rule_description' => $request->rule_description,
                'rule_settings' => $request->rule_settings,
                'is_active' => $request->boolean('is_active', true),
                'priority' => $request->integer('priority', 0),
                'effective_from' => $request->effective_from,
                'effective_to' => $request->effective_to,
                'created_by' => auth()->id() ?? 1, // Replace with proper auth
                'updated_by' => auth()->id() ?? 1, // Replace with proper auth
            ]);

            // Validate rule settings
            if (!$rule->validateSettings()) {
                throw new \Exception('Invalid rule settings. Please check your configuration.');
            }

            DB::commit();

            Log::info("Office rule created", [
                'rule_id' => $rule->id,
                'rule_name' => $rule->rule_name,
                'rule_type' => $rule->rule_type
            ]);

            return redirect()->route('admin.office-rules.index')
                ->with('success', 'Office rule created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Office rule creation failed", ['error' => $e->getMessage()]);

            return redirect()->back()
                ->withInput($request->all())
                ->with('error', 'Failed to create office rule. ' . $e->getMessage());
        }
    }

    public function show(OfficeRule $rule)
    {
        $rule->load(['creator', 'updater']);

        // Route to specific show view based on rule type
        switch ($rule->rule_type) {
            case 'office_timing':
                return view('admin.rules.office_timing.show', compact('rule'));
            case 'late_mark':
                return view('admin.rules.late_mark.show', compact('rule'));
            case 'half_day':
                return view('admin.rules.half_day.show', compact('rule'));
            case 'weekend_policy':
                return view('admin.rules.weekend_policy.show', compact('rule'));
            case 'consecutive_leave':
                return view('admin.rules.consecutive_leave.show', compact('rule'));
            case 'holiday_consecutive':
                return view('admin.rules.holiday_consecutive.show', compact('rule'));
            default:
                // Generic rule view for unknown types
                return view('admin.rules.generic.show', compact('rule'));
        }
    }

    public function edit(OfficeRule $rule)
    {
        $ruleTypes = OfficeRule::getRuleTypes();
        $ruleCategories = OfficeRule::getRuleCategories();

        // Route to specific edit form based on rule type
        switch ($rule->rule_type) {
            case 'office_timing':
                return view('admin.rules.office_timing.edit', compact('rule', 'ruleTypes', 'ruleCategories'));
            case 'late_mark':
                return view('admin.rules.late_mark.edit', compact('rule', 'ruleTypes', 'ruleCategories'));
            case 'half_day':
                return view('admin.rules.half_day.edit', compact('rule', 'ruleTypes', 'ruleCategories'));
            case 'weekend_policy':
                return view('admin.rules.weekend_policy.edit', compact('rule', 'ruleTypes', 'ruleCategories'));
            case 'consecutive_leave':
                return view('admin.rules.consecutive_leave.edit', compact('rule', 'ruleTypes', 'ruleCategories'));
            default:
                // Fallback to universal edit dispatcher
                return view('admin.rules.edit', compact('rule', 'ruleTypes', 'ruleCategories'));
        }
    }

    public function update(Request $request, OfficeRule $rule)
    {
        $request->validate([
            'rule_type' => 'required|in:' . implode(',', array_keys(OfficeRule::getRuleTypes())),
            'rule_category' => 'required|in:' . implode(',', array_keys(OfficeRule::getRuleCategories())),
            'rule_name' => 'required|string|max:255',
            'rule_description' => 'nullable|string|max:1000',
            'rule_settings' => 'required|array',
            'is_active' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:0|max:100',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
        ]);

        try {
            DB::beginTransaction();

            $rule->update([
                'rule_type' => $request->rule_type,
                'rule_category' => $request->rule_category,
                'rule_name' => $request->rule_name,
                'rule_description' => $request->rule_description,
                'rule_settings' => $request->rule_settings,
                'is_active' => $request->boolean('is_active', true),
                'priority' => $request->integer('priority', 0),
                'effective_from' => $request->effective_from,
                'effective_to' => $request->effective_to,
                'updated_by' => auth()->id() ?? 1, // Replace with proper auth
            ]);

            // Validate rule settings
            if (!$rule->validateSettings()) {
                throw new \Exception('Invalid rule settings. Please check your configuration.');
            }

            DB::commit();

            Log::info("Office rule updated", [
                'rule_id' => $rule->id,
                'rule_name' => $rule->rule_name
            ]);

            return redirect()->route('admin.office-rules.index')
                ->with('success', 'Office rule updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Office rule update failed", ['error' => $e->getMessage(), 'rule_id' => $rule->id]);

            return redirect()->back()
                ->withInput($request->all())
                ->with('error', 'Failed to update office rule. ' . $e->getMessage());
        }
    }

    public function destroy(OfficeRule $rule)
    {
        try {
            $ruleName = $rule->rule_name;
            $rule->delete();

            Log::info("Office rule deleted", [
                'rule_id' => $rule->id,
                'rule_name' => $ruleName
            ]);

            return redirect()->route('admin.office-rules.index')
                ->with('success', 'Office rule deleted successfully!');

        } catch (\Exception $e) {
            Log::error("Office rule deletion failed", [
                'error' => $e->getMessage(),
                'rule_id' => $rule->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete office rule. Please try again.');
        }
    }

    public function toggleStatus(OfficeRule $rule)
    {
        try {
            $rule->update([
                'is_active' => !$rule->is_active,
                'updated_by' => auth()->id() ?? 1
            ]);

            $status = $rule->is_active ? 'activated' : 'deactivated';

            Log::info("Office rule status changed", [
                'rule_id' => $rule->id,
                'status' => $status
            ]);

            return redirect()->back()
                ->with('success', "Office rule {$status} successfully!");

        } catch (\Exception $e) {
            Log::error("Office rule status change failed", [
                'error' => $e->getMessage(),
                'rule_id' => $rule->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to change rule status. Please try again.');
        }
    }

    public function createDefaultRules()
    {
        try {
            OfficeRule::createDefaultRules();

            Log::info("Default office rules created");

            return redirect()->route('admin.office-rules.index')
                ->with('success', 'Default office rules created successfully! You can customize them as needed.');

        } catch (\Exception $e) {
            Log::error("Default office rules creation failed", ['error' => $e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Failed to create default rules. ' . $e->getMessage());
        }
    }

    public function preview(Request $request)
    {
        $request->validate([
            'rule_type' => 'required|in:' . implode(',', array_keys(OfficeRule::getRuleTypes())),
            'rule_category' => 'required|in:' . implode(',', array_keys(OfficeRule::getRuleCategories())),
            'rule_settings' => 'required|array',
        ]);

        // Create a temporary rule object for preview
        $rule = new OfficeRule([
            'rule_type' => $request->rule_type,
            'rule_category' => $request->rule_category,
            'rule_name' => $request->rule_name ?: 'Preview Rule',
            'rule_description' => $request->rule_description,
            'rule_settings' => $request->rule_settings,
            'is_active' => $request->boolean('is_active', true),
            'priority' => $request->integer('priority', 0),
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
        ]);

        if (!$rule->validateSettings()) {
            return response()->json([
                'success' => false,
                'message' => 'Rule settings are invalid. Please check your configuration.'
            ]);
        }

        return response()->json([
            'success' => true,
            'preview' => [
                'rule_type_display' => $rule->rule_type_name,
                'category_display' => $rule->category_name,
                'settings_summary' => $this->generateSettingsSummary($rule),
                'is_effective' => $rule->isCurrentlyEffective(),
                'effective_period' => $rule->effective_period,
                'status_badge' => $rule->status_badge
            ]
        ]);
    }

    private function getTemplateSettings($ruleType)
    {
        return match($ruleType) {
            'office_timing' => OfficeRule::getOfficeTimingSettings(),
            'late_mark' => OfficeRule::getLateMarkSettings(),
            'half_day' => OfficeRule::getHalfDaySettings(),
            'weekend_policy' => OfficeRule::getWeekendPolicySettings(),
            'consecutive_leave' => OfficeRule::getConsecutiveLeaveSettings(),
            'holiday_consecutive' => OfficeRule::getHolidayConsecutiveSettings(),
            default => []
        };
    }

    private function generateSettingsSummary(OfficeRule $rule)
    {
        $settings = $rule->rule_settings;

        return match($rule->rule_type) {
            'office_timing' => "Office hours: {$settings['start_time']} - {$settings['end_time']} ({$settings['working_hours']} hours/day)",
            'late_mark' => "Late threshold: {$settings['late_threshold_minutes']} min, Half-day after: {$settings['half_day_threshold_minutes']} min",
            'half_day' => "Half-day threshold: {$settings['half_day_threshold']}, Half-day hours: {$settings['half_day_hours']}",
            'weekend_policy' => "2nd Saturday off: " . ($settings['second_saturday_off'] ? 'Yes' : 'No') . ", 4th Saturday off: " . ($settings['fourth_saturday_off'] ? 'Yes' : 'No'),
            'consecutive_leave' => "Weekend consecutive enabled: " . ($settings['weekend_consecutive']['enabled'] ? 'Yes' : 'No') . ", Max consecutive: {$settings['weekend_consecutive']['max_consecutive_days']} days",
            'holiday_consecutive' => "Holiday consecutive enabled: " . ($settings['enabled'] ? 'Yes' : 'No') . ", Before: {$settings['before_holiday_consecutive']['max_consecutive']} days, After: {$settings['after_holiday_consecutive']['max_consecutive']} days",
            default => 'Custom rule settings configured'
        };
    }
}
