<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'key' => $this->key ?? null,
            'code' => $this->code ?? null,
            'active' => $this->active ?? null,
            'image_url' => $this->getFirstMediaUrl() ?? null,
            'image' => $this->getFirstMediaResource() ?? null,
            'order_id' => $this->order_id ?? null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'deleted' => isset($this->deleted_at),
        ];
    }
}
