<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class EventCompanySimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $conferenceId = $request->header('X-Conference-Id');
        $currentConference = $this->conferences->where('id', $conferenceId)->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'countryName' => $this->country->name,
            'countryFlag' => $this->country->getFirstMediaUrl(),
            'state' => $this->state,
            'city' => $this->city,
            'accountType' => "company",
            'membershipType' => $currentConference->pivot->type ?? null,
            'image_url' => $this->getFirstMediaUrl(),
        ];
    }
}
