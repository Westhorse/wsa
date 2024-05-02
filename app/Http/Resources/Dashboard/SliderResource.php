<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            'name' => $this->name,
            'sub_title' => $this->sub_title,
            'des' => $this->des,
            'order_id' => $this->order_id,
            'network_id' => $this->network_id,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()),
            'active' => $this->active,
            'title' => $this->title,
            'bg_url' => $this->bg_url,
            'link_type' => $this->link_type,

            // Button One Data
            'button_one_active' => $this->button_one_active,
            'button_text_one' => $this->button_text_one,
            'button_style_one' => $this->button_style_one,
            'button_route_one' => $this->button_route_one,
            'button_icon_one' => $this->button_icon_one,
            'button_link_type_one' => $this->button_link_type_one,

            // Button Two Data
            'button_two_active' => $this->button_two_active,
            'button_text_two' => $this->button_text_two,
            'button_style_two' => $this->button_style_two,
            'button_route_two' => $this->button_route_two,
            'button_icon_two' => $this->button_icon_two,
            'button_link_type_two' => $this->button_link_type_two,

            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'deleted' => isset($this->deleted_at),


        ];
    }
}
