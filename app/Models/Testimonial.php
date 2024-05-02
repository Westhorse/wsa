<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'show_home' => 'boolean'
    ];
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
