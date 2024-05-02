<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $networkSlug = getNetworkSlug();
//        dd($networkSlug);
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'type' => $this->type ?? '',
            'detail' => $this->detail ?? '',
            'active' => $this->active ?? '',
            'order_id' => $this->order_id,
            'benefit_id' => $this->benefit_id ?? '',
            'event_id' => $this->event_id ?? '',
            'items' => $this->children ?? [],
            'image_url' => $this->getFirstMediaUrl($networkSlug),
            'image' => new MediaResource($this->getFirstMedia($networkSlug)),
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
