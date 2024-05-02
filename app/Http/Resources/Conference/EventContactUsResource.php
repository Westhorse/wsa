<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventContactUsResource extends JsonResource
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
            'email' => $this->email,
            'wsa_id' => $this->wsa_id,
            'phone' => $this->phone,
            'address' => $this->address,
            'subject' => $this->subject,
            'message' => $this->message,
            'company' => $this->company,
            'country_id' => $this->country_id,
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d M, Y - H:i A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'sent_since' => $this->created_at ? $this->created_at->diffForHumans() : null,
            'created_at' => $this->created_at ? $this->created_at->format('F d, Y - h:i A') : null,
        ];
    }
}


