<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends BaseModel
{

    protected $guarded = ['id'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    public function networks(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'admins_roles', 'admin_id', 'role_id')->withPivot('network_id');
    }
}
