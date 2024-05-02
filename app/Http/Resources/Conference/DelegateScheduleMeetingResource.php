<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Conference\DietaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DelegateScheduleMeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request): array
    {
        // Check if the authenticated user has a meeting with this delegate

        $hasSelfMeeting = DB::table('delegates_time_slots')
            ->where(function ($query) {
                $query->whereJsonContains('status_request->sender', Auth::id())
                    ->orWhereJsonContains('status_request->receiver', Auth::id());
            })
            ->where(function ($query) {
                $query->whereJsonContains('status_request->sender', $this->id)
                    ->orWhereJsonContains('status_request->receiver', $this->id);
            })->exists();
        $user_id = auth()->user()->user_id;
        $hasColleagueMeeting = DB::table('delegates_time_slots')
            ->where(function ($query) use ($user_id) {
                $query->where('status_request->sender', '!=', auth()->id())
                    ->whereIn('status_request->sender', function ($query) use ($user_id) {
                        $query->select('id')
                            ->from('delegates')
                            ->where('user_id', $user_id);
                    });
            })
            ->orWhere(function ($query) use ($user_id) {
                $query->where('status_request->receiver', '!=', auth()->id())
                    ->whereIn('status_request->receiver', function ($query) use ($user_id) {
                        $query->select('id')
                            ->from('delegates')
                            ->where('user_id', $user_id);
                    });
            })->where(function ($query) {
                $query->whereJsonContains('status_request->sender', $this->id)
                    ->orWhereJsonContains('status_request->receiver', $this->id);
            })
            ->pluck('delegate_id')
            ->contains($this->id);


        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'type' => $this->type,
            'job_title' => $this->job_title ?? null,
            'user_id' => $this->user_id ?? null,
            'image_url' => $this->getFirstMediaUrl(),
            'company' => new EventCompanySimpleResource($this->user),
            'hasSelfMeeting' => $hasSelfMeeting,
            'hasColleagueMeeting' => $hasColleagueMeeting,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'deleted' => isset($this->deleted_at),
            'days' => $this->days(),
        ];
    }

    private function days()
    {
        $delegateReservations = $this->timeSlots->groupBy('day_id');
        return $delegateReservations->map(function ($dayTimeSlots, $dayId) {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $dayTimeSlots->first()->day->date)->format('d F Y');
            return [
                'id' => $dayTimeSlots->first()->day->id,
                'name' => $dayTimeSlots->first()->day->name,
                'date' => $dayTimeSlots->first()->day->date,
                'date_formatted' => $formattedDate,
                'time_slots' => ScheduleMeetingResource::collection($dayTimeSlots),
            ];
        })->values()->all();
    }
}
