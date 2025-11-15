<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'days_count',
        'reason',
        'status',
        'is_half_day',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'admin_notes',
        'emergency',
        'attachments',
        'submitted_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_half_day' => 'boolean',
        'emergency' => 'boolean',
        'attachments' => 'array',
        'days_count' => 'decimal:1'
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    // Accessors & Mutators
    public function getFormattedLeaveTypeAttribute()
    {
        return $this->getLeaveTypeDisplayName($this->leave_type);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning text-dark">⏳ Pending</span>',
            'approved' => '<span class="badge bg-success">✅ Approved</span>',
            'rejected' => '<span class="badge bg-danger">❌ Rejected</span>',
            'cancelled' => '<span class="badge bg-secondary">🚫 Cancelled</span>',
        };
    }

    public function getDateRangeAttribute()
    {
        if ($this->start_date == $this->end_date) {
            return $this->start_date->format('M j, Y');
        }

        return $this->start_date->format('M j') . ' - ' . $this->end_date->format('M j, Y');
    }

    public function getDurationAttribute()
    {
        return $this->days_count . ' ' . ($this->is_half_day ? 'half day' : 'day' . ($this->days_count > 1 ? 's' : ''));
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByType($query, $leaveType)
    {
        return $query->where('leave_type', $leaveType);
    }

    public function scopeInDateRange($query, $start, $end)
    {
        return $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end]);
    }

    // Methods
    public function approve(Employee $approvedBy, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approvedBy->id,
            'approved_at' => now(),
            'admin_notes' => $notes
        ]);
    }

    public function reject(Employee $rejectedBy, $reason, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $rejectedBy->id,
            'approved_at' => now(),
            'rejected_reason' => $reason,
            'admin_notes' => $notes
        ]);
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }

    public function calculateDaysCount()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        if ($this->is_half_day) {
            return 0.5;
        }

        return $start->diffInDays($end) + 1; // Including both start and end dates
    }

    public static function getLeaveTypes()
    {
        return [
            'annual_leave' => 'Annual Leave',
            'sick_leave' => 'Sick Leave',
            'casual_leave' => 'Casual Leave',
            'maternity_leave' => 'Maternity Leave',
            'paternity_leave' => 'Paternity Leave',
            'emergency_leave' => 'Emergency Leave',
            'unpaid_leave' => 'Unpaid Leave',
            'medical_leave' => 'Medical Leave',
            'vacation_leave' => 'Vacation Leave',
            'bereavement_leave' => 'Bereavement Leave',
            'personal_leave' => 'Personal Leave'
        ];
    }

    public static function getLeaveTypeDisplayName($type)
    {
        return static::getLeaveTypes()[$type] ?? ucfirst(str_replace('_', ' ', $type));
    }
}
