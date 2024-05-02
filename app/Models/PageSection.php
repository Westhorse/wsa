<?php

namespace App\Models;

use App\Http\Traits\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageSection extends BaseModel
{
    use HasMedia;

    protected $with = [
        'media',
    ];

    protected $guarded = ['id'];

    protected $table = "page_sections";

    protected $casts = [
        'active' => 'boolean',
        'button_one_active' => 'boolean',
        'button_two_active' => 'boolean',
    ];



    public function parent(): BelongsTo
    {
        return $this->belongsTo(PageSection::class, 'parent_id');
    }
    public function children(): HasMany
    {
        return $this->hasMany(PageSection::class, 'parent_id');
    }
}
