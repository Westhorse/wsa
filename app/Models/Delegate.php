<?php

namespace App\Models;

use App\Helpers\Constants;
use App\Http\Traits\HasMedia;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Delegate extends Model
{
    use HasApiTokens, HasMedia, LogsActivity , Authenticatable;

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

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function tshirt_size(): BelongsTo
    {
        return $this->belongsTo(TshirtSize::class, 'tshirt_size_id');
    }

    public function phoneKey(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'phone_key_id');
    }

    public function cellKey(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'cell_key_id');
    }

    public function dietaries(): BelongsToMany
    {
        return $this->belongsToMany(Dietary::class, 'delegates_dietaries', 'delegate_id', 'dietary_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function delegate(): BelongsTo
    {
        return $this->belongsTo(Delegate::class, 'delegate_id');
    }
    public function spouses(): HasMany
    {
        return $this->hasMany(Delegate::class, 'delegate_id');
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'orders_rooms')
            ->withPivot('total_price', 'bed_type', 'start_date', 'end_date', 'delegate_id')
            ->withTimestamps();
    }

    public function timeSlots(): BelongsToMany
    {
        return $this->belongsToMany(TimeSlot::class, 'delegates_time_slots', 'delegate_id', 'time_slot_id')
            ->withPivot('id','delegate_id', 'time_slot_id', 'status', 'delegate_request_id', 'status_request','table_number','zoom_link','is_online')
            ->withTimestamps();
    }

}
