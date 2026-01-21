<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FurnacePlan extends Model
{
    protected $fillable = [
        'household_id',
        'seq',              // ✅ FIX: supaya seq tersimpan
        'title',
        'type',
        'capacity_liter',
        'design_summary',
        'material_list',
        'safety_notes',
        'location_note',
        'lat',
        'lng',
        'status',
    ];

    protected $casts = [
        'seq' => 'integer',      // ✅ biar konsisten
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }
}
