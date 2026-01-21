<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Household extends Model
{
    // Kolom yang boleh diisi dari form / API
    protected $fillable = [
        'head_name',
        'phone',
        'dusun',
        'rt',
        'rw',
        'address_text',
        'lat',
        'lng',
        'members_count',
    ];

    // 1 rumah bisa punya banyak biopori
    public function bioporiSites(): HasMany
    {
        return $this->hasMany(BioporiSite::class);
    }

    // 1 rumah bisa punya beberapa rencana tungku
    public function furnacePlans(): HasMany
    {
        return $this->hasMany(FurnacePlan::class);
    }
}
