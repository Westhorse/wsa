<?php

namespace App\Http\Resources\Conference;

use App\Http\Resources\Common\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {

        $packageCount = DB::table('orders')
        ->whereIn('status', ['approved_bank_transfer', 'approved_online_payment'])
        ->where('package_id', $this->id)
        ->count();

        $available_count =  $this->count - $packageCount;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'count' => $this->count,
            'available' => $available_count > 0,
            'available_count' => $available_count,
            'active' => $this->active,
            'price' => $this->price,
            'features' => $this->features ?? [],
            'is_infinity' => $this->is_infinity,
            'order_id' => $this->order_id,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'delegate_count' => $this->delegate_count,
            'earlybird_price' => $this->earlybird_price,
            'style' => $this->style,
            'image_url' => $this->getFirstMediaUrl(),
            'image' => new MediaResource($this->getFirstMedia()),
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'deleted' => isset($this->deleted_at),
        ];
    }
}
