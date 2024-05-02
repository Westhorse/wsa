<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'type' => $this->type,
            'title' => $this->title,
            'job_title' => $this->job_title ?? null,
            'email' => $this->email ?? null,
            'user_id' => $this->user_id ?? null,
            'order_id' => $this->order_id,
            'spouses' => SponsorResource::collection($this->spouses),
            'image_url' => $this->getFirstMediaUrl(),
            'extra_dietaries' => $this->extra_dietaries,
            'tshirt_size_id' => $this->tshirt_size_id,
            'dietaries' => DietaryShortResource::collection($this->dietaries) ?? [],
            'phone' => $this->phone,
            'cell' => $this->cell,
            'phoneKey' => $this->phoneKey->key,
            'cellKey' => $this->cellKey->key,

        ];
    }
}
