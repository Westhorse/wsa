<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\TimeSlotRequest;
use App\Http\Resources\Conference\TimeSlotResource;
use App\Interfaces\TimeSlotRepositoryInterface;
use App\Models\Order;
use App\Models\TimeSlot;
use Exception;
use Illuminate\Http\Request;

class TimeSlotController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(TimeSlotRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $timeSlot = TimeSlotResource::collection($this->crudRepository->allEvent([], [], ['*']));
            return $timeSlot->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic(Request $request)
    {
        try {
            $conferenceId = $request->header('X-Conference-Id');
            $TimeSlots = TimeSlot::where('active',1)->where('conference_id',$conferenceId)->get();
            $TimeSlot = TimeSlotResource::collection($TimeSlots);
            return $TimeSlot->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(TimeSlotRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $time_from = new \DateTime($validatedData['time_from']);
            $time_to = new \DateTime($validatedData['time_to']);

            $timeSlots = [];

            for ($time = clone $time_from; $time < $time_to;) {
                $timeSlots[] = [
                    'time_from' => $time->format('H:i') ?? null,
                    'time_to' => $time->add(new \DateInterval('PT30M'))->format('H:i') ?? null,
                    'active' => $validatedData['active'] ?? null,
                    'conference_id' => $request->header('X-Conference-Id') ?? null,
                    'default_status' => $validatedData['default_status'] ?? null,
                    'note' => $validatedData['note'] ?? null,
                    'day_id' => $validatedData['day_id'] ?? null,
                ];
            }

            foreach ($timeSlots as $timeSlot) {
                $model = $this->crudRepository->create($timeSlot);
            }
            $orders = Order::whereIn('status', ['approved_online_payment', 'approved_bank_transfer'])->get();
            foreach ($orders as $order) {
                $user = $order->user;
                $delegates = $user->delegates;
                foreach ($delegates as $delegate) {
                    $timeSlots = TimeSlot::all();
                    $delegate->timeSlots()->sync($timeSlots);
                }
            }
            return new TimeSlotResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function show(TimeSlot $time_slot)
    {
        try {
            $cached = new TimeSlotResource($time_slot);
            return $cached->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(TimeSlotRequest $request, TimeSlot $time_slot)
    {
        try {
            $this->crudRepository->update($request->validated(), $time_slot->id);
            activity()->performedOn($time_slot)->withProperties(['attributes' => $time_slot])->log('update');
            $orders = Order::whereIn('status', ['approved_online_payment', 'approved_bank_transfer'])->get();
            foreach ($orders as $order) {
                $user = $order->user;
                $delegates = $user->delegates;
                foreach ($delegates as $delegate) {
                    $timeSlots = TimeSlot::all();
                    $delegate->timeSlots()->sync($timeSlots);
                }
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY), null);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('time_slots', $request['items']);
            TimeSlot::whereIn('id', $request['items'])->each(function ($timeSlot) {
                $timeSlot->delegates()->detach();
            });
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
            $this->crudRepository->restoreItem(TimeSlot::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = TimeSlot::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(TimeSlot::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
