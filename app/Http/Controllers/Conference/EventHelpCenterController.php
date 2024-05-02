<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\EventHelpCenterRequest;
use App\Http\Resources\Conference\ConferenceResource;
use App\Http\Resources\Conference\EventHelpCenterResource;
use App\Interfaces\EventHelpCenterRepositoryInterface;
use App\Models\EventHelpCenter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class EventHelpCenterController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(EventHelpCenterRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $eventHelpCenters = EventHelpCenterResource::collection($this->crudRepository->all(['eventHelpCenter'], [], ['id','title','active','order_id']));
            return $eventHelpCenters->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic(Request $request)
    {
        try {
            $eventHelpCenters = EventHelpCenter::get();
            $eventHelpCenter = EventHelpCenterResource::collection($eventHelpCenters);
            return $eventHelpCenter->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(EventHelpCenterRequest $request)
    {
        try {
            $data = $request->validated();
            $slugOptions = getSlugOptions();
            $data['slug'] = isset($data['title']) ? Str::slug($data['title'], $slugOptions->slugSeparator, $slugOptions->slugLanguage) : '';
            $eventHelpCenter = $this->crudRepository->create($data);

            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $eventHelpCenter);
            }

            return new EventHelpCenterResource($eventHelpCenter);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    /**
     * Show the specified resource.
     * @param EventHelpCenter $eventHelpCenter
     * @return ConferenceResource|\Illuminate\Http\JsonResponse
     */
    public function show(EventHelpCenter $eventHelpCenter)
    {
        try {
            $cacheKey = 'eventHelpCenter_' . $eventHelpCenter->id;
            if (cache()->has($cacheKey)) {
                $cachedEventHelpCenter = cache($cacheKey);
            } else {
                $cachedEventHelpCenter = new EventHelpCenterResource($eventHelpCenter);
                cache([$cacheKey => $eventHelpCenter], 5);
            }
            return $cachedEventHelpCenter->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param EventHelpCenterRequest $request
     * @param EventHelpCenter $eventHelpCenter
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EventHelpCenterRequest $request, EventHelpCenter $eventHelpCenter)
    {
        try {
            $this->crudRepository->update($request->validated(), $eventHelpCenter->id);
            if (request('image') !== null) {
                $eventHelpCenter = EventHelpCenter::find($eventHelpCenter->id);
                $image = $this->crudRepository->AddMediaCollection('image', $eventHelpCenter);
            }
            activity()->performedOn($eventHelpCenter)->withProperties(['attributes' => $eventHelpCenter])->log('update');
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
            $count = $this->crudRepository->deleteRecords('event_help_centers', $request['items']);
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
            $this->crudRepository->restoreItem(EventHelpCenter::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = EventHelpCenter::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(EventHelpCenter::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
