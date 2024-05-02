<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class TshirtSize extends BaseModel
{
    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean'
    ];
    public function tshirtSizeDelegates(): HasMany
    {
        return $this->hasMany(Delegate::class, 'tshirt_size_id');
    }
}
