<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicEventPageResource extends JsonResource
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
            'title' => $this->title,
            'keywords' => $this->keywords ,
            'description' => $this->description,
            'name' => $this->name,
            'slug' => $this->slug,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()),

            'sections' =>  EventSectionPageResource::collection($this->eventSectionPages->where('active', true)->sortBy('order_id')) ?? null ,

            'active' => $this->active,
            'order_id' => $this->order_id,
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
