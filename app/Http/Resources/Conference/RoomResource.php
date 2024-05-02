<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {

        $orderRoomCount = DB::table('orders_rooms')
        ->join('orders', 'orders.id', '=', 'orders_rooms.order_id')
        ->whereIn('orders.status', ['approved_bank_transfer', 'approved_online_payment'])
        ->where('orders_rooms.room_id', $this->id)
        ->count();

        $available_count = $this->count - $orderRoomCount;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'public_types' => $this->public_types ?? [],
            'features' => $this->features ?? [],
            'delegates_count' => $this->delegates_count,
            'description' => $this->description,
            'count' => $this->count,
            'available' => $available_count > 0,
            'available_count' =>  $available_count ,
            'price' => $this->price,
            'public_show' => $this->public_show,
            'active' => $this->active,
            'order_id' => $this->order_id,
            'type' => $this->type,
            'gallery' => $this->getMediaResource('gallery'),
            'image_url' => $this->getFirstMediaUrl(),
            'image' => $this->getFirstMediaResource(),
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d M, Y - H:i A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'created_at' => $this->created_at ? $this->created_at->format('F d, Y - h:i A') : null,
        ];
    }
}
