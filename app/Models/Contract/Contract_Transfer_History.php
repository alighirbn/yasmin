<?php

namespace App\Models\Contract;

use App\Models\Customer\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract_Transfer_History extends Model
{
    use HasFactory;
    protected $table = 'contract_transfer_histories';
    public function approve()
    {
        $this->approved = true;
        $this->save();
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function oldcustomer()
    {
        return $this->belongsTo(Customer::class, 'old_customer_id');
    }

    public function newcustomer()
    {
        return $this->belongsTo(Customer::class, 'new_customer_id');
    }


    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create', 'id');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }

    protected $fillable = [
        'url_address',
        'transfer_date',
        'transfer_amount',
        'transfer_note',
        'old_customer_picture',
        'new_customer_picture',

        'contract_id',
        'old_customer_id',
        'new_customer_id',

        'approved',
        'user_id_create',
        'user_id_update',
    ];
}
