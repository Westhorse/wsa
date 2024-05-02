<?php

namespace App\Models;

use App\Http\Traits\HasMedia;

class News extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'featured' => 'boolean',
        'publish_date' => 'datetime'
    ];

}
