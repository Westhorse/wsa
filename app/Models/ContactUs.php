<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactUs  extends BaseModel
{
    protected $guarded = ['id'];

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class, 'network_id');
    }
}
