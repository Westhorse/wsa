<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends BaseModel
{
    protected $guarded = ['id'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
