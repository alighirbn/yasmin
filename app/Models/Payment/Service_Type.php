<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_Type extends Model
{
    use HasFactory;
    protected $table = 'service_types';
    protected $fillable = [
        'type_name',
    ];
}
