<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventHelpCenterResource extends JsonResource
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
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'post_title' => $this->post_title,
            'type' => $this->type,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'slug' => $this->slug,
            'active' => $this->active,
            'order_id' => $this->order_id,
            'parent_id' => $this->parent_id,
            'list' => $this->list ?? [],
            'image_url' => $this->getFirstMediaUrl(),
            'image' => $this->getFirstMediaResource(),
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d M, Y - H:i A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'sent_since' => $this->created_at ? $this->created_at->diffForHumans() : null,
            'created_at' => $this->created_at ? $this->created_at->format('F d, Y - h:i A') : null,
        ];
    }
}
