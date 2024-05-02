<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Conference\EventDayResource;
use App\Interfaces\EventDayRepositoryInterface;
use App\Models\EventDay;
use Exception;
use Illuminate\Http\Request;

class EventDayController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(EventDayRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            return EventDayResource::collection($this->crudRepository->allEvent(
                ['conference','programs','timeSlots'],
                [],
                ['id','name','active','date']
            ));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic(Request $request)
    {
        try {
            $conferenceId = $request->header('X-Conference-Id');
            $EventDayResource = EventDay::where('active', 1)->where('conference_id',$conferenceId)->get();
            $EventDay = EventDayResource::collection($EventDayResource);
            return $EventDay->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    /**
     * Show the specified resource.
     * @param EventDay $event_day
     * @return EventDayResource|\Illuminate\Http\JsonResponse
     */
    public function show(EventDay $event_day)
    {
        try {
            $eventDayResource = new EventDayResource($event_day);
            return $eventDayResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param EventDay $event_day
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, EventDay $event_day)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'nullable|string',
            ]);
            $event_day->update($validatedData);
            activity()->performedOn($event_day)->withProperties(['attributes' => $event_day])->log('update');

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
            $count = $this->crudRepository->deleteRecords('event_days', $request['items']);
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
            $this->crudRepository->restoreItem(EventDay::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = EventDay::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(EventDay::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
