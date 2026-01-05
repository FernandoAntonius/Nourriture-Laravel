<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\History;
use Illuminate\Http\Request;

class PredictController extends Controller
{
    /**
     * Display all predictions (GET /predict)
     */
    public function index()
    {
        $predictions = Person::all();
        
        return response()->json([
            'success' => true,
            'message' => 'All predictions retrieved successfully',
            'data' => $predictions,
            'count' => $predictions->count(),
        ]);
    }

    /**
     * Create a new age prediction (POST /predict)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $person = Person::create($validated);

        // Save to history if user is authenticated
        if ($request->user()) {
            History::create([
                'user_id' => $request->user()->id,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Prediction created successfully',
            'data' => $person,
        ], 201);
    }

    /**
     * Get a specific prediction (GET /predict/{id})
     */
    public function show($id)
    {
        $person = Person::find($id);

        if (!$person) {
            return response()->json([
                'success' => false,
                'message' => 'Prediction not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Prediction retrieved successfully',
            'data' => $person,
        ]);
    }

    /**
     * Update a prediction (PUT/PATCH /predict/{id})
     */
    public function update(Request $request, $id)
    {
        $person = Person::find($id);

        if (!$person) {
            return response()->json([
                'success' => false,
                'message' => 'Prediction not found',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $person->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Prediction updated successfully',
            'data' => $person,
        ]);
    }

    /**
     * Delete a prediction (DELETE /predict/{id})
     */
    public function destroy($id)
    {
        $person = Person::find($id);

        if (!$person) {
            return response()->json([
                'success' => false,
                'message' => 'Prediction not found',
            ], 404);
        }

        $deletedPerson = $person;
        $person->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prediction deleted successfully',
            'data' => $deletedPerson,
        ]);
    }

}
