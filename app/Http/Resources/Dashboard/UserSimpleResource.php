<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Common\CountryResource;
use App\Http\Resources\Conference\NetworkStatusResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $currentNetwork = getCurrentNetwork($this->networks);

        return [
            'id' => $this->id,
            'voted'=>DB::table('countries_users')->where('user_id' , $this->id)->exists() ,
            'ref' => $this->ref->admin ?? null,
            'name' => $this->name ?? null,
            'email' => $this->email ?? null,
            'wsa_id' => $this->wsa_id ?? null,
            'address_line1' => $this->address_line1 ?? null,
            'address_line2' => $this->address_line2 ?? null,
            'country_name' => $this->country->name ?? null,

            'city' => $this->city ?? null,
            'state' => $this->state ?? null,
            'postal_code' => $this->postal_code ?? null,
            'company_email' => $this->company_email ? $this->email : null,

            'phone' => $this->phone ?? null,
            'phone_key_id' => $this->phone_key_id ?? null,
            'fax' => $this->fax ?? null,
            'fax_key_id' => $this->fax_key_id ?? null,

            'website' => $this->website,
            'business_est' => $this->business_est,
            'employees_num' => $this->employees_num,
            'type_company' => $this->type_company ?? null,
            'country' => new CountryResource($this->country) ?? null,
            'image_url' => $this->getFirstMediaUrl(),

            'networks' => NetworkStatusResource::collection($this->networks) ?? [],

            'voting_active' => $this->voting_active ?? false,
        ];
    }
}
