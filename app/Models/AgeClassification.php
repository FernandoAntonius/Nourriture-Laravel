<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgeClassification extends Model
{
    protected $table = 'age_classifications';

    protected $fillable = [
        'name',
        'min_age',
        'max_age',
        'description',
    ];

    protected $casts = [
        'min_age' => 'integer',
        'max_age' => 'integer',
    ];

    /**
     * Get all persons in this age classification.
     */
    public function persons(): HasMany
    {
        return $this->hasMany(Person::class);
    }
}
