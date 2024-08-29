<?php

namespace App\Models\Contract;

use App\Models\Payment\Payment_Method;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;
    protected $table = 'installments';

    public function payment_method()
    {
        return $this->belongsTo(Payment_Method::class, 'payment_method_id', 'id');
    }

    protected $fillable = [
        'url_address',
        'installment_number',
        'installment_name',
        'installment_percent',
        'installment_period',

        'payment_method_id',

        'user_id_create',
        'user_id_update',
    ];
}
