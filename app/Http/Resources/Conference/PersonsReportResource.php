<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonsReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
      */
      public function toArray($request): array
      {
          return [
              'id' => $this->id,
              'name' => $this->name,
              'type' => $this->type,
              'title' => $this->title,
              'job_title' => $this->job_title,
              'job_title' => $this->job_title,
              'company' => new EventMemberSimpleResource($this->user),
              'order_id' => $this->order_id,
              'orderStatus' => $this->order->status,
              'image_url' => $this->getFirstMediaUrl(),
          ];
      }
}
