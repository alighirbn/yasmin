<?php

namespace App\Models\Cash;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';

    protected $fillable = [
        'url_address',
        'cash_account_id',
        'transaction_amount',
        'transaction_date',
        'transaction_type',      // debit or credit
        'transactionable_id',    // Polymorphic ID
        'transactionable_type',  // Polymorphic type (Payment, Expense, etc.)
    ];

    public function cash_account()
    {
        return $this->belongsTo(Cash_Account::class);
    }

    // Polymorphic relationship
    public function transactionable()
    {
        return $this->morphTo();
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
