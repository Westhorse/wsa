<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {

        $couponCount = DB::table('orders')
            ->where('coupon_id', $this->id)
            ->count();

        $available_count =  $this->count - $couponCount;

        return [
            'id' => $this->id,
            'code' => $this->code,
            'discount_value' => $this->discount_value,
            'discount_type' => $this->discount_type,
            'coupon_type' => $this->coupon_type,
            'available' => $available_count > 0,
            'available_count' => $available_count,
            'count' => $this->count,
            'active' => $this->active,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'deleted' => isset($this->deleted_at),
        ];
    }
}
