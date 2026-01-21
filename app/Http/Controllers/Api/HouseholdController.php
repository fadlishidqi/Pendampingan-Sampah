<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Household;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    public function index()
    {
        return Household::latest()->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'head_name' => ['required','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'dusun' => ['nullable','string','max:255'],
            'rt' => ['nullable','string','max:20'],
            'rw' => ['nullable','string','max:20'],
            'address_text' => ['nullable','string'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
            'members_count' => ['nullable','integer','min:1','max:50'],
        ]);

        $household = Household::create($data);
        return response()->json($household, 201);
    }

    public function show(Household $household)
    {
        return $household->load(['bioporiSites','furnacePlans']);
    }

    public function update(Request $request, Household $household)
    {
        $data = $request->validate([
            'head_name' => ['sometimes','required','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'dusun' => ['nullable','string','max:255'],
            'rt' => ['nullable','string','max:20'],
            'rw' => ['nullable','string','max:20'],
            'address_text' => ['nullable','string'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
            'members_count' => ['nullable','integer','min:1','max:50'],
        ]);

        $household->update($data);
        return $household;
    }

    public function destroy(Household $household)
    {
        $household->delete();
        return response()->noContent();
    }
}
