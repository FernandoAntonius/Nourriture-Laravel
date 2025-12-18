<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\RecipeController;
use App\Http\Controllers\API\FoodRecipeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('foods', FoodController::class);
Route::apiResource('recipes', RecipeController::class);

Route::post('/food-recipes', [FoodRecipeController::class, 'store']);
Route::delete('/food-recipes', [FoodRecipeController::class, 'destroy']);
