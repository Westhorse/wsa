<?php

namespace App\Http\Resources\Conference;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicEventDayWithProgramsResource extends JsonResource
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
            'active' => (bool) $this->active,
            'date' => $this->date ? Carbon::parse($this->date)->format('d F, Y') : null,
            'programs' => ProgramResource::collection($this->programs) ?? []
        ];
    }
}
