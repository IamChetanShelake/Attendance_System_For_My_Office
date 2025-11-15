<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'salary_amount',
        'pan_number',
        'aadhaar_number',
        'account_holder_name',
        'account_number',
        'bank_name',
        'ifsc_code',
        'branch_name',
        'effective_date',
        'status',
        'basic_salary',
        'hra',
        'conveyance',
        'medical_allowance',
        'lta',
        'special_allowance',
        'provident_fund',
        'remarks',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'salary_amount' => 'decimal:2',
        'basic_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'conveyance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'lta' => 'decimal:2',
        'special_allowance' => 'decimal:2',
        'provident_fund' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTotalEarningsAttribute()
    {
        return ($this->basic_salary ?? 0) +
               ($this->hra ?? 0) +
               ($this->conveyance ?? 0) +
               ($this->medical_allowance ?? 0) +
               ($this->lta ?? 0) +
               ($this->special_allowance ?? 0);
    }

    public function getTotalDeductionsAttribute()
    {
        return ($this->provident_fund ?? 0);
    }

    public function getNetSalaryAttribute()
    {
        return $this->total_earnings - $this->total_deductions;
    }
}
