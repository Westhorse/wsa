<?php

namespace App\Http\Resources\Conference;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventCompanyLoginResource extends JsonResource
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

        $filteredOrders =
            $this->whenLoaded('orders', function () {
                $userId = $this->id;
                $orders = Order::where('user_id', $userId)
                    ->whereNotIn('status', ['approved_online_payment', 'approved_bank_transfer'])
                    ->first();
                return new OrderResource($orders);
            });


        $filteredOrdersApproved =
            $this->whenLoaded('orders', function () {
                $userId = $this->id;
                $orders = Order::where('user_id', $userId)
                    ->whereIn('status', ['approved_online_payment', 'approved_bank_transfer'])
                    ->get();
                return OrderResource::collection($orders);
            });

        $currentConference = $this->conferences->where('id', $conferenceId)->first();





        $totalOrdersCount = $this->orders->count();


        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->company_email ?? $this->email ?? null,
            'countryName' => $this->country->name,
            'addressLineOne' => $this->address_line1,
            'addressLineTwo' => $this->address_line2,
            'phone' => $this->phone ?? null,
            'countryFlag' => $this->country->getFirstMediaUrl(),
            'phoneKey' => $this->phoneKey->key ?? null,
            'countryCode' => $this->country->code ?? null,
            'fax' => $this->fax ?? null,
            'faxKey' => $this->faxKey->key ?? null,

            'businessEst' => $this->business_est,
            'employeesNum' => $this->employees_num,

            'wsaId' => $this->wsa_id ?? null,
            'website' => $this->website ?? null,

            'totalSponsorshipItemsCount' => $this->sponsorshipItemsCount ?? 0,
            'totalDelegatesCount' => $this->ApprovedDelegates->count(),
            'totalRoomsCount' => $this->roomsCount ?? 0,
            'totalOrdersCount' => $totalOrdersCount,

            'state' => $this->state,
            'city' => $this->city,
            'profile' => $this->profile,
            'country_id' => $this->country_id,
            'accountType' => "company",
            'membershipType' => $currentConference->pivot->type ?? null,
            'image_url' => $this->getFirstMediaUrl(),
            'orders' => $filteredOrdersApproved ?? [],
            'pendingOrder' => $filteredOrders ?? null,
            'eventRegisterDate' => $this->conferences()->where('conference_id', $conferenceId)->first()?->pivot->created_at?->format('F d, Y - h:i A') ?? '',

        ];
    }
}
