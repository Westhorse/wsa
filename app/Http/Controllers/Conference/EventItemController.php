<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\EventItemRequest;
use App\Http\Resources\Conference\EventItemResource;
use App\Interfaces\EventItemRepositoryInterface;
use App\Models\EventItem;
use Exception;
use Illuminate\Http\Request;

class EventItemController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(EventItemRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $eventItemResources = EventItemResource::collection($this->crudRepository->all(
                [],
                [],
                ['*']
            ));
            return $eventItemResources->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic(Request $request)
    {
        try {
            $EventItemResource = EventItem::where('active', 1)->get();
            $EventItem = EventItemResource::collection($EventItemResource);
            return $EventItem->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(EventItemRequest $request)
    {
        try {
            $model = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            return new EventItemResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(EventItem $event_item)
    {
        try {
            $eventItemResource = new EventItemResource($event_item);
            return $eventItemResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(EventItemRequest $request, EventItem $event_item): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $event_item->id);
            if (request('image') !== null) {
                $eventPage = EventItem::find($event_item->id);
                $image = $this->crudRepository->AddMediaCollection('image', $eventPage);
            }
            activity()->performedOn($event_item)->withProperties(['attributes' => $event_item])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('event_items', $request['items']);
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
            $this->crudRepository->restoreItem(EventItem::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = EventItem::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(EventItem::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
