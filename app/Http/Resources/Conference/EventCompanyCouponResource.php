<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventCompanyCouponResource extends JsonResource
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
            'email ' => $this->email ,
            'CountryFlag' => $this->country->getFirstMediaUrl(),
            'city' => $this->city,
            'countryName' => $this->country->name,
        ];
    }
}
