<?php

namespace App\Models;

class Referral extends BaseModel
{
    protected $guarded = ['id'];
    protected $table = "referrals";

    protected $casts = [
        'active' => 'boolean'
    ];
}
