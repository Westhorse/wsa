<?php

namespace App\Http\Resources\Dashboard;

use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class RoleAdminResource extends JsonResource
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
        $roleId =  DB::table('admins_roles')->where('admin_id',$this->id)->where('network_id',$networkId)->pluck('role_id')->first();
        $permissionId =  DB::table('permission_role')->where('role_id',$roleId)->select('permission_id')->get();
        return [
            'id'=>$this->id,
            'permissionresourse' => PermissionResource::collection(DB::table('permissions')->whereIn('id', $permissionId->pluck('permission_id'))->get()),
         ];
    }
}
