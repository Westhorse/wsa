<?php

namespace App\Http\Resources\Common;

use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncotermResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $network = Network::where('id', $request->header('X-Network-ID'))->pluck('slug');
        return [
            'id' => $this->id,
            'title' => $this->title ?? null,
            'code' => $this->code ?? null,
            'des' => $this->des ?? null,
            'active' => $this->active ?? false,
            'order_id' => $this->order_id ?? null,
            'image_url' => $this->getFirstMediaUrl($network[0]),
            'image' => new MediaResource($this->getFirstMedia($network[0])),
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
