<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventSectionPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,
            'slug' => $this->slug,
            'post_title' => $this->post_title,
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'divider' => $this->divider ?? (object)[
                'active' => null,
                'position' => null,
                'style' => null,
            ],
            'default' => $this->default,
            'button_one' => $this->button_one ?? (object)[
                'label' => null,
                'url' => null,
                'target' => null,
                'icon' => null,
                'style' => null,
            ],
            'button_one_active' => $this->button_one_active,
            'button_two' => $this->button_two ?? (object)[
                'label' => null,
                'url' => null,
                'target' => null,
                'icon' => null,
                'style' => null,
            ],
            'button_two_active' => $this->button_two_active,
            'video_url' => $this->video_url,
            'gallery' => $this->getMediaResource('gallery'),
            'image_url' => $this->getFirstMediaUrl(),
            'image' => $this->getFirstMediaResource(),

            'items' =>  EventItemResource::collection($this->eventItems->sortBy('order_id')) ?? [],

            'active' => $this->active,
            'order_id' => $this->order_id,
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
