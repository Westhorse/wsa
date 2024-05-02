<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubMenuPublicResource extends JsonResource
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
            'link' => $this->link,
            'icon' => $this->icon,
            'parent_id' => $this->parent_id,
            'children' => $this->children->where('active', 1)->toArray(),
            'menu_id' => $this->menu_id,
            'order_id' => $this->order_id,
            'type' => $this->type,
            'active' => $this->active,
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
