<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'address',
        'marital_status',
        'dob',
        'phone',
        'email',
        'department',
        'type',
        'position',
        'start_date',
        'onrole_date',
        'probation_start_date',
        'probation_end_date',
        'probation_status',
        'photo',
        'aadhaar_number',
        'wfh_pin',
    ];

    protected $casts = [
        'dob' => 'date',
        'start_date' => 'date',
        'onrole_date' => 'date',
        'probation_start_date' => 'date',
        'probation_end_date' => 'date',
    ];

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name);
    }

    public function generateEmployeeId()
    {
        do {
            $id = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        } while (self::where('employee_id', $id)->exists());

        return $id;
    }



    public function salary()
    {
        return $this->hasOne(Salary::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function loginCredentials()
    {
        return $this->hasOne(EmployeeLogin::class);
    }
}
