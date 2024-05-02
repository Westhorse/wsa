<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Country extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];


    protected $casts = [
        'active' => 'boolean'
    ];

    public function continent(): BelongsTo
    {
        return $this->belongsTo(Continent::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function eventContactUs(): HasMany
    {
        return $this->hasMany(EventContactUs::class);
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

    public function conferences(): hasMany
    {
        return $this->hasMany(Conference::class);
    }

}
