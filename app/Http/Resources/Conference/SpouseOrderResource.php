<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpouseOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $orderComplete = $this->order->status === 'approved_online_payment' || $this->order->status === 'approved_bank_transfer';
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'type' => $this->type,

            'user_id' => $this->user_id,
            'delegate_id' => $this->delegate_id,
            'tshirt_size_id' => $this->tshirt_size_id,
            'order_id' => $this->order_id,
            'extra_dietaries' => $this->extra_dietaries,
            'dietaries' => DietaryShortResource::collection($this->dietaries) ?? [],

            'image_url' => $this->getFirstMediaUrl(),
            'image' => $this->getFirstMediaResource(),
            'order_complete' => $orderComplete,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'deleted' => isset($this->deleted_at),
        ];
    }
}
