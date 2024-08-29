<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_Method extends Model
{
    use HasFactory;
    protected $table = 'payment_method';
    protected $fillable = [
        'method_name',
    ];
}
