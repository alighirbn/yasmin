<?php

namespace App\Models\Building;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building_Type extends Model
{
    use HasFactory;
    protected $table = 'building_type';
    protected $fillable = [
        'type_name',
    ];
}
