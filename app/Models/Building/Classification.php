<?php

namespace App\Models\Building;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    use HasFactory;

    protected $table = 'classifications';

    protected $fillable = ['name', 'price_per_meter'];
}
