<?php

namespace App\Http\Resources\Conference;

use App\Models\Delegate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleMeetingResource extends JsonResource
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
        $statusCase = $this->pivot->status !== "null" ? ($this->pivot->status == 'true' ? 'true' : 'false') : ($status == 1 ? true : false);


        $meetingWithAuth = DB::table('delegates_time_slots')
            ->where('time_slot_id', $this->pivot->time_slot_id)
            ->where('delegate_id', $request->route('delegate')->id)
            ->where(function ($query) {
                $query->whereJsonContains('status_request->sender', Auth::id())
                    ->orWhereJsonContains('status_request->receiver', Auth::id());
            })->exists();

        $appointmentStatus = null;
        $selectedDelegate = DB::table('delegates_time_slots')
            ->where('time_slot_id', $this->pivot->time_slot_id)
            ->where('delegate_id', $request->route('delegate')->id);

        $authDelegate = DB::table('delegates_time_slots')
            ->where('time_slot_id', $this->pivot->time_slot_id)
            ->where('delegate_id', Auth::user()->id);

        $statusAuth = $authDelegate->value('status');
        if ($statusAuth === "null") {
            $statusAuth = DB::table('time_slots')->where('id', $this->pivot->time_slot_id)->value('default_status');
        }

        $statusSelected = $selectedDelegate->value('status');
        if ($statusSelected === "null") {
            $statusSelected = DB::table('time_slots')->where('id', $this->pivot->time_slot_id)->value('default_status');
        }

        $statusCase = $this->pivot->status !== "null" ? ($this->pivot->status == 'true' ? true : false) : ($status == 1 ? true : false);



        $selectedDelegateStatusClosed = $selectedDelegate->value('status') == "false";



        if (($authDelegate->value('status') == "true" || $statusAuth === 1) && $authDelegate->value('status_request') == null) {

            if ($selectedDelegate->value('status_request') == null && ($selectedDelegate->value('status') == "true" || $statusSelected === 1)) {
                $appointmentStatus = 'available';
            } else if ($selectedDelegateStatusClosed || $statusSelected === 0) {
                $appointmentStatus = 'auth_available_delegate_closed';
            } else if ($selectedDelegate->value('status') == "true" && $selectedDelegate->value('status_request') !== null) {
                $appointmentStatus = 'auth_available_delegate_self_meeting';
            }
        } elseif ($authDelegate->value('status') == "false" || $statusAuth === 0) {

            if ($selectedDelegate->value('status_request') == null && ($selectedDelegate->value('status') == "true" || $statusSelected === 1)) {
                $appointmentStatus = 'auth_closed_delegate_available';
            } else if ($selectedDelegateStatusClosed || $statusSelected === 0) {
                $appointmentStatus = 'auth_closed_delegate_closed';
            } else if ($selectedDelegate->value('status') == "true" && $selectedDelegate->value('status_request') !== null) {
                $appointmentStatus = 'auth_closed_delegate_self_meeting';
            }
        } elseif ($authDelegate->value('status_request') !== null) {
            if ($selectedDelegate->value('status_request') == null && ($selectedDelegate->value('status') == "true" || $statusSelected === 1)) {
                $appointmentStatus = 'auth_self_meeting_delegate_available';
            } else if ($selectedDelegateStatusClosed || $statusSelected === 0) {
                $appointmentStatus = 'auth_self_meeting_delegate_closed';
            } else if ($selectedDelegate->value('status') == "true" && $selectedDelegate->value('status_request') !== null && $meetingWithAuth) {
                $appointmentStatus = 'auth_self_meeting_with_same_delegate';
            } else if ($selectedDelegate->value('status') == "true" && $selectedDelegate->value('status_request') !== null) {
                $appointmentStatus = 'auth_self_meeting_delegate_self_meeting';
            }
        } else {
            $appointmentStatus =  'not_defined';
        }




        $status = DB::table('time_slots')->where('id', $this->pivot->time_slot_id)->value('default_status');
        $active = DB::table('time_slots')->where('id', $this->pivot->time_slot_id)->value('active');
        if ($active == 1) {
            $activeValue = true;
        } else {
            $activeValue = false;
        }
        $statusCase = $this->pivot->status !== "null" ? ($this->pivot->status == 'true' ? true : false) : ($status == 1 ? true : false);
        return [
            'status_code' => $appointmentStatus,
            'id' => $this->id,
            'time_slot_id' => $this->pivot->time_slot_id,
            'active' => $activeValue ?? null,
            'status' => $statusCase ?? null,
            'note' => $this->note,
            'day_id' => $this->day_id,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'time_from_formatted' => $timeFromFormatted ?? null,
            'time_to_formatted' => $timeToFormatted ?? null,
            'delegate_id' => $this->pivot->delegate_id,
            'delegate_request_id' => $this->pivot->delegate_request_id,
            'person' => new DelegateResource($person),
            'created_at' => $this->pivot->created_at,
            'updated_at' => $this->pivot->updated_at
        ];
    }
}
