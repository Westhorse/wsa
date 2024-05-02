<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventHelpCenter extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'list' => 'array'
    ];

    public function eventHelpCenter(): BelongsTo
    {
        return $this->belongsTo(EventHelpCenter::class, 'parent_id');
    }
}
