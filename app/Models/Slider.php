<?php

namespace App\Models;

use App\Http\Traits\HasMedia;

class Slider extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];


    protected $casts = [
        'active' => 'boolean',
        'link_type' => 'boolean',
        'button_one_active' => 'boolean',
        'button_two_active' => 'boolean'
    ];

}
