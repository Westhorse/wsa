<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Conference\NetworkStatusResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSimpleIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $currentNetwork = getCurrentNetwork($this->networks);
        //        $pendingNetworks = getPendingNetworks($this->networks);

        return [
            'id' => $this->id,
            //            'ref' => $this->ref->admin ?? null,
            //            'voted'=>DB::table('countries_users')->where('user_id' , $this->id)->exists() ,
            'name' => $this->name,
            //            'detected_country_name' => $this->detectedCountry->name ?? null,
            //            'detected_country' => new CountryResource($this->detectedCountry) ?? null,
            //            'email' => $this->email,
            'wsa_id' => $this->wsa_id,
            //            'address_line1' => $this->address_line1,
            //            'address_line2' => $this->address_line2,
            //            'map_long' => $this->map_long,
            //            'map_lat' => $this->map_lat,
            //            'slogan' => $this->slogan,
            'city' => $this->city,
            'state' => $this->state,
            //            'postal_code' => $this->postal_code,
            'company_email' => $this->company_email ?? $this->email,

            //            'phone' => $this->phone ?? null,
            //            'phone_key_id' => $this->phone_key_id ?? null,
            //            'phone_key' => $this->phoneKey->key ?? null,
            //            'fax' => $this->fax ?? null,
            //            'fax_key_id' => $this->fax_key_id ?? null,
            //            'fax_key' => $this->faxKey->key ?? null,

            //            'website' => $this->website,
            //            'profile' => $this->profile,
            //            'branches' => $this->branches,
            //            'business_est' => $this->business_est,
            //            'employees_num' => $this->employees_num,
            //            'ref_value' => $this->ref_value,
            //            'other_certificates' => $this->other_certificates ?? null,
            //            'other_services' => $this->other_services ?? null,
            'type_company' => $this->type_company ?? null,
            //            'tos_acceptance' => $this->tos_acceptance ?? null,
            //            'referral_id' => $this->referral_id ?? null,
            //            'country_id' => $this->country_id ?? null,
            'country_name' => $this->country->name ?? null,
            'country_flag' => $this->country->getFirstMediaUrl() ?? null,
            //            'country' => new CountryResource($this->country) ?? null,
            //            'role' => $this->role ?? null,
            'image_url' => $this->getFirstMediaUrl() ?? null,
            //            'image' => new MediaResource($this->getFirstMedia()) ?? null,
            //            'contactPersons' => ContactPersonResource::collection($this->contactPersons) ?? null,
            //            'tradeReferences' => TradeReferenceResource::collection($this->tradeReferences) ?? null,
            //            'services'   => ServiceIDResource::collection($this->services) ?? null,
            //            'certificates' => ServiceIDResource::collection($this->certificates) ?? null,

            // Networks Details
            //            'networks' => NetworkStatusResource::collection($this->networks) ?? null,
            'currentNetworkStatus' => new NetworkStatusResource($currentNetwork) ?? null,
            //            'pendingNetworkStatus' => NetworkStatusResource::collection($pendingNetworks) ?? [],

            //            'voting_active' => $this->voting_active ?? false,
        ];
    }
}
