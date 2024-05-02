<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Conference\NetworkStatusResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserAuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $currentNetwork = getCurrentNetwork($this->networks);
        $pendingNetworks = getPendingNetworks($this->networks);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'voted'=>DB::table('countries_users')->where('user_id' , $this->id)->exists() ,
            'email' => $this->email,
            'wsa_id' => $this->wsa_id,
            'group' => new GroupPublicResource($this->group) ?? null,
            'address_line1' => $this->address_line1 ?? null,
            'address_line2' => $this->address_line2 ?? null,
            'map_long' => $this->map_long,
            'map_lat' => $this->map_lat,
            'slogan' => $this->slogan,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'company_email' => $this->company_email ?? $this->email,
            'phone' => $this->phone ?? null,
            'phone_key_id' => $this->phone_key_id ?? null,
            'phone_key' => $this->phoneKey->key ?? null,
            'fax' => $this->fax ?? null,
            'fax_key_id' => $this->fax_key_id ?? null,
            'fax_key' => $this->faxKey->key ?? null,

            'website' => $this->website,
            'profile' => $this->profile,
            'branches' => $this->branches,
            'business_est' => $this->business_est,
            'employees_num' => $this->employees_num,
            'other_certificates' => $this->other_certificates ?? null,
            'other_services' => $this->other_services ?? null,
            'type_company' => $this->type_company ?? null,
            'country_id' => $this->country_id ?? null,
            'country_name' => $this->country->name ?? null,
            'country' => new CountryResource($this->country) ?? null,
            'image_url' => $this->getFirstMediaUrl() ?? null,
            'image' => new MediaResource($this->getFirstMedia()) ?? null,
            'contactPersons' => ContactPersonResource::collection($this->contactPersons) ?? null,
            'services'   => $this->services->pluck('id') ?? null,
            'certificates' =>$this->certificates->pluck('id') ?? null,

            // Networks Details
            'networks' => NetworkStatusResource::collection($this->networks) ?? [],
            'currentNetworkStatus' => new NetworkStatusResource($currentNetwork) ?? null,
            'pendingNetworkStatus' => NetworkStatusResource::collection($pendingNetworks) ?? [],

            'voting_active' => $this->voting_active ?? false,
        ];
    }
}
