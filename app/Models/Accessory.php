<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'accessory_name',
        'description',
        'serial_number',
        'model_number',
        'value',
        'allocation_date',
        'return_date',
        'status',
        'condition_notes',
        'photo',
    ];

    protected $casts = [
        'allocation_date' => 'date',
        'return_date' => 'date',
        'value' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'allocated' => '<span class="badge bg-success">Allocated</span>',
            'returned' => '<span class="badge bg-primary">Returned</span>',
            'lost' => '<span class="badge bg-danger">Lost</span>',
            'damaged' => '<span class="badge bg-warning">Damaged</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    public function getFormattedValueAttribute()
    {
        return $this->value ? '₹' . number_format($this->value, 2) : 'Not specified';
    }

    public function getAllocationDaysAttribute()
    {
        $start = $this->allocation_date;
        $end = $this->return_date ?? now();

        return $start->diffInDays($end);
    }
}
