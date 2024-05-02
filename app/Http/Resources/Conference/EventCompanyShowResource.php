<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Common\CountryResource;
use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventCompanyShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $currentNetwork = getCurrentNetwork($this->networks);
        $pendingNetworks = getPendingNetworks($this->networks);
        $conferenceId = $request->header('X-Conference-Id');
//////////////////////
        $filteredOrders = $this->orders
            ->whereNotIn('status', ['approved_online_payment', 'approved_bank_transfer']);
        $filteredOrderObject = new OrderResource($filteredOrders->first());

        $filteredOrdersApproved = $this->orders
            ->whereIn('status', ['approved_online_payment', 'approved_bank_transfer']);

        $filteredOrdersApprovedArray = OrderResource::collection($filteredOrdersApproved);
//////////////////////
        $sponsorshipItems = $this->getSponsorshipItemsAttribute();
        $packages = $this->getPackagesAttribute();
        $room = $this->getRoomsAttribute();
        $currentConference = $this->conferences->where('id', $conferenceId)->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->company_email ?? $this->email ?? null,
            'phone' => $this->phone ?? null,
            'phoneKey' => $this->phoneKey->key ?? null,
            'fax' => $this->fax ?? null,
            'faxKey' => $this->faxKey->key ?? null,

            'businessEst' => $this->business_est,
            'employeesNum' => $this->employees_num,

            'wsaId' => $this->wsa_id ?? null,
            'website' => $this->website ?? null,

            'state' => $this->state,
            'city' => $this->city,
            'profile' => $this->profile,
            'country_id' => $this->country_id,
            'accountType' => "company",

            'membershipType' => $currentConference->pivot->type ?? null,


            'image_url' => $this->getFirstMediaUrl(),

            'orders' => $filteredOrdersApprovedArray ?? [],
            'pendingOrder' => $filteredOrderObject ?? null,

            // Extra Data
            'ref' => $this->ref->admin ?? null,
            'detected_country_name' => $this->detectedCountry->name ?? null,
            'detected_country' => new CountryResource($this->detectedCountry) ?? null,
            'unhashed_password' => $this->unhashed_password,
            'wsa_id' => $this->wsa_id,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'map_long' => $this->map_long,
            'map_lat' => $this->map_lat,
            'slogan' => $this->slogan,
            'postal_code' => $this->postal_code,
            'company_email' => $this->company_email ?? $this->email,

            'phone_key_id' => $this->phone_key_id ?? null,
            'phone_key' => $this->phoneKey->key ?? null,
            'fax_key_id' => $this->fax_key_id ?? null,
            'fax_key' => $this->faxKey->key ?? null,

            'branches' => $this->branches,
            'business_est' => $this->business_est,
            'employees_num' => $this->employees_num,
            'ref_value' => $this->ref_value,
            'type_company' => $this->type_company ?? null,
            'tos_acceptance' => $this->tos_acceptance ?? null,
            'referral_id' => $this->referral_id ?? null,
            'country_name' => $this->country->name ?? null,
            'country' => new CountryResource($this->country) ?? null,
            'image' => new MediaResource($this->getFirstMedia()) ?? null,

            // Networks Details
            'networks' => NetworkStatusResource::collection($this->networks) ?? null,
            'currentNetworkStatus' => new NetworkStatusResource($currentNetwork) ?? null,
            'pendingNetworkStatus' => NetworkStatusResource::collection($pendingNetworks) ?? [],

            //Event relationships and items
            'delegates' => DelegateResource::collection($this->delegates) ?? [],
            'spouses' => SpouseResource::collection($this->spouses) ?? [],
            'sponsorshipItems' => $sponsorshipItems ?? [],
            'packages' => $packages ?? [],

            'rooms' => $room,


            'eventRegisterDate' => $this->conferences()->where('conference_id', $conferenceId)->first()?->pivot->created_at?->format('F d, Y - h:i A') ?? '',

        ];
    }
}
