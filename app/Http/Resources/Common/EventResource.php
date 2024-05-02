<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $endDate = $this->end_date;

        if ($endDate && $endDate < now()) {
            $isEventOver = true;
        } else {
            $isEventOver = false;
        }

        return [
            'id' => $this->id,
            'title' => $this->title ?? '',
            'venue' => $this->venue ?? '',
            'slug' => $this->slug ?? '',
            'type' => $this->type ?? '',
            'des' => $this->des ?? '',
            'short_des' => $this->short_des ?? '',
            'url_text' => $this->url_text ?? '',
            'url_path' => $this->url_path ?? '',
            'country' => new CountryResource($this->country),

            'start_date_formatted' => $this->start_date ? $this->start_date->format('d, F Y') : null ?? '',
            'end_date_formatted' => $this->end_date ? $this->end_date->format('d, F Y') : null,

            'start_date' => $this->start_date ? $this->start_date->format('Y-m-d') : null,
            'end_date' => $this->end_date ? $this->end_date->format('Y-m-d') : null,

            'start_date_front' => $this->start_date ? $this->start_date->format('d,F,Y') : null,
            'end_date_front' => $this->end_date ? $this->end_date->format('d,F,Y') : null,

            'delegates' => $this->delegates ?? '',
            'sessions' => $this->sessions ?? '',
            'companies' => $this->companies ?? '',
            'countries' => $this->countries ?? '',
            'featured' => $this->featured ?? '',
            'active' => $this->active ?? false,
            'order_id' => $this->order_id ?? '',
            'country_id' => $this->country_id ?? '',
            'city' => $this->city ?? '',
            'duration' => $this->duration ?? '',

            'event_over' => $isEventOver,

            // Gallery Array
            'gallery' => $this->getMediaResource('gallery'),
            // Main Cover Image
            'image_url' => $this->getFirstMediaUrl('image'),
            'image' => $this->getFirstMediaResource('image'),


            // 'contents' => ContentResource::collection($this->contents) ?? "",
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
