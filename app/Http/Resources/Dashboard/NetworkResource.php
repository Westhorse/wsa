<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NetworkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'slug' => $this->slug ?? null,
            'domain' => $this->domain ?? null,
            'active' => $this->active ?? false,
            'collection' => $this->collection ?? false,
            'order_id' => $this->order_id ?? null,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia())
        ];
    }
}
