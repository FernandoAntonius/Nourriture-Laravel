<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    protected $table = 'histories';

    protected $fillable = [
        'user_id',
        'name',
        'confidence',
        'description',
    ];

    protected $casts = [
        'confidence' => 'decimal:2',
    ];

    /**
     * Get the user that owns this history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
