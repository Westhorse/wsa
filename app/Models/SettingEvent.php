<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SettingEvent extends BaseModel
{
    use HasMedia;
    protected $table = "setting_events";

    protected $with = [
        'media',
    ];
    protected $guarded = ['id'];

    protected $casts = [
        'items' => 'array',
        'button' => 'array',
    ];


    public function parent(): BelongsTo
    {
        return $this->belongsTo(SettingEvent::class, 'parent_id');
    }
    public function children(): HasMany
    {
        return $this->hasMany(SettingEvent::class, 'parent_id');
    }
}

