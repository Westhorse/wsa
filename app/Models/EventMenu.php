<?php

namespace App\Models;

class EventMenu extends BaseModel
{

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'show_icon' => 'boolean'
    ];

}
