<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends BaseModel
{
    protected $guarded = ['id'];


    protected $casts = [
        'active' => 'boolean'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function conferences(): hasMany
    {
        return $this->hasMany(Conference::class);
    }
}
