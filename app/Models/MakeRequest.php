<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MakeRequest extends BaseModel
{
    protected $guarded = ['id'];

    public function enumTable(): BelongsTo
    {
        return $this->belongsTo(EnumTable::class);
    }
}
