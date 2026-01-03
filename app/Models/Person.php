<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Person extends Model
{
    protected $table = 'persons';

    protected $fillable = [
        'name',
        'age',
        'age_classification_id',
        'email',
        'phone',
        'address',
    ];

    protected $casts = [
        'age' => 'integer',
    ];

    /**
     * Get the age classification for this person.
     */
    public function ageClassification(): BelongsTo
    {
        return $this->belongsTo(AgeClassification::class);
    }
}
