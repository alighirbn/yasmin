<?php

namespace App\Models\Customer;

use App\Models\Contract\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';

    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create', 'id');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'contract_customer_id', 'id');
    }

    protected $fillable = [
        'url_address',

        'customer_full_name',
        'customer_phone',
        'customer_email',
        'customer_card_number',
        'customer_card_issud_auth',
        'customer_card_issud_date',
        'mother_full_name',
        'full_address',
        'address_card_number',
        'saleman',

        'user_id_create',
        'user_id_update',
    ];
}
