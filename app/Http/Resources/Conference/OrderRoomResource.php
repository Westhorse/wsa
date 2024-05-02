<?php

namespace App\Http\Resources\Conference;

use App\Models\Delegate;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderRoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $delegateIds = json_decode($this->pivot->delegate_id, true);
        $delegates = PersonRoomResource::collection(Delegate::whereIn('id', $delegateIds)->get());

        $orderComplete = false;
        $orderId = $this->pivot->order_id;
        $orderData = Order::find($orderId);
        if ($orderData->status === 'approved_online_payment' || $orderData->status === 'approved_bank_transfer') {
            $orderComplete = true;
        }
        return [
            'room' => [
                'id' => $this->id,
                'name' => $this->name,
                'order_id' => $this->order_id,
                'type' => $this->type,
                'image_url' => $this->getFirstMediaUrl(),
            ],

            'order_complete' => $orderComplete,
            'persons' => $delegates,
            'record_id' => $this->pivot->id,
            'bed_type' => $this->pivot->bed_type,
            'room_id' => $this->pivot->room_id,
            'total_price' => $this->pivot->total_price,
            'start_date' => $this->pivot->start_date,
            'end_date' => $this->pivot->end_date,
            'startDateFormatted' => $this->pivot->start_date ? Carbon::parse($this->pivot->start_date)->format('d M, Y') : null,
            'endDateFormatted' => $this->pivot->end_date ? Carbon::parse($this->pivot->end_date)->format('d M, Y') : null,

            'date' => [Carbon::parse($this->pivot->start_date)->format('d-m-Y'), Carbon::parse($this->pivot->end_date)->format('d-m-Y')],
        ];
    }
}
