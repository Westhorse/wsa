<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Conference extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'hotel_booking_max_duration' => 'array',
        'duration' => 'array',
        'virtual' => 'boolean',
        'early_bird_active' => 'boolean',
        'early_bird_end_date' => 'datetime',
        'reg_deadline_date' => 'datetime',
        'eb_member_delegate_price' => 'float',
        'eb_member_spouse_price' => 'float',
        'eb_non_member_delegate_price' => 'float',
        'eb_non_member_spouse_price' => 'float',
        'member_delegate_price' => 'float',
        'member_spouse_price' => 'float',
        'non_member_delegate_price' => 'float',
        'non_member_spouse_price' => 'float',

    ];

    public function eventDay(): HasMany
    {
        return $this->hasMany(EventDay::class, 'conference_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conferences_users', 'user_id', 'conference_id', 'type');
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'conference_id');
    }

    /**
     * Determine if the end date is past.
     */
    public function isEndDatePast(): Attribute
    {
        $duration = json_decode($this->attributes['duration'], true);
        $isEndDatePast = false;
        if (isset($duration) && count($duration) === 2) {
            $end_date = Carbon::createFromFormat('d-m-Y', $duration[1]);
            $isEndDatePast = $end_date->isPast();
        }
        return Attribute::make(
            get: fn () => $isEndDatePast,
        );
    }
}
