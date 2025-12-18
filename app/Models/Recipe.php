<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function foods(){
        return $this->belongsToMany(
            Food::class,
            'food_recipes',
            'recipe_id',
            'food_id'
        )->withPivot('quantity')
         ->withTimestamps();
    }

    public function calculateTotalNutrition()
    {
        $total = [
            'calories' => 0,
            'protein' => 0,
            'fat' => 0,
            'sat_fat' => 0,
            'fiber' => 0,
            'carbs' => 0,
        ];

        foreach ($this->foods as $food) {
            $quantity = $food->pivot->quantity;
            $total['calories'] += $food->calories * $quantity;
            $total['protein'] += $food->protein * $quantity;
            $total['fat'] += $food->fat * $quantity;
            $total['sat_fat'] += $food->sat_fat * $quantity;
            $total['fiber'] += $food->fiber * $quantity;
            $total['carbs'] += $food->carbs * $quantity;
        }

        return $total;
    }
}
