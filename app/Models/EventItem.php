<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventItem extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];
    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'button' => 'array'
    ];

    public function eventSectionPage(): BelongsTo
    {
        return $this->belongsTo(EventSectionPage::class);
    }
}
