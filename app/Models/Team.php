<?php

namespace App\Models;

use App\Http\Traits\HasMedia;

class Team extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];
    protected $guarded = ['id'];
    protected $casts = [
        'active' => 'boolean',
        'show_home' => 'boolean'
    ];
}
