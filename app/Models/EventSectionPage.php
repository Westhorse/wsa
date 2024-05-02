<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventSectionPage extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];
    protected $guarded = ['id'];

    protected $casts = [
        'button_one' => 'array',
        'button_two' => 'array',
        'divider' => 'array',
        'default' => 'boolean',
        'button_one_active' => 'boolean',
        'button_two_active' => 'boolean',
        'active' => 'boolean',
    ];

    public function eventPage(): BelongsTo
    {
        return $this->belongsTo(EventPage::class);
    }

    public function eventItems(): HasMany
    {
        return $this->hasMany(EventItem::class);
    }
}
