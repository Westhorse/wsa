<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'uuid' => $this->uuid ?? null,
            'total' => $this->total,
            'amount' => $this->amount,
            'user_id' => $this->user_id,
            'package_id' => $this->package_id,
            'coupon_id' => $this->coupon_id,
            'packagePrice' => $this->total_price_package,
            'totalDelegatesPrice' => $this->total_price_delegate,
            'totalSpousesPrice' => $this->total_price_spouse,
            'total_price_sponsorship_items' => $this->total_price_sponsorship_items,
            'coupon' => new CouponResource($this->coupon),
            'package' => new PackageOrderResource($this->package),
            'delegates' => DelegateOrderResource::collection($this->delegates),
            'spouses' => SpouseResource::collection($this->spouses),
            'userPersons' => PersonsOrderResource::collection($this->userPersons),
            'sponsorshipItems' => SponsorshipItemShortResource::collection($this->sponsorshipItems),
            'allSponsorshipItems' => SponsorshipItemOrderResource::collection($this->sponsorshipItems),
            'rooms' => OrderRoomResource::collection($this->whenLoaded('rooms')),
            'status' => $this->status,
            'updated_at' => $this->updated_at ? $this->updated_at->format('F d, Y - h:i A') : null,
            'created_at' => $this->created_at ? $this->created_at->format('F d, Y - h:i A') : null,
        ];
    }
}
