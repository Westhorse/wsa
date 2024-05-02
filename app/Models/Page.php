<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends BaseModel
{
    protected $guarded = ['id'];
    protected $table = "pages";

    protected $casts = [
        'active' => 'boolean'
    ];

    public function pageSections(): BelongsToMany
    {
        return $this->belongsToMany(PageSection::class, 'pages_page_sections', 'page_id', 'page_section_id')->withPivot('order_id');
    }

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class, 'network_id');
    }
}
