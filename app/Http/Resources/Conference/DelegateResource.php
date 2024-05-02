<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Conference\DietaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DelegateResource extends JsonResource
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
            'unhashed_password' => $this->unhashed_password,
            'type' => $this->type,
            'title' => $this->title,
            'job_title' => $this->job_title,
            'email' => $this->email,
            'user_id' => $this->user_id,
            'company' => new EventCompanySimpleResource($this->user),
            'tshirt_size_id' => $this->tshirt_size_id,
            'tshirtSize' => $this->tshirt_size->name,
            'phone' => $this->phone,
            'cell' => $this->cell,
            'phone_key_id' => $this->phone_key_id,
            'cell_key_id' => $this->cell_key_id,
            'extra_dietaries' => $this->extra_dietaries,
            'order_id' => $this->order_id,
            'orderStatus' => $this->order->status,

            'dietaries' => DietaryResource::collection($this->dietaries) ?? null,

            'image_url' => $this->getFirstMediaUrl(),
            'image' => $this->getFirstMediaResource(),

            'bc_url' => $this->getFirstMediaUrl('bc'),
            'bc' => $this->getFirstMediaResource('bc'),

            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'deleted' => isset($this->deleted_at),
            'order_complete' => $orderComplete,

        ];
    }
}
