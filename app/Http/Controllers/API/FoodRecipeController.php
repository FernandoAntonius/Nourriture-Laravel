<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodRecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'recipe_id' => 'required|exists:recipes,id',
            'food_id' => 'required|exists:food,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $recipe = Recipe::find($validated['recipe_id']);
        $food = Food::find($validated['food_id']);

        if (!$recipe || !$food) {
            return response()->json(['message' => 'Recipe or Food not found'], 404);
        }

        // Attach atau update relasi
        $recipe->foods()->syncWithoutDetaching([
            $validated['food_id'] => ['quantity' => $validated['quantity']]
        ]);

        return response()->json([
            'message' => 'Food added to recipe',
            'recipe_id' => $validated['recipe_id'],
            'food_id' => $validated['food_id'],
            'quantity' => $validated['quantity']
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'food_id' => 'required|exists:food,id',
        ]);

        $recipe = Recipe::find($validated['recipe_id']);
        
        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        $recipe->foods()->detach($validated['food_id']);

        return response()->json(['message' => 'Food removed from recipe'], 200);
    }
}
