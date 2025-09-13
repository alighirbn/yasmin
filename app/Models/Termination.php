<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Termination extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'termination_date',
        'reason',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
