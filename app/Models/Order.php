<?php

namespace App\Models;

use App\Helpers\Constants;
use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use HasMedia, LogsActivity;
    protected $table = "orders";
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*'])->logOnlyDirty();
    }
    public function scopeFilter($builder, $filters = null, $filterOperator = "=")
    {
        if (isset($filters) && is_array($filters)) {
            foreach ($filters as $field => $value) {
                if ($value == Constants::NULL)
                    $builder->whereNull($field);
                elseif ($value == Constants::NOT_NULL)
                    $builder->whereNotNull($field);
                elseif (is_array($value))
                    $builder->whereIn($field, $value);
                elseif ($filterOperator == "like")
                    $builder->where($field, $filterOperator, '%' . $value . '%');
                else
                    $builder->where($field, $value);
            }
        }
        return $builder;
    }
    protected $with = [
        'media',
    ];
    protected $casts = [

        'total_approved' => 'array',
        'persons' => 'array',

    ];

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }


    public function delegates(): HasMany
    {
        return $this->hasMany(Delegate::class, 'order_id')->where('type', 'delegate');
    }

    public function spouses(): HasMany
    {
        return $this->hasMany(Delegate::class, 'order_id')->where('type', 'spouse');
    }
    public function userPersons(): HasMany
    {
        return $this->hasMany(Delegate::class, 'order_id');
    }


    public function sponsorshipItems(): BelongsToMany
    {
        return $this->belongsToMany(SponsorshipItem::class, 'orders_sponsorship_item', 'order_id', 'sponsorship_item_id')
            ->withPivot('order_id', 'sponsorship_item_id', 'id','price_sponsorship_item')
            ->withTimestamps();
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'orders_rooms')
            ->withPivot('total_price', 'bed_type', 'start_date', 'end_date', 'delegate_id', 'id')
            ->withTimestamps();
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class, 'conference_id');
    }

    public function stripe(): HasMany
    {
        return $this->hasMany(Stripe::class);
    }
}

