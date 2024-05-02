<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageSectionMobileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title ?? null,
            'description' => $this->description ?? null,
            'video_url' => $this->video_url ?? null,

            // 'slug' => $this->slug,
            // 'title' => $this->title,
            // 'description' => $this->description,
            // 'video_url' => $this->video_url,
            // 'image_url' => $this->getFirstMediaUrl(),
            // 'image' => $this->getFirstMediaResource(),
            // 'active' => $this->active,
            // 'order_id' => $this->order_id,
            // 'deleted' => isset($this->deleted_at),
            // 'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
