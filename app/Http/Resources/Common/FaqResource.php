<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $networkId = $request->header('X-Network-ID');
        $networkBenefit = $this->networks->where('pivot.network_id', $networkId);
        $networkBenefitActive = $networkBenefit->first();
        $activeValue = $networkBenefitActive['pivot']['active'] ?? null;

        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'des' => $this->des ?? '',
            'networks' => $this->networks->map(function ($item) {
                return [
                    'network_id' =>  $item->pivot->network_id,
                    'active' =>  $item->pivot->active
                ];
            }),
            'active'=> $activeValue ?? '',
            'order_id' => $this->order_id ?? '',
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
