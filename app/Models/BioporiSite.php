<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BioporiSite extends Model
{
    protected $fillable = [
        'household_id',
        'seq',              // ✅ FIX: supaya seq tersimpan
        'code',
        'location_note',
        'diameter_cm',
        'depth_cm',
        'lat',
        'lng',
        'status',
        'installed_at',
        'notes',
    ];

    protected $casts = [
        'installed_at' => 'date',
        'seq' => 'integer',      // ✅ biar konsisten
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }
}
