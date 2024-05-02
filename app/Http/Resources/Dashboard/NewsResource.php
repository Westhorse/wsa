<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'slug' => $this->slug,
            'order_id' => $this->order_id,
            'featured' => $this->featured,
            'active' => $this->active,
            'short_des' => $this->short_des,
            'des' => $this->des ?? "",
            'publish_date' => $this->publish_date ? $this->publish_date->format('Y-m-d\TH:i') : null,
            'publish_date_formatted' => $this->publish_date ? $this->publish_date->format('d M, Y - H:i A') : null,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()) ,
            'deleted' => isset($this->deleted_at),
            'reading_time' =>$this->short_des,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d M, Y - H:i A') : null,
            'created_at' => $this->created_at ? $this->created_at->format('d M, Y - H:i A') : null,

        ];
    }
}
