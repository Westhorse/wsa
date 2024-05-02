<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\MenuRequest;
use App\Http\Resources\Dashboard\MenuPublicResource;
use App\Http\Resources\Dashboard\MenuResource;
use App\Interfaces\MenuRepositoryInterface;
use App\Models\Menu;
use App\Models\SubMenu;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(MenuRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $menu = MenuResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'order_id', 'active','network_id'],
                true, false
            ));
            return $menu->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Menu $menu)
    {
        try {
            $menu = new MenuResource($menu);
            return $menu->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function showPublic(Menu $menu)
    {
        try {
            $menu = new MenuPublicResource($menu);
            return $menu->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(MenuRequest $request)
    {
        try {
            $Menu = $this->crudRepository->create($request->validated());
            DB::table('menus')->where('id', $Menu->id)->update(['network_id' => $request->header('X-Network-ID')]);
            return new MenuResource($Menu);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        try {
            $this->crudRepository->update($request->validated(), $menu->id);
            activity()->performedOn($menu)->withProperties(['attributes' => $menu])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('menus', $request['items']);
            SubMenu::whereIn('menu_id', $request['items'])->update(['active' => false]);
            return $count > 122
                ? JsonResponse::respondError(trans("responses.msg_multi_resources_cannot_deleted"))
                : ($count == 122 ? JsonResponse::respondError(trans("responses.msg_cannot_deleted"))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Menu::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Menu::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Menu::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
