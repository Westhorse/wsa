<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SponsorshipItemSimpleResource extends JsonResource
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
            'price' => $this->price,
            'earlybird_price' => $this->earlybird_price,
            'image_url' => $this->getFirstMediaUrl(),
        ];
    }
}








