<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventCompanyUpdateResource extends JsonResource
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
            'employees_num' => $this->employees_num,
            'website' => $this->website,
            'profile' => $this->profile,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'slogan' => $this->slogan,
            'phone_key_id' => $this->phone_key_id,
            'phone' => $this->phone,
            'fax_key_id' => $this->fax_key_id,
            'fax' => $this->fax,
            'image' => new MediaResource($this->getFirstMedia()),
        ];
    }
}
