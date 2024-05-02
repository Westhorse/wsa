<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class NetworkStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $expireDate = $this->whenPivotLoaded('users_networks', function () {
            return $this->pivot->expire_date ? Carbon::parse($this->pivot->expire_date) : null;
        });

        $expireSoon = $expireDate && $expireDate->diffInDays(now()) <= 30;
        $isExpired = $expireDate && $expireDate < now();

        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'slug' => $this->slug ?? null,
            'domain' => $this->domain ?? null,
            'collection' => $this->collection ?? false,
            'order_id' => $this->order_id ?? null,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()),
            'fpp' => $this->whenPivotLoaded('users_networks', function () {
                return (bool)$this->pivot->fpp;
            }),
            'network' => $this->whenPivotLoaded('users_networks', function () {
                return (bool)$this->pivot->network;
            }),
            'active' => $this->whenPivotLoaded('users_networks', function () {
                return (bool)$this->pivot->active;
            }),
            'status' => $this->whenPivotLoaded('users_networks', function () {
                return $this->pivot->status;
            }),
            'type' => $this->whenPivotLoaded('users_networks', function () {
                return $this->pivot->type;
            }),
            'start_date' => $this->whenPivotLoaded('users_networks', function () {
                return $this->pivot->start_date ?: null;
            }),
            'expire_date' => $this->whenPivotLoaded('users_networks', function () {
                return $this->pivot->expire_date ?: null;
            }),
            'created_at' => $this->whenPivotLoaded('users_networks', function () {
                return $this->pivot->created_at ? Carbon::parse($this->pivot->created_at)->format('d F, Y') : null;
            }),
            'start_date_formatted' => $this->whenPivotLoaded('users_networks', function () {
                return $this->pivot->start_date ? Carbon::parse($this->pivot->start_date)->format('d F, Y') : null;
            }),
            'expire_date_formatted' => $this->whenPivotLoaded('users_networks', function () {
                return $this->pivot->expire_date ? Carbon::parse($this->pivot->expire_date)->format('d F, Y') : null;
            }),
            'created_at_formatted' => $this->whenPivotLoaded('users_networks', function () {
                return $this->pivot->created_at ? Carbon::parse($this->pivot->created_at)->format('d F, Y') : null;
            }),
            'created_since' => $this->whenPivotLoaded('users_networks', function () {
                return $this->pivot->created_at ? Carbon::parse($this->pivot->created_at)->diffForHumans() : null;
            }),
            'expire_days_left' => $this->whenPivotLoaded('users_networks', function () {
                return $this->pivot->expire_date ? dateCounter($this->pivot->expire_date) : null;
            }),
            'expire_soon' => $expireSoon,
            'expired' => $isExpired,
        ];
    }
}
