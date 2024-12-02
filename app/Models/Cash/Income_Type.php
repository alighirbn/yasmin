<?php

namespace App\Models\Cash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income_Type extends Model
{
    use HasFactory;
    protected $table = 'income_types';

    protected $fillable = [

        'income_type',
    ];
}
