<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Common\MediaResource;
use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BenefitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $contents = $this->contents()->whereNull('parent_id')->orderBy('order_id', 'asc')->get();
        $network = Network::where('id', $request->header('X-Network-ID'))->pluck('slug');

        $networkId = $request->header('X-Network-ID');
        $networkBenefit = $this->networks->where('pivot.network_id', $networkId);
        $networkBenefitActive = $networkBenefit->first();
        $activeValue = $networkBenefitActive['pivot']['active']?? null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'short_des' => $this->short_des,
            'order_id' => $this->order_id,
            'networks' => $this->networks->map(function ($item) {
                return [
                    'network_id' =>  $item->pivot->network_id,
                    'active' =>  $item->pivot->active
                ];
            }),
            'active'=> $activeValue ?? '',
            'image_url' => $this->getFirstMediaUrl($network[0]),
            'image' => new MediaResource($this->getFirstMedia($network[0])) ,
            'contents' => ContentResource::collection($this->contents->sortBy('order_id'))->whereNull('parent_id')->values()->toArray(),
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
