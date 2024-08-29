<?php

namespace App\Models\Contract;

use App\Models\Payment\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract_Installment extends Model
{
    use HasFactory;
    protected $table = 'contract_installments';

    public function payment()
    {
        return $this->hasOne(Payment::class, 'contract_installment_id', 'id');
    }


    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class, 'installment_id', 'id');
    }



    protected $fillable = [
        'url_address',
        'installment_amount',
        'installment_date',

        'contract_id',
        'installment_id',


        'user_id_create',
        'user_id_update',
    ];
}
