<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = [
        'name',
        'measure',
        'grams',
        'calories',
        'protein',
        'fat',
        'sat_fat',
        'fiber',
        'carbs',
    ];

    public function recipes(){
        return $this->belongsToMany(
            Recipe::class,
            'food_recipes',
            'food_id',
            'recipe_id'
        )->withPivot('quantity')
         ->withTimestamps();
    }
}
