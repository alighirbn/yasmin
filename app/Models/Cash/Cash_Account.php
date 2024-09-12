<?php

namespace App\Models\Cash;

use App\Models\Payment\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash_Account extends Model
{
    use HasFactory;
    protected $table = 'cash_accounts';

    protected $fillable = [
        'url_address',
        'account_name',  // e.g., 'Main Account', 'Savings Account'
        'balance',       // Current balance of the cash account

        'user_id_create',
        'user_id_update',
    ];

    // Relationship with transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Method to adjust balance
    public function adjustBalance($amount, $type)
    {
        // Type can be 'credit' or 'debit'
        if ($type === 'credit') {
            $this->balance += $amount;
        } else if ($type === 'debit') {
            $this->balance -= $amount;
        }

        $this->save();
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
