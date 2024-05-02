<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactPerson extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $guarded = ['id'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function phoneKey(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'phone_key_id');
    }

    public function cellKey(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'cell_key_id');
    }
}
