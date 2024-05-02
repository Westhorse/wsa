<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TimeSlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'time_from' => Carbon::parse($this->time_from)->format('H:i'),
            'time_to' => Carbon::parse($this->time_to)->format('H:i'),
            'active' => $this->active,
            'default_status' => $this->default_status,
            'note' => $this->note,
            'day_id' => $this->day_id,
            'day' => new EventDayResource($this->day),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
