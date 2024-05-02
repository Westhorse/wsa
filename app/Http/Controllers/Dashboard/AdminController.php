<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\AdminRequest;
use App\Http\Resources\Dashboard\AdminResource;
use App\Interfaces\AdminRepositoryInterface;
use App\Models\Admin;
use App\Models\Network;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(AdminRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $admin = AdminResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'super_admin', 'email']
            ));
            return $admin->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(AdminRequest $request)
    {
        try {
            $networkId = Network::where('id', $request->header('X-Network-ID'))->value('id');
            $admin = $this->crudRepository->create($request->validated());
            DB::table('admins_roles')->insert(['admin_id' => $admin->id, 'role_id' => $request['role_id'], 'network_id' => $networkId]);
            return new AdminResource($admin);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Admin $admin)
    {
        try {
            $admin = new AdminResource($admin);
            return $admin->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(AdminRequest $request, Admin $admin)
    {
        try {
            $networkId = Network::where('id', $request->header('X-Network-ID'))->value('id');
            $this->crudRepository->update($request->validated(), $admin->id);
            activity()->performedOn($admin)->withProperties(['attributes' => $admin])->log('update');
            if (DB::table('admins_roles')->where('network_id', $networkId)->where('admin_id' , $admin->id)->first() == null) {
                DB::table('admins_roles')->insert(['admin_id' => $admin->id, 'role_id' => $request['role_id'], 'network_id' => $networkId]);
            } else {
                DB::table('admins_roles')->where('admin_id', $admin->id)->where('network_id',$networkId)->update(['role_id' => $request['role_id'], 'network_id' => $networkId]);
            }
            if ($request->password !== null) {
                DB::table('admins')->where('id', $admin->id)->update(['password' => Hash::make($request['password']),]);
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('admins', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 1 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Admin::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Admin::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Admin::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



}
