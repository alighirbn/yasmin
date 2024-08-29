<?php

namespace App\Models\Payment;

use App\Models\Contract\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $table = 'services';


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
        return $this->belongsTo(Contract::class, 'service_contract_id', 'id');
    }
    public function service_type()
    {
        return $this->belongsTo(Service_Type::class, 'service_type_id', 'id');
    }



    protected $fillable = [
        'url_address',

        'service_amount',
        'service_date',
        'service_note',

        'service_contract_id',
        'service_type_id',

        'user_id_create',
        'user_id_update',
    ];
}
