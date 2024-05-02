<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];
    protected $guarded = ['id'];

    protected $casts = [
        'public_show' => 'boolean',
        'active' => 'boolean',
        'public_types' => 'array',
        'features' => 'array',
        'persons' => 'array',
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'orders_rooms')
            ->withPivot('total_price', 'bed_type', 'start_date', 'end_date','delegate_id', 'id')
            ->withTimestamps();
    }
}
