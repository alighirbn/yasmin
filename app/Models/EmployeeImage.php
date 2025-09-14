<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'image_path',
        'user_id_create',
    ];

    /**
     * Employee relationship
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
