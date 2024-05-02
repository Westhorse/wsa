<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventPage extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];
    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function eventSectionPages(): HasMany
    {
        return $this->hasMany(EventSectionPage::class);
    }
}
