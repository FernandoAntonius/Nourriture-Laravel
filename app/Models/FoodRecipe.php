<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodRecipe extends Model
{
    protected $fillable = [
        'food_id',
        'recipe_id',
        'quantity',
    ];
}
