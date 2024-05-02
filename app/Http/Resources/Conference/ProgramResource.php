<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ProgramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'dress_code' => $this->dress_code,
            'description' => $this->description,
            'from' => $this->from ? Carbon::parse($this->from)->format('H:i') : null,
            'to' => $this->to ? Carbon::parse($this->to)->format('H:i') : null,
            'active' => (bool) $this->active,
            'day_id' => $this->day_id,
            'day' => new EventDayResource($this->eventDay),
        ];
    }
}
