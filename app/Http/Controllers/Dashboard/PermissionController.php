<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\PermissionResource;
use App\Interfaces\PermissionRepositoryInterface;
use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected mixed $crudRepository;

    public function __construct(PermissionRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $permission = PermissionResource::collection($this->crudRepository->all()->whereNull('parent_id'));
            return $permission->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Permission $permission)
    {
        try {
            $permission = new PermissionResource($permission);
            return $permission->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }






}
