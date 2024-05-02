<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetMemberResource extends JsonResource
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
            'city' => $this->city,
            'state' => $this->state,
            'country_name' => $this->country->name,
//            'country_flag' => $this->country->getFirstMediaUrl(),
//            'country' => new CountryResource($this->country) ?? null,
            'image_url' => $this->getFirstMediaUrl() ?? null,
//            'image' => new MediaResource($this->getFirstMedia()) ?? null,
        ];
    }
}
