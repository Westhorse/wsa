<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Conference\DietaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DelegateDashboardOrderResource extends JsonResource
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
            'type' => $this->type,
            'title' => $this->title,
            'job_title' => $this->job_title,
            'email' => $this->email,
            'user_id' => $this->user_id,
            'tshirtSize' => $this->tshirt_size->name,
            'phone' => $this->phone,
            'cell' => $this->cell,
            'phone_key_id' => $this->phone_key_id,
            'cell_key_id' => $this->cell_key_id,
            'extra_dietaries' => $this->extra_dietaries,
            'dietaries' => DietaryResource::collection($this->dietaries) ?? [],
            'image_url' => $this->getFirstMediaUrl(),
            'bc_url' => $this->getFirstMediaUrl('bc'),
            'bc' => $this->getFirstMediaResource('bc'),
            'order_complete' => $orderComplete,
        ];
    }
}
