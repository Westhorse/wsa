<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Common\CountryResource;
use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'title'=>$this->title,
            'company'=>$this->company,
            'country_id'=>$this->country_id,
            'country' => new CountryResource($this->country),
            'des'=>$this->des,
            'short_des'=>$this->short_des,
            'active'=>$this->active,
            'show_home'=>$this->show_home,
            'order_id'=>$this->order_id,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()) ,
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
