<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'punch_in_time',
        'punch_out_time',
        'status',
        'worked_hours',
        'notes',
        'punch_in_source',
        'punch_out_source',
        'attendance_type',
    ];

    protected $casts = [
        'date' => 'date',
        'punch_in_time' => 'datetime',
        'punch_out_time' => 'datetime',
        'worked_hours' => 'decimal:2',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->where('date', today());
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    // Accessors & Mutators
    public function getFormattedWorkedHoursAttribute()
    {
        if (!$this->worked_hours) {
            return '--';
        }

        $hours = floor($this->worked_hours);
        $minutes = round(($this->worked_hours - $hours) * 60);

        return sprintf('%dh %dm', $hours, $minutes);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'present' => '<span class="badge bg-success">Present</span>',
            'absent' => '<span class="badge bg-danger">Absent</span>',
            'half-day' => '<span class="badge bg-warning">Half Day</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    // Methods
    public static function recordPunchIn($employeeId, $timestamp = null, $source = 'qr', $attendanceType = 'office')
    {
        $date = now()->toDateString();
        $time = $timestamp ?: now();

        $attendance = self::firstOrCreate(
            ['employee_id' => $employeeId, 'date' => $date],
            ['status' => 'present', 'attendance_type' => $attendanceType]
        );

        if (!$attendance->punch_in_time) {
            $attendance->update([
                'punch_in_time' => $time,
                'punch_in_source' => $source,
                'attendance_type' => $attendanceType
            ]);
        }

        return $attendance;
    }

    public static function recordPunchOut($employeeId, $timestamp = null, $source = 'qr', $attendanceType = 'office')
    {
        $date = now()->toDateString();
        $time = $timestamp ?: now();

        $attendance = self::firstOrCreate(
            ['employee_id' => $employeeId, 'date' => $date],
            ['status' => 'present', 'attendance_type' => $attendanceType]
        );

        $attendance->update([
            'punch_out_time' => $time,
            'punch_out_source' => $source,
            'attendance_type' => $attendanceType
        ]);

        // Calculate worked hours if both punch in and out are recorded
        if ($attendance->punch_in_time && $attendance->punch_out_time) {
            $workedSeconds = $attendance->punch_in_time->diffInSeconds($attendance->punch_out_time);
            $workedHours = round($workedSeconds / 3600, 2);
            $attendance->update(['worked_hours' => $workedHours]);
        }

        return $attendance;
    }

    // Manual Attendance Recording with PIN Verification
    public static function recordManualPunchIn($employeeId, $pin, $attendanceType = 'office', $timestamp = null)
    {
        $employee = Employee::find($employeeId);

        // Verify PIN
        if (!$employee || $employee->wfh_pin !== $pin) {
            throw new \Exception('Invalid employee PIN');
        }

        $time = $timestamp ?: now();

        return self::recordPunchIn($employeeId, $time, 'manual', $attendanceType);
    }

    public static function recordManualPunchOut($employeeId, $pin, $attendanceType = 'office', $timestamp = null)
    {
        $employee = Employee::find($employeeId);

        // Verify PIN
        if (!$employee || $employee->wfh_pin !== $pin) {
            throw new \Exception('Invalid employee PIN');
        }

        $time = $timestamp ?: now();

        return self::recordPunchOut($employeeId, $time, 'manual', $attendanceType);
    }

    public static function getTodaysAttendance()
    {
        return self::with('employee')
            ->where('date', today())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getEmployeeTodaysAttendance($employeeId)
    {
        return self::where('employee_id', $employeeId)
            ->where('date', today())
            ->first();
    }
}
