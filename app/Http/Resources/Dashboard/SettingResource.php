<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Common\MediaResource;
use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $networkSlug = Network::where('id', $request->header('X-Network-ID'))->value('slug');
        $networkId = $request->header('X-Network-ID');
        $networks = $this->networks->where('pivot.network_id', $networkId)->map->pivot->first();
        $networkValue = $networks ? $networks->value : null;
        return [
                'id' => $this->id,
                'label' => $this->label,
                'placeholder' => $this->placeholder,
                'des' => $this->des,
                'name' => $this->name,
                'data' => $this->data,
                'type' => $this->type,
                'children' => SettingResource::collection($this->children),
                'class' => $this->class,
                'rules' => $this->rules,
                'value' => $networkValue ? ($this->type === 'select') ? (int) $networkValue : (($this->type === 'boolean') ? ($networkValue == 1 ? true : false) :
                    $networkValue) : null,
                'parent_id' => $this->parent_id,
                'image_url' => $this->getFirstMediaUrl($networkSlug),
                'image' => new MediaResource($this->getFirstMedia($networkSlug)),
        ];
    }
}
