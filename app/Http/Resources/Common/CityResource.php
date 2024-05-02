<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'country' => new CountryResource($this->country) ?? null,
            'order_id' => $this->order_id,
            'country_name' => $this->country->name ?? null,
            'country_id' => $this->country_id,
            'lat' => $this->lat ?? null,
            'lng' => $this->lng ?? null,
            'active' => $this->active,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,

        ];
    }
}
