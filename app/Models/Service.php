<?php

namespace App\Models;

class Service extends BaseModel
{
    protected $guarded = ['id'];
    protected $table = "services";

    protected $casts = [
        'active' => 'boolean'
    ];
}
