<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Dashboard\UserSimpleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactPersonResource extends JsonResource
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
            'user_id' => $this->user_id,
            'title' => $this->title ?? null,
            'name' => $this->name,
            'email' => $this->email,
            'birth_date' => $this->birth_date ?? null,
            'phone' => $this->phone ?? null,
            'phone_key_id' => $this->phone_key_id ?? null,
            'phone_key' => $this->phoneKey->key ?? null,
            'cell' => $this->cell ?? null,
            'cell_key_id' => $this->cell_key_id ?? null,
            'cell_key' => $this->cellKey->key ?? null,
            'company' => new UserSimpleResource($this->user) ?? null,
            'job_title' => $this->job_title,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()),
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
