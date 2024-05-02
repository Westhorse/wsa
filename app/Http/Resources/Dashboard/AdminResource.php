<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Dashboard\AdminRoleResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {

        $networkId = $request->header('X-Network-ID');
        $roles = $this->roles->where('pivot.network_id', $networkId);
        $roleId = $roles->pluck('id')->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'super_admin' => $this->super_admin,
            'role_id' => $roleId,
            'role' => $roles->map(function ($role) {
                return new AdminRoleResource($role);
            })->first(),

        ];
    }
}
