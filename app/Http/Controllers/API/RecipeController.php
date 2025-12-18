<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recipes = Recipe::with('foods')->get();
        return response()->json($recipes, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:40',
            'description' => 'nullable|string|max:255',
        ]);

        $recipe = Recipe::create($validated);
        return response()->json($recipe, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $recipe = Recipe::with('foods')->find($id);
        
        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        $nutrition = $recipe->calculateTotalNutrition();
        
        return response()->json([
            'id' => $recipe->id,
            'name' => $recipe->name,
            'description' => $recipe->description,
            'foods' => $recipe->foods,
            'total_nutrition' => $nutrition,
            'created_at' => $recipe->created_at,
            'updated_at' => $recipe->updated_at,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $recipe = Recipe::find($id);
        
        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:40',
            'description' => 'sometimes|nullable|string|max:255',
        ]);

        $recipe->update($validated);
        return response()->json($recipe, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $recipe = Recipe::find($id);
        
        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        $recipe->delete();
        return response()->json(['message' => 'Recipe deleted'], 200);
    }
}
