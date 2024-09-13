<?php

namespace App\Models\Cash;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransfer extends Model
{
    use HasFactory;

    protected $table = 'cash_transfers';

    protected $fillable = [
        'url_address',
        'transfer_date',
        'from_account_id',
        'to_account_id',
        'amount',
        'transfer_note',
        'approved',
        'user_id_create',
        'user_id_update',
    ];

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function fromAccount()
    {
        return $this->belongsTo(Cash_Account::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(Cash_Account::class, 'to_account_id');
    }

    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create', 'id');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }
}
