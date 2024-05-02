<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuPublicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $subMenus = $this->subMenus->sortBy('order_id');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'order_id' => $this->order_id,
            'active' => $this->active,
            'network_id' => $this->network_id,
            'subMenus' =>  SubMenuPublicResource::collection($subMenus)->whereNull('parent_id')->where('active', 1)->sortBy('order_id'),
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
