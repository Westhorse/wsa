<?php

namespace App\Models;

use App\Http\Traits\HasMedia;

class Partner extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean'
    ];
}
