<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingSectionEventResource extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $value = null;

        if ($this->type === 'uploader') {
            $value = new MediaResource($this->getFirstMedia());
        } elseif ($this->type === 'number') {
            $value = (float)$this->value;
        } elseif ($this->type === 'boolean') {
            $value = $this->value == 1;
        } elseif ($this->type === 'button') {
            $value = $this->button ?? (object)[
                'label' => null,
                'url' => null,
                'target' => null,
                'icon' => null,
                'style' => null,
            ];
        } elseif ($this->type === 'list') {
            $value = $this->items ?? [];
        } elseif ($this->type === 'datetime_range') {
            $value = json_decode($this->datetime_range)  ?? [];
        } else {
            $value = $this->value;
        }
        return [
            'id' => $this->id,
            'label' => $this->label,
            'placeholder' => $this->placeholder,
            'des' => $this->des,
            'name' => $this->name,
            'data' => $this->data,
            'type' => $this->type,
            'children' => SettingSectionEventResource::collection($this->children),
            'class' => $this->class,
            'rules' => $this->rules,
            'value' => $value,
            'parent_id' => $this->parent_id,
            'order_id' => $this->order_id ?? null,
        ];
    }
}
