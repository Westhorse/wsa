<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\EventMenuRequest;
use App\Http\Resources\Conference\EventMenuResource;
use App\Interfaces\EventMenuRepositoryInterface;
use App\Models\EventMenu;
use Exception;
use Illuminate\Http\Request;

class EventMenuController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(EventMenuRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $eventMenuResources = EventMenuResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'link', 'icon', 'show_icon', 'active', 'order_id']
            ));
            return $eventMenuResources->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function indexPublic()
    {
        try {
            $EventMenu = EventMenu::get();
            $EventMenuPublic = EventMenuResource::collection($EventMenu);
            return $EventMenuPublic;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    /**
     * Show the specified resource.
     * @param EventMenu $event_menu
     * @return EventMenuResource|\Illuminate\Http\JsonResponse
     */
    public function show(EventMenu $event_menu)
    {
        try {
            $eventMenuResource = new EventMenuResource($event_menu);
            return $eventMenuResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    /**
     * Update the specified resource in storage.
     * @param EventMenuRequest $request
     * @param EventMenu $event_menu
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EventMenuRequest $request, EventMenu $event_menu)
    {
        try {
            $this->crudRepository->update($request->validated(), $event_menu->id);
            activity()->performedOn($event_menu)->withProperties(['attributes' => $event_menu])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('event_menus', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 222 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(EventMenu::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = EventMenu::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(EventMenu::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
