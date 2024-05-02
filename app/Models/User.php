<?php

namespace App\Models;

use App\Http\Resources\Conference\OrderRoomResource;
use App\Http\Traits\HasMedia;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Stripe\Stripe;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;
    use SoftDeletes;
    use HasMedia;

    protected $with = [
        'media',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'tos_acceptance' => 'boolean',
        'voting_active' => 'boolean',
    ];

    // Master Data Class Relationships --------------------------------

    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class, 'users_certificates', 'user_id', 'certificate_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'users_services', 'user_id', 'service_id');
    }

    public function networks(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'users_networks', 'user_id', 'network_id')->withPivot(['fpp', 'network', 'active', 'status', 'type', 'start_date', 'expire_date', 'created_at']);
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class, 'conferences_users', 'user_id', 'conference_id')->withPivot(['user_id', 'conference_id', 'type', 'created_at', 'updated_at'])->withTimestamps();
    }


    public function votedMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'countries_users', 'user_id', 'member_id')
            ->withPivot('country_id')
            ->withTimestamps();
    }

    public function voters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'countries_users', 'member_id', 'user_id')
            ->withPivot('country_id')
            ->withTimestamps();
    }

    public function Referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class, 'referral_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    // User Data Class Relationships --------------------------------
    public function contactPersons(): HasMany
    {
        return $this->hasMany(ContactPerson::class);
    }

    public function tradeReferences(): HasMany
    {
        return $this->hasMany(TradeReference::class);
    }

    public function ref(): BelongsTo
    {
        return $this->belongsTo(Ref::class);
    }

    public function getSponsorshipItemsAttribute()
    {
        if (!$this->relationLoaded('orders')) {
            $this->load('orders.sponsorshipItems');
        }

        return $this->orders->flatMap(function ($order) {
            return $order->sponsorshipItems->toArray();
        });
    }

    public function getRoomsAttribute()
    {
        if (!$this->relationLoaded('orders')) {
            $this->load('orders.rooms');
        }

        return $this->orders->flatMap(function ($order) {
            return OrderRoomResource::collection($order->rooms)->toArray(request());
        });
    }

    public function getSponsorshipItemsCountAttribute()
    {
        if (!$this->relationLoaded('orders')) {
            $this->load('orders.sponsorshipItems');
        }

        return $this->orders->flatMap(function ($order) {
            if (in_array($order->status, ['approved_online_payment', 'approved_bank_transfer'])) {
                return $order->sponsorshipItems;
            }
            return [];
        })->count();
    }


    public function getSponsorshipItemsAllToAttribute()
    {
        if (!$this->relationLoaded('orders')) {
            $this->load('orders.sponsorshipItems');
        }

        return $this->orders->flatMap(function ($order) {
            if (in_array($order->status, ['approved_online_payment', 'approved_bank_transfer'])) {
                return $order->sponsorshipItems;
            }
            return [];
        });
    }

    public function getRoomsCountAttribute()
    {
        if (!$this->relationLoaded('orders')) {
            $this->load('orders.rooms');
        }

        return $this->orders->flatMap(function ($order) {
            if (in_array($order->status, ['approved_online_payment', 'approved_bank_transfer'])) {
                return $order->rooms;
            }
            return [];
        })->count();
    }

    public function getApprovedDelegatesAttribute()
    {
        if (!$this->relationLoaded('orders')) {
            $this->load('orders.delegates');
        }

        return $this->orders->flatMap(function ($order) {
            if (in_array($order->status, ['approved_online_payment', 'approved_bank_transfer'])) {
                return $order->delegates;
            }
            return [];
        });
    }


    public function getTotalDelegatesAttribute()
    {
        if (!$this->relationLoaded('orders')) {
            $this->load('orders.delegates');
        }

        return $this->orders->flatMap(function ($order) {
                return $order->delegates;

            return [];
        });
    }

    public function getPackagesAttribute()
    {
        if (!$this->relationLoaded('orders')) {
            $this->load('orders.package');
        }

        return $this->orders->map(function ($order) {
            return $order->package;
        })->first();
    }


    public function delegates(): HasMany
    {
        return $this->hasMany(Delegate::class, 'user_id')->where('type', 'delegate');
    }

    public function spouses(): HasMany
    {
        return $this->hasMany(Delegate::class, 'user_id')->where('type', 'spouse');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    // Country Class Relationships --------------------------------

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function phoneKey(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'phone_key_id');
    }

    public function faxKey(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'fax_key_id');
    }

    public function detectedCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'detected_country_id');
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function stripe(): HasMany
    {
        return $this->hasMany(Stripe::class);
    }


    public function delegatesWithRooms(): HasManyThrough
    {
        return $this->hasManyThrough(
            Delegate::class,
            Order::class,
            'user_id',  // الخارجي
            'order_id', // الداخلي في الجدول الوسيط
            'id',       // الداخلي في جدول النهاية
            'id'        // الخارجي في جدول الوسيط
        )->whereHas('rooms');
    }


    // ---------------------------------------- Scopes --------------------------------
    public function scopeUserStatus($query, array $statusArray)
    {
        return $query->whereHas('networks', function ($query) use ($statusArray) {
            $query->whereIn('status', $statusArray);
        });
    }

    public function scopeOrdersCount($query, $status = null)
    {
        return $query->withCount(['orders' => function (Builder $query) use ($status) {
            if ($status === 'approved') {
                $query->whereIn('status', ['approved_online_payment', 'approved_bank_transfer']);
            } elseif ($status === 'pending') {
                $query->whereNotIn('status', ['approved_online_payment', 'approved_bank_transfer']);
            }
        }]);
    }
}
