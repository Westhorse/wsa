<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'event_section_page_id' => $this->event_section_page_id,
            'title' => $this->title,
            'icon' => $this->icon,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'url' => $this->url,
            'order_id' => $this->order_id,
            'active' => $this->active,
            'button_active' => $this->button_active,
            'post_title' => $this->post_title,
            'sub_title' => $this->sub_title,

            'button' => $this->button ?? (object)[
                'label' => null,
                'url' => null,
                'target' => null,
                'icon' => null,
                'style' => null,
            ],

            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()),


            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
