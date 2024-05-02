<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventPageResource extends JsonResource
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
            'name' => $this->name,
            'title' => $this->title,
            'slug' => $this->slug,
            'keywords' => $this->keywords ,
            'description' => $this->description,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()),

            'sections' =>  EventSectionPageResource::collection($this->eventSectionPages->sortBy('order_id')) ?? null ,

            'active' => $this->active,
            'order_id' => $this->order_id,
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
