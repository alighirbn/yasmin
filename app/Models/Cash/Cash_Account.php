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

    // ✅ Relationship with payments
    public function payments()
    {
        return $this->hasMany(Payment::class, 'cash_account_id', 'id');
    }

    // Relationship with transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'cash_account_id', 'id');
    }

    // Method to adjust balance
    public function adjustBalance($amount, $type)
    {
        // Recalculate the balance first to ensure it is correct
        $this->recalculateBalance();

        // Type can be 'credit' or 'debit'
        if ($type === 'credit') {
            $this->balance += $amount;
        } else if ($type === 'debit') {
            $this->balance -= $amount;
        }

        $this->save();
    }

    public function recalculateBalance()
    {
        // Sum all credits (transaction_type = 'credit')
        $creditSum = $this->transactions()->where('transaction_type', 'credit')->sum('transaction_amount');

        // Sum all debits (transaction_type = 'debit')
        $debitSum = $this->transactions()->where('transaction_type', 'debit')->sum('transaction_amount');

        // Set the new balance by subtracting debits from credits
        $this->balance = $creditSum - $debitSum;

        // Save the new balance
        $this->save();

        return $this->balance;
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
