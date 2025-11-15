<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'type',
        'image',
        'is_recurring',
        'recurrence_type',
        'recurrence_data',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_recurring' => 'boolean',
        'recurrence_data' => 'array',
    ];

    public function getTypeBadgeAttribute()
    {
        return match($this->type) {
            'holiday' => '<span class="badge bg-danger">Holiday</span>',
            'celebration' => '<span class="badge bg-success">Celebration</span>',
            'event' => '<span class="badge bg-primary">Event</span>',
            'deadline' => '<span class="badge bg-warning">Deadline</span>',
            'meeting' => '<span class="badge bg-info">Meeting</span>',
            default => '<span class="badge bg-secondary">Event</span>',
        };
    }

    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('M d, Y');
    }

    public function getIsTodayAttribute()
    {
        return $this->event_date->isToday();
    }

    public function getIsPastAttribute()
    {
        return $this->event_date->isPast();
    }

    public function getIsFutureAttribute()
    {
        return $this->event_date->isFuture();
    }

    public function getDaysUntilAttribute()
    {
        $today = now()->startOfDay();
        $eventDate = $this->event_date;

        if ($eventDate->isPast()) {
            return $today->diffInDays($eventDate) . ' days ago';
        } elseif ($eventDate->isToday()) {
            return 'Today';
        } else {
            return 'In ' . $today->diffInDays($eventDate) . ' days';
        }
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'holiday' => '#dc3545',
            'celebration' => '#28a745',
            'event' => '#007bff',
            'deadline' => '#ffc107',
            'meeting' => '#17a2b8',
            default => '#6c757d',
        };
    }

    public function getTypeRgbAttribute()
    {
        return match($this->type) {
            'holiday' => '220, 53, 69',
            'celebration' => '40, 167, 69',
            'event' => '0, 123, 255',
            'deadline' => '255, 193, 7',
            'meeting' => '23, 162, 184',
            default => '108, 117, 125',
        };
    }

    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'holiday' => '<i class="fas fa-gift"></i>',
            'celebration' => '<i class="fas fa-birthday-cake"></i>',
            'event' => '<i class="fas fa-calendar-star"></i>',
            'deadline' => '<i class="fas fa-clock"></i>',
            'meeting' => '<i class="fas fa-users"></i>',
            default => '<i class="fas fa-calendar"></i>',
        };
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->startOfDay());
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->startOfDay());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('event_date', now()->month)
                    ->whereYear('event_date', now()->year);
    }
}
