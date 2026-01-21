<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapPoint extends Model
{
    protected $fillable = [
        'household_id',
        'title',
        'note',
        'lat',
        'lng',
        'status',
    ];
}
