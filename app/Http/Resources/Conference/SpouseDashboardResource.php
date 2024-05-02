<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\EventCompanySimpleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpouseDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $orderComplete = $this->order->status === 'approved_online_payment' || $this->order->status === 'approved_bank_transfer';
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'type' => $this->type,
            'user_id' => $this->user_id,
            'company' => new EventCompanySimpleResource($this->user),
            'delegate_id' => $this->delegate_id,
            'tshirt_size_id' => $this->tshirt_size_id,
            'order_id' => $this->order_id,
            'extra_dietaries' => $this->extra_dietaries,
            'dietaries' => DietaryShortResource::collection($this->dietaries) ?? null,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => $this->getFirstMediaResource(),
            'order_complete' => $orderComplete,
            'orderStatus' => $this->order->status,
        ];
    }
}
