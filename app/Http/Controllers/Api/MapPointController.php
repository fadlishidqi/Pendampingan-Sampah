<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BioporiSite;
use App\Models\FurnacePlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapPointController extends Controller
{
    // GET /api/map-points
    public function index(): JsonResponse
    {
        $points = $this->getMergedPoints();

        return response()->json([
            'count' => count($points),
            'points' => $points,
        ]);
    }

    // POST /api/proker-points
    public function storeProkerPoint(Request $request): JsonResponse
    {
        $data = $request->validate([
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'note' => ['nullable', 'string', 'max:255'],
            'household_id' => ['nullable', 'exists:households,id'],
        ]);

        return DB::transaction(function () use ($data) {
            $seq = $this->nextSeq();
            $code = $this->formatCode($seq);
            $note = $data['note'] ?? 'Titik proker (biopori+tungku)';

            BioporiSite::create([
                'household_id' => $data['household_id'] ?? null,
                'seq' => $seq,
                'code' => $code,
                'location_note' => $note,
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'status' => 'active',
            ]);

            FurnacePlan::create([
                'household_id' => $data['household_id'] ?? null,
                'seq' => $seq,
                'title' => $code,
                'type' => 'drum',
                'capacity_liter' => null,
                'design_summary' => null,
                'material_list' => null,
                'safety_notes' => null,
                'location_note' => $note,
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'status' => 'active',
            ]);

            return response()->json([
                'message' => "Titik #{$seq} berhasil disimpan.",
                'seq' => $seq,
                'pair_code' => $code,
            ], 201);
        });
    }

    // DELETE /api/proker-points/{seq}
    public function destroyProkerPoint(int $seq): JsonResponse
    {
        return DB::transaction(function () use ($seq) {
            BioporiSite::where('seq', $seq)->delete();
            FurnacePlan::where('seq', $seq)->delete();

            // rapikan nomor + kode
            $bioRows = BioporiSite::where('seq', '>', $seq)->orderBy('seq')->get(['id', 'seq']);
            foreach ($bioRows as $r) {
                $newSeq = (int)$r->seq - 1;
                BioporiSite::where('id', $r->id)->update([
                    'seq' => $newSeq,
                    'code' => $this->formatCode($newSeq),
                ]);
            }

            $furRows = FurnacePlan::where('seq', '>', $seq)->orderBy('seq')->get(['id', 'seq']);
            foreach ($furRows as $r) {
                $newSeq = (int)$r->seq - 1;
                FurnacePlan::where('id', $r->id)->update([
                    'seq' => $newSeq,
                    'title' => $this->formatCode($newSeq),
                ]);
            }

            return response()->json([
                'message' => "Titik #{$seq} berhasil dihapus. Penomoran & kode dirapikan.",
            ]);
        });
    }

    // PATCH /api/admin/clear-all-points
    // ✅ FIX: jangan pakai DB::transaction karena ALTER TABLE menyebabkan implicit commit
    public function clearAllPoints(): JsonResponse
    {
        $bioCount = (int) DB::table('biopori_sites')->count();
        $furCount = (int) DB::table('furnace_plans')->count();

        // Hapus semua data titik + reset autoincrement (tanpa transaction)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('biopori_sites')->delete();
        DB::table('furnace_plans')->delete();

        DB::statement('ALTER TABLE biopori_sites AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE furnace_plans AUTO_INCREMENT = 1');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return response()->json([
            'message' => 'Semua titik berhasil dihapus dan penomoran kembali dari 1.',
            'biopori_deleted' => $bioCount,
            'furnace_deleted' => $furCount,
        ]);
    }

    // ===== Helpers =====

    private function getMergedPoints(): array
    {
        $bio = BioporiSite::query()
            ->select(['seq', 'code', 'location_note', 'lat', 'lng', 'status'])
            ->orderBy('seq')
            ->get();

        $points = [];
        foreach ($bio as $b) {
            $points[] = [
                'type' => 'proker',
                'seq' => (int) $b->seq,
                'pair_code' => $b->code,
                'note' => $b->location_note,
                'lat' => $b->lat !== null ? (float)$b->lat : null,
                'lng' => $b->lng !== null ? (float)$b->lng : null,
                'status' => $b->status,
            ];
        }

        return $points;
    }

    private function nextSeq(): int
    {
        $maxB = (int) (BioporiSite::max('seq') ?? 0);
        $maxF = (int) (FurnacePlan::max('seq') ?? 0);
        return max($maxB, $maxF) + 1;
    }

    private function formatCode(int $seq): string
    {
        return 'PROKER-' . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
    }
}
