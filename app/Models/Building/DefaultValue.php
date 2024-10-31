<?php

namespace App\Models\Building;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultValue extends Model
{
    use HasFactory;

    protected $table = 'default_values';

    protected $fillable = ['price_per_meter'];
}
