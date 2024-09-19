<?php

namespace App\Models\Building;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building_Category extends Model
{
    use HasFactory;
    protected $table = 'building_category';
    protected $fillable = [
        'url_address',
        'category_name',
        'category_area',
        'category_cost',
    ];

    public function buildings()
    {
        return $this->hasMany(Building::class, 'building_category_id', 'id');
    }
}
