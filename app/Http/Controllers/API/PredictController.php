<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\History;
use App\Models\AgeClassification;
use Illuminate\Http\Request;

class PredictController extends Controller
{
    /**
     * Display all predictions (GET /predict)
     */
    public function index()
    {
        $predictions = Person::with('ageClassification')->get();
        
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
            'age' => 'required|integer|min:0|max:150',
            'email' => 'nullable|email|unique:persons,email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Find the age classification based on the provided age
        $classification = AgeClassification::where('min_age', '<=', $validated['age'])
            ->where('max_age', '>=', $validated['age'])
            ->first();

        if (!$classification) {
            return response()->json([
                'success' => false,
                'message' => 'Age classification not found for the provided age',
                'age' => $validated['age'],
            ], 404);
        }

        // Create person with the classification
        $validated['age_classification_id'] = $classification->id;
        $person = Person::create($validated);

        // Save to history if user is authenticated
        if ($request->user()) {
            History::create([
                'user_id' => $request->user()->id,
                'name' => $validated['name'],
                'age' => $validated['age'],
                'age_classification_id' => $classification->id,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Age prediction created successfully',
            'data' => $person->load('ageClassification'),
        ], 201);
    }

    /**
     * Get a specific prediction (GET /predict/{id})
     */
    public function show($id)
    {
        $person = Person::with('ageClassification')->find($id);

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
            'age' => 'sometimes|integer|min:0|max:150',
            'email' => 'sometimes|email|unique:persons,email,' . $person->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // If age is updated, recalculate the classification
        if (isset($validated['age'])) {
            $classification = AgeClassification::where('min_age', '<=', $validated['age'])
                ->where('max_age', '>=', $validated['age'])
                ->first();

            if (!$classification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Age classification not found for the provided age',
                    'age' => $validated['age'],
                ], 404);
            }

            $validated['age_classification_id'] = $classification->id;
        }

        $person->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Prediction updated successfully',
            'data' => $person->load('ageClassification'),
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

    /**
     * Predict age classification from age value (POST /predict/classify)
     */
    public function predictClassification(Request $request)
    {
        $validated = $request->validate([
            'age' => 'required|integer|min:0|max:150',
        ]);

        $classification = AgeClassification::where('min_age', '<=', $validated['age'])
            ->where('max_age', '>=', $validated['age'])
            ->first();

        if (!$classification) {
            return response()->json([
                'success' => false,
                'message' => 'Age classification not found',
                'age' => $validated['age'],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Age classification predicted successfully',
            'age' => $validated['age'],
            'classification' => $classification,
        ]);
    }
}
