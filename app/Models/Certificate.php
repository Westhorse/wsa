<?php

namespace App\Models;

class Certificate extends BaseModel
{
    protected $guarded = ['id'];
    protected $table = "certificates";

    protected $casts = [
        'active' => 'boolean'
    ];
}
