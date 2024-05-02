<?php

namespace App\Http\Resources\Dashboard;

use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $networkId = Network::where('id', $request->header('X-Network-ID'))->value('id');
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'permissions' => PermissionAdminResource::collection($this->permissions) ?? [],
        ];
    }
}
