<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDashboardViewResource extends JsonResource
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
            'packageId' => $this->package_id,
            'packagePrice' => $this->total_price_package,
            'totalDelegatesPrice' => $this->total_price_delegate,
            'totalSpousesPrice' => $this->total_price_spouse,
            'totalPriceSponsorshipItems' => $this->total_price_sponsorship_items,
            'coupon' => new CouponResource($this->coupon),
            'company' => new EventCompanySimpleResource($this->user),
            'package' => new PackageOrderResource($this->package),
            'delegates' => DelegateDashboardOrderResource::collection($this->delegates),
            'spouses' => SpouseResource::collection($this->spouses),
            'sponsorshipItems' => SponsorshipItemOrderResource::collection($this->sponsorshipItems),
            'rooms' => OrderRoomResource::collection($this->rooms),
            'status' => $this->status,
            'updatedAt' => $this->updated_at ? $this->updated_at->format('F d, Y - h:i A') : null,
            'createdAt' => $this->created_at ? $this->created_at->format('F d, Y - h:i A') : null,
        ];
    }
}
