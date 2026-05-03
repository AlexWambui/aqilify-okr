<?php

namespace App\Models\Okrs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quarter extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class, 'year_id');
    }
}
