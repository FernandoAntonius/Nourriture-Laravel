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
}
