<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\AgeClassification;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $persons = Person::with('ageClassification')->get();
        return response()->json($persons);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:150',
            'age_classification_id' => 'required|exists:age_classifications,id',
            'email' => 'nullable|email|unique:persons,email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $person = Person::create($validated);
        return response()->json($person->load('ageClassification'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        return response()->json($person->load('ageClassification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'age' => 'sometimes|integer|min:0|max:150',
            'age_classification_id' => 'sometimes|exists:age_classifications,id',
            'email' => 'sometimes|email|unique:persons,email,' . $person->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $person->update($validated);
        return response()->json($person->load('ageClassification'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        $person->delete();
        return response()->json(['message' => 'Person deleted successfully']);
    }

    /**
     * Get all persons by age classification.
     */
    public function getByAgeClassification(AgeClassification $ageClassification)
    {
        $persons = $ageClassification->persons()->get();
        return response()->json([
            'age_classification' => $ageClassification,
            'persons' => $persons,
            'count' => $persons->count(),
        ]);
    }
}
