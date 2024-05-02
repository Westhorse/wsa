<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dietary extends  BaseModel
{
    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean'
    ];

 

    public function dietPersons(): BelongsToMany
    {
        return $this->belongsToMany(Delegate::class, 'delegates_dietaries', 'dietary_id', 'delegate_id')->withPivot('delegate_id');
    }
}
