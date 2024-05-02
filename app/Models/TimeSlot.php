<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TimeSlot extends Model
{
    use  LogsActivity;
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
    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
        'default_status' => 'boolean',
    ];

    public function day(): BelongsTo
    {
        return $this->belongsTo(EventDay::class, 'day_id');
    }

    public function delegates(): BelongsToMany
    {
        return $this->belongsToMany(Delegate::class, 'delegates_time_slots', 'time_slot_id', 'delegate_id')
            ->withPivot('id','delegate_id', 'time_slot_id', 'status', 'delegate_request_id', 'status_request','table_number','zoom_link','is_online')
            ->withTimestamps();
    }
}
