<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Common\CountryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventDelegateLoginResource extends JsonResource
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
            'email' => $this->email,
            'job_title' => $this->job_title,
            'phone_key_id' => $this->phone_key_id,
            'phoneKey' => new CountryResource($this->phoneKey),
            'phone' => $this->phone,
            'cell_key_id' => $this->cell_key_id,
            'cellKey' => new CountryResource($this->cellKey),
            'cell' => $this->cell,
            'accountType' => "delegate",
            'tshirt_size_id' => $this->tshirt_size_id,
            'tshirtSize' => new TshirtSizeResource($this->tshirt_size),
            'spouses' => SpouseResource::collection($this->spouses),
            'dietaries' => DietaryResource::collection($this->dietaries),
            'extra_dietaries' => $this->extra_dietaries,
            'company' => new EventCompanyLoginResource($this->user),
            'image_url' => $this->getFirstMediaUrl(),
        ];
    }
}
