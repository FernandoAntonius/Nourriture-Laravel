<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Food::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:40',
            'measure' => 'required|numeric',
            'grams' => 'required|numeric',
            'calories' => 'sometimes|numeric|min:0',
            'protein' => 'sometimes|numeric|min:0',
            'fat' => 'sometimes|numeric|min:0',
            'sat_fat' => 'sometimes|numeric|min:0',
            'fiber' => 'sometimes|numeric|min:0',
            'carbs' => 'sometimes|numeric|min:0',
        ]);

        // Set default 0 untuk nutrition yang tidak diisi
        $validated['calories'] = $validated['calories'] ?? 0;
        $validated['protein'] = $validated['protein'] ?? 0;
        $validated['fat'] = $validated['fat'] ?? 0;
        $validated['sat_fat'] = $validated['sat_fat'] ?? 0;
        $validated['fiber'] = $validated['fiber'] ?? 0;
        $validated['carbs'] = $validated['carbs'] ?? 0;

        $food = Food::create($validated);
        return response()->json($food, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $food = Food::find($id);
        
        if (!$food) {
            return response()->json(['message' => 'Food not found'], 404);
        }

        return response()->json($food, 200);
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
        $food = Food::find($id);
        
        if (!$food) {
            return response()->json(['message' => 'Food not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:40',
            'measure' => 'sometimes|numeric',
            'grams' => 'sometimes|numeric',
            'calories' => 'sometimes|numeric|min:0',
            'protein' => 'sometimes|numeric|min:0',
            'fat' => 'sometimes|numeric|min:0',
            'sat_fat' => 'sometimes|numeric|min:0',
            'fiber' => 'sometimes|numeric|min:0',
            'carbs' => 'sometimes|numeric|min:0',
        ]);

        $food->update($validated);
        return response()->json($food, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $food = Food::find($id);
        
        if (!$food) {
            return response()->json(['message' => 'Food not found'], 404);
        }

        $food->delete();
        return response()->json(['message' => 'Food deleted'], 200);
    }
}
