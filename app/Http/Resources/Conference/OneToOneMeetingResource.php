<?php

namespace App\Http\Resources\Conference;

use App\Models\Delegate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OneToOneMeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $statusRequest = $this->pivot->status_request ? json_decode($this->pivot->status_request, true) : null;
        $person = null;
        if ($statusRequest !== null) {
            foreach ($statusRequest as $key => $value) {
                if ($value === Auth::user()->id) {
                } else {
                    $secondPersonId = $value;
                    $person = Delegate::find($secondPersonId);
                }
            }
        }
        $personStatus = null;
        if ($this->pivot->delegate_request_id == Auth::user()->id) {
            $personStatus = 'sender';
        } elseif ($person == null) {
            $personStatus = 'null';
        } else {
            $personStatus = 'receiver';
        }
        $timeFromFormatted = Carbon::createFromFormat('H:i:s', $this->time_from)->format('h:i A');
        $timeToFormatted = Carbon::createFromFormat('H:i:s', $this->time_to)->format('h:i A');
        $status = DB::table('time_slots')->where('id', $this->pivot->time_slot_id)->value('default_status');
        $active = DB::table('time_slots')->where('id', $this->pivot->time_slot_id)->value('active');
        if ($active == 1) {
            $activeValue = true;
        }else{
            $activeValue = false;
        }
        $statusCase = $this->pivot->status !== "null" ? ($this->pivot->status == 'true' ? true : false) : ($status == 1 ? true : false);
        return [
            'id' => $this->pivot->id,
            'time_slot_id' => $this->pivot->time_slot_id,
            'active' => $activeValue ?? null,
            'status' => $statusCase ?? null,
            'note' => $this->note,
            'table_number' => $this->table_number,
            'zoom_link' => $this->zoom_link,
            'is_online' => $this->is_online,
            'day_id' => $this->day_id,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'time_from_formatted' => $timeFromFormatted ?? null,
            'time_to_formatted' => $timeToFormatted ?? null,
            'delegate_id' => $this->pivot->delegate_id,
            'delegate_request_id' => $this->pivot->delegate_request_id,
            'status_code' => $personStatus ?? null,
            'person' => new DelegateResource($person),
            'created_at' => $this->pivot->created_at,
            'updated_at' => $this->pivot->updated_at
        ];
    }
}
