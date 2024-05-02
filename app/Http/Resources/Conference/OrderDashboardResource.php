<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDashboardResource extends JsonResource
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
            'total' => $this->total ?? 0,
            'amount' => $this->amount ?? 0,
            'user' => new EventMemberSimpleResource($this->user),
            'coupon_id' => $this->coupon_id,
            'totalDelegatesPrice' => $this->total_price_delegate,
            'totalSpousesPrice' => $this->total_price_spouse,
            'coupon' => new CouponResource($this->coupon),
            'package' => new PackageSimpleResource($this->package) ?? null,
            'delegates' => DelegateOrderResource::collection($this->delegates),
            'spouses' => SpouseResource::collection($this->spouses),
            'sponsorshipItems' => SponsorshipItemSimpleResource::collection($this->sponsorshipItems),
            'rooms' => OrderRoomResource::collection($this->whenLoaded('rooms')),
            'status' => $this->status,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d M, Y - H:i A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'created_at' => $this->created_at ? $this->created_at->format('F d, Y - h:i A') : null,
        ];
    }
}
