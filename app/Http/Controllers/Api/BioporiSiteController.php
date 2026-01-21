<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BioporiSite;
use Illuminate\Http\Request;

class BioporiSiteController extends Controller
{
    public function index()
    {
        return BioporiSite::latest()->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'household_id' => ['nullable','exists:households,id'],
            'code' => ['required','string','max:50','unique:biopori_sites,code'],
            'location_note' => ['nullable','string','max:255'],
            'diameter_cm' => ['nullable','integer','min:1','max:200'],
            'depth_cm' => ['nullable','integer','min:1','max:500'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
            'status' => ['nullable','in:planned,active,maintenance,inactive'],
            'installed_at' => ['nullable','date'],
            'notes' => ['nullable','string'],
        ]);

        $site = BioporiSite::create($data);
        return response()->json($site, 201);
    }

    public function show(BioporiSite $bioporiSite)
    {
        return $bioporiSite->load('household');
    }

    public function update(Request $request, BioporiSite $bioporiSite)
    {
        $data = $request->validate([
            'household_id' => ['nullable','exists:households,id'],
            'code' => ['sometimes','required','string','max:50','unique:biopori_sites,code,' . $bioporiSite->id],
            'location_note' => ['nullable','string','max:255'],
            'diameter_cm' => ['nullable','integer','min:1','max:200'],
            'depth_cm' => ['nullable','integer','min:1','max:500'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
            'status' => ['nullable','in:planned,active,maintenance,inactive'],
            'installed_at' => ['nullable','date'],
            'notes' => ['nullable','string'],
        ]);

        $bioporiSite->update($data);
        return $bioporiSite;
    }

    public function destroy(BioporiSite $bioporiSite)
    {
        $bioporiSite->delete();
        return response()->noContent();
    }
}
