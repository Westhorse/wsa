<?php

namespace App\Http\Resources\Conference;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ShowCouponResource extends JsonResource
{
    public function toArray($request): array
    {

        $couponCount = DB::table('orders')
            ->where('coupon_id', $this->id)
            ->count();

        $available_count =  $this->count - $couponCount;

        $users = User::whereHas('orders', function ($query) {
            $query->where('coupon_id', $this->id);
        })->get();


        $userArray = $users->map(function($user) {
            return new EventCompanyCouponResource($user);
        });

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
            'totalUsedCompanies' => $userArray,
        ];
    }
}
