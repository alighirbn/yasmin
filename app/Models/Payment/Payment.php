<?php

namespace App\Models\Payment;

use App\Models\Cash\Transaction;
use App\Models\Contract\Contract;
use App\Models\Contract\Contract_Installment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';


    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create', 'id');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'payment_contract_id', 'id');
    }

    public function contract_installment()
    {
        return $this->belongsTo(Contract_Installment::class, 'contract_installment_id', 'id');
    }

    // Define the polymorphic relationship with transactions
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function approve()
    {
        $this->approved = true;
        $this->save();
    }


    protected $fillable = [
        'url_address',
        'payment_amount',
        'payment_date',
        'payment_note',
        'approved', // New field

        'payment_contract_id',
        'contract_installment_id',

        'user_id_create',
        'user_id_update',
    ];
}
