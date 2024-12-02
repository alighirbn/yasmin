<?php

namespace App\Models\Cash;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;
    protected $table = 'incomes';

    protected $fillable = [
        'url_address',
        'income_type_id',
        'income_amount',
        'income_date',
        'income_note',
        'approved', // New field

        'cash_account_id',

        'user_id_create',
        'user_id_update',
    ];

    // Method to approve the income
    public function approve()
    {
        $this->approved = true;
        $this->save();
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function income_type()
    {
        return $this->belongsTo(Income_Type::class, 'income_type_id');
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
