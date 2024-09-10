<?php

namespace App\Models\Cash;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $table = 'expenses';

    protected $fillable = [
        'url_address',
        'expense_type_id',
        'expense_amount',
        'expense_date',
        'expense_note',
        'approved', // New field
    ];

    // Method to approve the expense
    public function approve()
    {
        $this->approved = true;
        $this->save();
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function expense_type()
    {
        return $this->belongsTo(Expense_Type::class, 'expense_type_id');
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
