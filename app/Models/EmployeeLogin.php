<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class EmployeeLogin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'email',
        'password',
        'is_active',
        'last_login_at',
        'login_attempts',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationship with Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Accessors & Mutators
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // Helper methods
    public function getStatusBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-danger">Inactive</span>';
    }

    public function getFormattedLastLoginAttribute()
    {
        return $this->last_login_at
            ? $this->last_login_at->format('M d, Y \a\t h:i A')
            : 'Never';
    }

    // Scope for active accounts
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for inactive accounts
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Check if account is locked due to too many failed attempts
    public function isAccountLocked()
    {
        return $this->login_attempts >= 5 && !$this->is_active;
    }

    // Reset login attempts
    public function resetLoginAttempts()
    {
        $this->update(['login_attempts' => 0]);
    }

    // Increment login attempts
    public function incrementLoginAttempts()
    {
        $this->increment('login_attempts');
    }

    // Record successful login
    public function recordLogin()
    {
        $this->update([
            'last_login_at' => now(),
            'login_attempts' => 0
        ]);
    }

    // Generate a password in format: name#(employeeid)
    public static function generateSecurePassword($employee = null)
    {
        if ($employee) {
            // Generate password in format: firstname#(employee_id)
            return $employee->first_name . '#' . $employee->employee_id;
        }

        // Fallback to old method if no employee provided
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        $password = '';

        for ($i = 0; $i < 8; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $password;
    }
}
