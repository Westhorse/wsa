<?php

namespace App\Http\Resources\Common;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetUserVoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
       $DBContinents = Country::where('id', $this->country_id)->first();
       $DBUsers = User::where('id', $this->member_id)->first();
       return [
            'id' => $this->id,
            'country' => new CountryResource($DBContinents)  ?? null,
            'member' => new UserVotingResource($DBUsers)  ?? null,
        ];
    }
}
