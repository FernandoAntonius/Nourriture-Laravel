<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AgeClassification;
use Illuminate\Http\Request;

class AgeClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classifications = AgeClassification::all();
        return response()->json($classifications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'min_age' => 'required|integer|min:0',
            'max_age' => 'required|integer|gt:min_age',
            'description' => 'nullable|string',
        ]);

        $classification = AgeClassification::create($validated);
        return response()->json($classification, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AgeClassification $ageClassification)
    {
        return response()->json($ageClassification);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AgeClassification $ageClassification)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'min_age' => 'sometimes|integer|min:0',
            'max_age' => 'sometimes|integer|gt:min_age',
            'description' => 'nullable|string',
        ]);

        $ageClassification->update($validated);
        return response()->json($ageClassification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AgeClassification $ageClassification)
    {
        $ageClassification->delete();
        return response()->json(['message' => 'Age classification deleted successfully']);
    }

    /**
     * Classify age based on age value
     */
    public function classifyAge(Request $request)
    {
        $validated = $request->validate([
            'age' => 'required|integer|min:0|max:150',
        ]);

        $classification = AgeClassification::where('min_age', '<=', $validated['age'])
            ->where('max_age', '>=', $validated['age'])
            ->first();

        if (!$classification) {
            return response()->json(['message' => 'Age classification not found'], 404);
        }

        return response()->json([
            'age' => $validated['age'],
            'classification' => $classification,
        ]);
    }
}
