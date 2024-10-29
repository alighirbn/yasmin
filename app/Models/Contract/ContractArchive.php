<?php

namespace App\Models\Contract;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractArchive extends Model
{
    use HasFactory;
    protected $table = 'contract_archive';

    protected $fillable = [
        'contract_id',
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

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
