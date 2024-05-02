<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SponsorshipItem extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];
    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'is_infinity' => 'boolean',
        'is_featured' => 'boolean',
        'features' => 'array',
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'orders_sponsorship_item', 'order_id', 'sponsorship_item_id')
            ->withPivot('order_id', 'sponsorship_item_id', 'id', 'price_sponsorship_item')
            ->withTimestamps();
    }
}
