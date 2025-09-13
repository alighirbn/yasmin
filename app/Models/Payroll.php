<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'total_incentives',
        'total_deductions',
        'net_salary',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
