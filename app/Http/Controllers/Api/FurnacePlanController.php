<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FurnacePlan;
use Illuminate\Http\Request;

class FurnacePlanController extends Controller
{
    public function index()
    {
        return FurnacePlan::latest()->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'household_id' => ['nullable','exists:households,id'],
            'title' => ['required','string','max:255'],
            'type' => ['nullable','string','max:100'],
            'capacity_liter' => ['nullable','integer','min:1','max:100000'],
            'design_summary' => ['nullable','string'],
            'material_list' => ['nullable','string'],
            'safety_notes' => ['nullable','string'],
            'location_note' => ['nullable','string','max:255'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
            'status' => ['nullable','in:draft,review,active'],
        ]);

        $plan = FurnacePlan::create($data);
        return response()->json($plan, 201);
    }

    public function show(FurnacePlan $furnacePlan)
    {
        return $furnacePlan->load('household');
    }

    public function update(Request $request, FurnacePlan $furnacePlan)
    {
        $data = $request->validate([
            'household_id' => ['nullable','exists:households,id'],
            'title' => ['sometimes','required','string','max:255'],
            'type' => ['nullable','string','max:100'],
            'capacity_liter' => ['nullable','integer','min:1','max:100000'],
            'design_summary' => ['nullable','string'],
            'material_list' => ['nullable','string'],
            'safety_notes' => ['nullable','string'],
            'location_note' => ['nullable','string','max:255'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
            'status' => ['nullable','in:draft,review,active'],
        ]);

        $furnacePlan->update($data);
        return $furnacePlan;
    }

    public function destroy(FurnacePlan $furnacePlan)
    {
        $furnacePlan->delete();
        return response()->noContent();
    }
}
