<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class EnumTable extends BaseModel
{
    protected $guarded = ['id'];

    public function makeRequests(): HasMany
    {
        return $this->hasMany(MakeRequest::class);
    }
}
