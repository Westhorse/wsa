<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'url' => '/member/' . $this->wsa_id . '/',
            'name' => $this->name,
            'lat' => $this->map_lat,
            'lng' => $this->map_long,
            'image_url' => $this->getFirstMediaUrl() ?? null,
            'description' => $this->country->name ?? null,
            'type' => "circle",
            'size' => "10",
        ];
    }
}
