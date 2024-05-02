<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Network extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];


    protected $casts = [
        'active' => 'boolean',
        'collection' => 'boolean'
    ];

    public function settings(): BelongsToMany
    {
        return $this->belongsToMany(Setting::class, 'settings_networks', 'network_id', 'setting_id')
            ->withPivot('value')
            ->withTimestamps();
    }


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'admins_roles', 'admin_id', 'role_id')->withPivot(['network_id']);
    }

    public function benefits(): BelongsToMany
    {
        return $this->belongsToMany(Benefit::class, 'benefits_networks', 'network_id', 'benefit_id')
            ->withPivot('active')
            ->withTimestamps();
    }

    public function faqs(): BelongsToMany
    {
        return $this->belongsToMany(Faq::class, 'faqs_networks', 'network_id', 'faq_id')
            ->withPivot('active')
            ->withTimestamps();
    }
}
