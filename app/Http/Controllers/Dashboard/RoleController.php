<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\RoleRequest;
use App\Http\Resources\Dashboard\RoleAdminResource;
use App\Http\Resources\Dashboard\RoleResource;
use App\Interfaces\RoleRepositoryInterface;
use App\Models\Admin;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(RoleRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $role = RoleResource::collection($this->crudRepository->all(
                ['permissions'],
                [],
                ['id', 'name']
            ));
            return $role->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Role $role)
    {
        try {
            $role = new RoleResource($role);
            return $role->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(RoleRequest $request)
    {
        try {
            $model = $this->crudRepository->create($request->except('permissions'));
            $model->permissions()->sync($request->permissions);

            $models   = new RoleResource($model);
            return $models;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(RoleRequest $request, Role $role)
    {
        try {
            $data = $request->only('name');
            $this->crudRepository->update($data, $role->id);

            if ($request->has('permissions')) {
                $permissionsId = customSyncItem($request->permissions, 'permission_id');
                $role->permissions()->sync($permissionsId);
            } else {
                $role->permissions()->detach();
            }

            $datad = new RoleResource($role->refresh());
            activity()->performedOn($role)->withProperties(['attributes' => $role])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY), null);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('roles', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans("responses.msg_multi_resources_cannot_deleted"))
                : ($count == 222 ? JsonResponse::respondError(trans("responses.msg_cannot_deleted"))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        try {
            $this->crudRepository->restoreItem(Role::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Role::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Role::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function adminPermissions($id)
    {
        try {
            $admins = Admin::find($id);
            return new RoleAdminResource($admins);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
