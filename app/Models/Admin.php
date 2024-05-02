<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends BaseModel
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'super_admin' => 'boolean',
    ];

    public function networks(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'admins_roles', 'admin_id', 'role_id')->withPivot('network_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'admins_roles', 'admin_id', 'role_id')->withPivot('network_id');
    }

    public function refs(): HasMany
    {
        return $this->hasMany(Ref::class);
    }
}

