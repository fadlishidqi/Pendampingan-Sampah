<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminPurgeController extends Controller
{
    public function clearAllPoints()
    {
        $bio = DB::table('biopori_sites')
            ->whereNotNull('lat')
            ->orWhereNotNull('lng')
            ->update([
                'lat' => null,
                'lng' => null,
                'status' => 'planned',
                'updated_at' => now(),
            ]);

        $fur = DB::table('furnace_plans')
            ->whereNotNull('lat')
            ->orWhereNotNull('lng')
            ->update([
                'lat' => null,
                'lng' => null,
                'status' => 'draft',
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'Semua titik berhasil dihapus (koordinat dikosongkan).',
            'biopori_updated' => $bio,
            'furnace_updated' => $fur,
        ]);
    }
}
