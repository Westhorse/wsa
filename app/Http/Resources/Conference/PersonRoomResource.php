<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonRoomResource extends JsonResource
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
            'type' => $this->type,
            'title' => $this->title,
            'job_title' => $this->job_title ?? null,
            'user_id' => $this->user_id ?? null,
            'order_id' => $this->order_id,
            'image_url' => $this->getFirstMediaUrl(),
        ];
    }
}
