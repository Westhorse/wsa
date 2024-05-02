<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Benefit extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];

    protected $casts = [
    ];

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'benefit_id');
    }

    public function networks(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'benefits_networks', 'benefit_id', 'network_id')
            ->withPivot('active')
            ->withTimestamps();
    }
}
