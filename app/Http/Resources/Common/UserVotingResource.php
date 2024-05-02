<?php

namespace App\Http\Resources\Common;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserVotingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {

        $totalVotesCount = User::find($this->id)
            ->votedMembers()
            ->count();

        return [
            'id' => $this->id,
            'voted'=>DB::table('countries_users')->where('user_id' , $this->id)->exists() ,
            'name' => $this->name ?? null,
            'email' => $this->email ?? null,
            'wsa_id' => $this->wsa_id ?? null,
            'country_name' => $this->country->name ?? null,

            'city' => $this->city ?? null,
            'state' => $this->state ?? null,

            'type_company' => $this->type_company ?? null,
            'country' => new CountryResource($this->country) ?? null,
            'image_url' => $this->getFirstMediaUrl(),

            'total_votes' => $totalVotesCount,
            'voting_active' => $this->voting_active ?? false,
        ];
    }
}
