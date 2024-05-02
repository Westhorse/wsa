<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SponsorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'description' => $this->description,
            'sponsorship_name' => $this->sponsorship_name,
            'event_sponsor' => $this->event_sponsor,
            'active' => $this->active,
            'order_id' => $this->order_id,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()),
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'deleted' => isset($this->deleted_at),
        ];
    }
}
