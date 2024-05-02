<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $networkSlug = getNetworkSlug();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'des' => $this->des,
            'parent_id' => $this->parent_id,
            'type' => $this->type,
            'active' => $this->active,
            'order_id' => $this->order_id,
            'button_text_one' => $this->button_text_one,
            'button_style_one' => $this->button_style_one,
            'button_route_one' => $this->button_route_one,
            'button_icon_one' => $this->button_icon_one,
            'button_link_type_one' => $this->button_link_type_one,
            'button_text_two' => $this->button_text_two,
            'button_style_two' => $this->button_style_two,
            'button_route_two' => $this->button_route_two,
            'button_icon_two' => $this->button_icon_two,
            'button_link_type_two' => $this->button_link_type_two,
            'button_one_active' => $this->button_one_active,
            'button_two_active' => $this->button_two_active,
            'children' => PageSectionResource::collection($this->children) ?? null,
            'image_url' => $this->getFirstMediaUrl($networkSlug),
            'image' => new MediaResource($this->getFirstMedia($networkSlug)),
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
