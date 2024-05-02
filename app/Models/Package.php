<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];
    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'is_infinity' => 'boolean',
        'features' => 'array',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class , 'package_id');
    }

}

