<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\RoomRequest;
use App\Http\Resources\Conference\RoomResource;
use App\Interfaces\RoomRepositoryInterface;
use App\Models\Room;
use Exception;
use Illuminate\Http\Request;

class RoomController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(RoomRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {

                $roomResources = RoomResource::collection($this->crudRepository->all(
                    [],
                    [],
                    ['*']
                ));

            return $roomResources->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic(Request $request)
    {
        try {
            $Rooms = Room::where('active', 1)->get();
            $Room = RoomResource::collection($Rooms);
            return $Room->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(RoomRequest $request)
    {
        try {
            $model = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            if (request('gallery') !== null) {
                $this->crudRepository->AddMediaCollectionArray('gallery', $model, 'gallery');
            }
            return new RoomResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Room $room)
    {
        try {

                $roomResource = new RoomResource($room);

            return $roomResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(RoomRequest $request, Room $room): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $room->id);
            if (request('image') !== null) {
                $room = Room::find($room->id);
                $image = $this->crudRepository->AddMediaCollection('image', $room);
            }
            if (request('gallery') !== null) {
                $room = Room::find($room->id);
                $image = $this->crudRepository->AddMediaCollectionArray('gallery', $room, 'gallery');
            }
            activity()->performedOn($room)->withProperties(['attributes' => $room])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('rooms', $request['items']);
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
            $this->crudRepository->restoreItem(Room::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Room::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Room::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
