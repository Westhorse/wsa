<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'placeholder' => $this->placeholder,
            'des' => $this->des,
            'name' => $this->name,
            'data' => $this->data,
            'type' => $this->type,
            'button' => $this->button ?? (object)[
                'label' => null,
                'url' => null,
                'target' => null,
                'icon' => null,
                'style' => null,
            ],
            'items' => $this->items ?? [],
            'children' => SettingEventResource::collection($this->children),
            'class' => $this->class,
            'rules' => $this->rules,
            'value' => $this->value,
            'parent_id' => $this->parent_id,
            'order_id' => $this->order_id ?? null,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()),
        ];
    }
}
