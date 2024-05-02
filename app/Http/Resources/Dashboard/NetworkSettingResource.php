<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Common\MediaResource;
use App\Models\Network;
use Illuminate\Http\Resources\Json\JsonResource;

class NetworkSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $networkSlug = Network::where('domain', $request->query('domain'))->value('slug');
        $networkId = Network::where('domain', $request->query('domain'))->value('id');
        $networks = $this->settings->where('pivot.network_id', $networkId);
        $settings = [];
        foreach ($networks as $network) {
            $value = $network->pivot->value ?? null;

            if ($this->type === 'uploader') {
                $value = $this->getFirstMediaUrl($networkSlug);
            } else {
                $value = isset($value) ?
                    ($this->type === 'select' ? (int) $value : ($this->type === 'boolean' ? ($value == 1 ? true : false) : $value))
                    : null;
            }

            $settings[] = [
                'id' => $network->id,
                'name' => $network->name,
                'value' => $value,
            ];
        }
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'order_id' => $this->order_id ?? null,
            'collection' => $this->collection ?? false,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()),
            'settings' => SettingValueResource::collection($networks),
        ];
    }
}
