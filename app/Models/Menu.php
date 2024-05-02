<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends BaseModel
{

    protected $guarded = ['id'];


    protected $casts = [
        'active' => 'boolean'
    ];

    public function subMenus(): HasMany
    {
        return $this->hasMany(SubMenu::class);
    }

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class, 'network_id');
    }
}
