<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventMemberSimpleResource extends JsonResource
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
            'name' => $this->name ?? null,
            'email' => $this->email ?? null,
            'wsa_id' => $this->wsa_id ?? null,
            'countryName' => $this->country->name ?? null,
            'countryFlag' => $this->country->getFirstMediaUrl(),
            'city' => $this->city ?? null,
            'state' => $this->state ?? null,
            'image_url' => $this->getFirstMediaUrl(),
        ];
    }
}
