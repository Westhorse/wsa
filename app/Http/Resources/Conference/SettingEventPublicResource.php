<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingEventPublicResource extends JsonResource
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
            $value = $this->getFirstMediaUrl();
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
            $value = json_decode($this->datetime_range) ?? [];
        } else {
            $value = $this->value;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $value,
        ];
    }
}
