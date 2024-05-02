<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Setting extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];

    public function networks(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'settings_networks', 'setting_id', 'network_id')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Setting::class, 'parent_id');
    }
    public function children(): HasMany
    {
        return $this->hasMany(Setting::class, 'parent_id');
    }
}
