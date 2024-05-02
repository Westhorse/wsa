<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Faq extends BaseModel
{
    protected $guarded = ['id'];


    protected $casts = [
        'active' => 'boolean'
    ];

    public function networks(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'faqs_networks', 'faq_id', 'network_id')
            ->withPivot('active')
            ->withTimestamps();
    }

}
