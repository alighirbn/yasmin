<?php

namespace App\Models\Cash;

use App\Models\Cash\CashTransfer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransferArchive extends Model
{
    use HasFactory;
    protected $table = 'cash_transfer_archive';

    protected $fillable = [
        'cash_transfer_id',
        'image_path',
        'user_id_create',
        'user_id_update',
    ];


    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create', 'id');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }

    public function cash_transfer()
    {
        return $this->belongsTo(CashTransfer::class);
    }
}
