<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Continent extends BaseModel
{
    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function countries() : HasMany
    {
        return $this->hasMany(Country::class);
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Country::class)
            ->distinct();
    }

    public function voters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'countries_users', 'country_id', 'user_id')
            ->withPivot('member_id')
            ->withTimestamps();
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'countries_users', 'country_id', 'member_id')
            ->withPivot('user_id')
            ->withTimestamps();
    }
}
