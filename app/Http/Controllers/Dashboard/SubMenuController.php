<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\SubMenuRequest;
use App\Http\Resources\Dashboard\SubMenuResource;
use App\Interfaces\SubMenuRepositoryInterface;
use App\Models\SubMenu;
use Exception;
use Illuminate\Http\Request;

class SubMenuController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(SubMenuRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $sub_menu = SubMenuResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'order_id', 'active']
            ));
            return $sub_menu->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(SubMenu $sub_menu)
    {
        try {
            $sub_menu = new SubMenuResource($sub_menu);
            return $sub_menu->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(SubMenuRequest $request)
    {
        try {
            $sub_menu = $this->crudRepository->create($request->validated());
            return new SubMenuResource($sub_menu);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(SubMenuRequest $request, SubMenu $sub_menu)
    {
        try {
            $this->crudRepository->update($request->validated(), $sub_menu->id);
            activity()->performedOn($sub_menu)->withProperties(['attributes' => $sub_menu])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            $subMenu = SubMenu::find($id);
            $subMenu->delete();
            return  JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(SubMenu::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = SubMenu::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(SubMenu::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
