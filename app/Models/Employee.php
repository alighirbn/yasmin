<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'department',
        'position',
        'basic_salary',
        'hire_date',
        'termination_date',
        'status',
    ];

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function incentives()
    {
        return $this->hasMany(Incentive::class);
    }

    public function advances()
    {
        return $this->hasMany(Advance::class);
    }

    public function terminations()
    {
        return $this->hasMany(Termination::class);
    }

    public function images()
    {
        return $this->hasMany(\App\Models\EmployeeImage::class);
    }
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
