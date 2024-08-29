<?php

namespace App\Models\Building;

use App\Models\Contract\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;
    protected $table = 'buildings';

    public function contract()
    {
        return $this->hasOne(Contract::class, 'contract_building_id', 'id');
    }

    public function building_type()
    {
        return $this->belongsTo(Building_Type::class, 'building_type_id', 'id');
    }
    public function building_category()
    {
        return $this->belongsTo(Building_Category::class, 'building_category_id', 'id');
    }



    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create', 'id');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }

    protected $fillable = [
        'url_address',

        'building_number',
        'block_number',
        'house_number',
        'building_area',
        'building_map_x',
        'building_map_y',

        'building_type_id',
        'building_category_id',

        'user_id_create',
        'user_id_update',
    ];
}
