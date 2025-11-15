<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OfficeRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_type',
        'rule_category',
        'rule_name',
        'rule_description',
        'rule_settings',
        'is_active',
        'priority',
        'effective_from',
        'effective_to',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'rule_settings' => 'array',
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date'
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('rule_type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('rule_category', $category);
    }

    public function scopeEffective($query, $date = null)
    {
        $date = $date ?? now()->format('Y-m-d');
        return $query->where(function($q) use ($date) {
            $q->where('effective_from', '<=', $date)
              ->orWhereNull('effective_from');
        })->where(function($q) use ($date) {
            $q->where('effective_to', '>=', $date)
              ->orWhereNull('effective_to');
        });
    }

    // Accessors & Mutators
    public function getStatusBadgeAttribute()
    {
        $effective = $this->isCurrentlyEffective();
        return $this->is_active && $effective
            ? '<span class="badge bg-success">✅ Active</span>'
            : '<span class="badge bg-secondary">⏸️ Inactive</span>';
    }

    public function getRuleTypeNameAttribute()
    {
        return match($this->rule_type) {
            'office_timing' => 'Office Timing',
            'late_mark' => 'Late Mark Policy',
            'half_day' => 'Half Day Rules',
            'weekend_policy' => 'Weekend Policy',
            'consecutive_leave' => 'Consecutive Leave',
            'holiday_consecutive' => 'Holiday Consecutive Leave',
            default => ucfirst(str_replace('_', ' ', $this->rule_type))
        };
    }

    public function getCategoryNameAttribute()
    {
        return match($this->rule_category) {
            'attendance' => 'Attendance',
            'leave' => 'Leave Management',
            'holiday' => 'Holiday Policy',
            'disciplinary' => 'Disciplinary',
            default => ucfirst($this->rule_category ?? 'General')
        };
    }

    public function getEffectivePeriodAttribute()
    {
        if (!$this->effective_from && !$this->effective_to) {
            return 'Always';
        }

        $from = $this->effective_from ? $this->effective_from->format('M j, Y') : 'Start';
        $to = $this->effective_to ? $this->effective_to->format('M j, Y') : 'Present';

        return $from === 'Start' ? "Until {$to}" : ($to === 'Present' ? "From {$from}" : "{$from} - {$to}");
    }

    // Methods
    public function isCurrentlyEffective($date = null)
    {
        $date = $date ?? now()->format('Y-m-d');

        $fromCheck = !$this->effective_from || $this->effective_from->format('Y-m-d') <= $date;
        $toCheck = !$this->effective_to || $this->effective_to->format('Y-m-d') >= $date;

        return $fromCheck && $toCheck;
    }

    // Rule type specific methods
    public static function getRuleTypes()
    {
        return [
            'office_timing' => 'Office Timing',
            'late_mark' => 'Late Mark Policy',
            'half_day' => 'Half Day Rules',
            'weekend_policy' => 'Weekend Policy',
            'consecutive_leave' => 'Consecutive Leave Rules',
            'holiday_consecutive' => 'Holiday Consecutive Leave'
        ];
    }

    public static function getRuleCategories()
    {
        return [
            'attendance' => 'Attendance',
            'leave' => 'Leave Management',
            'holiday' => 'Holiday Policy',
            'disciplinary' => 'Disciplinary Actions'
        ];
    }

    // Helper methods for different rule types
    public static function getOfficeTimingSettings()
    {
        return [
            'start_time' => '09:00',
            'end_time' => '18:00',
            'working_hours' => 8,
            'break_start' => '13:00',
            'break_end' => '14:00',
            'grace_period_minutes' => 15
        ];
    }

    public static function getLateMarkSettings()
    {
        return [
            'late_threshold_minutes' => 15,
            'half_day_threshold_minutes' => 180,
            'max_late_marks_per_month' => 3,
            'auto_half_day_after_minutes' => 180,
            'action_on_exceed' => 'warning', // warning, deduction, suspension
            'deduction_percentage' => 2
        ];
    }

    public static function getHalfDaySettings()
    {
        return [
            'half_day_hours' => 4,
            'half_day_threshold' => '13:00',
            'mandatory_break_required' => true,
            'allow_partial_leave' => true
        ];
    }

    public static function getWeekendPolicySettings()
    {
        return [
            'second_saturday_off' => true,
            'fourth_saturday_off' => true,
            'working_saturdays' => [1, 3, 5], // 1st, 3rd, 5th Saturdays are working
            'sunday_off' => true,
            'holiday_if_falls_on_sunday' => true
        ];
    }

    public static function getConsecutiveLeaveSettings()
    {
        return [
            'weekend_consecutive' => [
                'enabled' => true,
                'sunday_to_monday' => true, // Leave on Monday after Sunday
                'friday_to_saturday' => false, // Leave on Saturday before Sunday
                'saturday_to_monday' => true, // Leave on Monday after Saturday
                'max_consecutive_days' => 2
            ],
            'working_saturday_consecutive' => [
                'enabled' => true,
                'saturday_to_monday' => true, // Saturday (working) to Monday = 2 days
                'friday_to_saturday' => false,
                'max_consecutive_days' => 2
            ],
            'extra_leave_required' => false,
            'warning_enabled' => true
        ];
    }

    public static function getHolidayConsecutiveSettings()
    {
        return [
            'enabled' => true,
            'before_holiday_consecutive' => [
                'days_before' => 1,
                'max_consecutive' => 2
            ],
            'after_holiday_consecutive' => [
                'days_after' => 1,
                'max_consecutive' => 2
            ],
            'use_academic_calendar' => true,
            'apply_to_all_holidays' => true,
            'specific_holidays' => [], // Array of holiday IDs if not all
            'count_weekends_between' => true,
            'warning_enabled' => true
        ];
    }

    // Sample rules creation method
    public static function createDefaultRules()
    {
        // Office Timing Rule
        self::create([
            'rule_type' => 'office_timing',
            'rule_category' => 'attendance',
            'rule_name' => 'Standard Office Hours',
            'rule_description' => 'Standard working hours for all employees',
            'rule_settings' => self::getOfficeTimingSettings(),
            'priority' => 1,
            'is_active' => true
        ]);

        // Late Mark Policy
        self::create([
            'rule_type' => 'late_mark',
            'rule_category' => 'attendance',
            'rule_name' => 'Late Arrival Policy',
            'rule_description' => 'Policy for handling late arrivals and deductions',
            'rule_settings' => self::getLateMarkSettings(),
            'priority' => 2,
            'is_active' => true
        ]);

        // Half Day Rules
        self::create([
            'rule_type' => 'half_day',
            'rule_category' => 'attendance',
            'rule_name' => 'Half Day Attendance Rules',
            'rule_description' => 'Rules for half day attendance and early departures',
            'rule_settings' => self::getHalfDaySettings(),
            'priority' => 3,
            'is_active' => true
        ]);

        // Weekend Policy
        self::create([
            'rule_type' => 'weekend_policy',
            'rule_category' => 'holiday',
            'rule_name' => 'Weekend and Saturday Policy',
            'rule_description' => 'Policy for 2nd/4th Saturdays and weekend rules',
            'rule_settings' => self::getWeekendPolicySettings(),
            'priority' => 4,
            'is_active' => true
        ]);

        // Consecutive Leave Rules
        self::create([
            'rule_type' => 'consecutive_leave',
            'rule_category' => 'leave',
            'rule_name' => 'Weekend Consecutive Leave',
            'rule_description' => 'Rules for leave taken around weekends and holidays',
            'rule_settings' => self::getConsecutiveLeaveSettings(),
            'priority' => 5,
            'is_active' => true
        ]);

        // Holiday Consecutive Rules
        self::create([
            'rule_type' => 'holiday_consecutive',
            'rule_category' => 'leave',
            'rule_name' => 'Holiday Consecutive Leave',
            'rule_description' => 'Rules for leave taken before/after declared holidays',
            'rule_settings' => self::getHolidayConsecutiveSettings(),
            'priority' => 6,
            'is_active' => true
        ]);
    }

    // Validation methods for rule settings
    public function validateSettings()
    {
        $validator = match($this->rule_type) {
            'office_timing' => $this->validateOfficeTimingSettings(),
            'late_mark' => $this->validateLateMarkSettings(),
            'half_day' => $this->validateHalfDaySettings(),
            'weekend_policy' => $this->validateWeekendPolicySettings(),
            'consecutive_leave' => $this->validateConsecutiveLeaveSettings(),
            'holiday_consecutive' => $this->validateHolidayConsecutiveSettings(),
            default => true
        };

        return $validator === true;
    }

    private function validateOfficeTimingSettings()
    {
        $settings = $this->rule_settings;
        // Basic validation - start_time should be before end_time
        return isset($settings['start_time']) && isset($settings['end_time']) &&
               strtotime($settings['start_time']) < strtotime($settings['end_time']);
    }

    private function validateLateMarkSettings()
    {
        $settings = $this->rule_settings;
        // Late threshold should be reasonable
        return isset($settings['late_threshold_minutes']) &&
               $settings['late_threshold_minutes'] >= 0 &&
               $settings['late_threshold_minutes'] <= 240; // Max 4 hours
    }

    // Other validation methods...
    private function validateHalfDaySettings() { return true; }
    private function validateWeekendPolicySettings() { return true; }
    private function validateConsecutiveLeaveSettings() { return true; }
    private function validateHolidayConsecutiveSettings() { return true; }
}
