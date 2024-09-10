<?php

namespace App\Models\Cash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense_Type extends Model
{
    use HasFactory;
    protected $table = 'expenses';

    protected $fillable = [
        'url_address',
        'expense_type',
    ];
}
