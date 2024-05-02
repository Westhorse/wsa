<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\FileUploadAction;
use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Conference\DelegateMeetingResource;
use App\Http\Resources\Conference\DelegateScheduleMeetingResource;
use App\Http\Resources\Conference\OneToOneMeetingResource;
use App\Models\Delegate;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Log;

class MeetingController extends Controller
{

    /**
     * Retrieve the list of one-to-one meetings grouped by days.
     *
     * @param Delegate $delegate The delegate instance.
     * @return \Illuminate\Http\JsonResponse
     */
    public function oneToOneMeetingList(Delegate $delegate)
    {
        try {
            $authUser = Auth::user();
            $dataByDays = $authUser->timeSlots->groupBy('day_id');
            $responseData = $dataByDays->map(function ($dayTimeSlots) {
                $formattedDate = Carbon::createFromFormat('Y-m-d', $dayTimeSlots->first()->day->date)->format('d F Y');
                return [
                    'id' => $dayTimeSlots->first()->day->id,
                    'name' => $dayTimeSlots->first()->day->name,
                    'date' => $dayTimeSlots->first()->day->date,
                    'date_formatted' => $formattedDate,
                    'time_slots' => OneToOneMeetingResource::collection($dayTimeSlots),
                ];
            });
            return response()->json(['result' => "Success", 'data' => $responseData->values()->all(), 'status' => 200]);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function oneToOneSaveTableNumber(Request $request, $id)
    {
        try {
            if (!$request['is_online']) {
                DB::table('delegates_time_slots')->where('id', $id)->update([
                    'is_online' => $request['is_online'], 'table_number' => $request['table_number']
                ]);
            } else {
                DB::table('delegates_time_slots')->where('id', $id)->update([
                    'is_online' => $request['is_online'], 'zoom_link' => $request['zoom_link']
                ]);
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function scheduleMeetingList(Delegate $delegate)
    {
        try {
            $authUser = Auth::user();
            $permission = new DelegateScheduleMeetingResource($authUser);
            return $permission->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function allDelegateList(Request $request)
    {
        try {
            $loggedInUserId = Auth::user()->id;
            $searchBy = $request->input('searchBy', '');
            $value = $request->input('value', '');
            $countryId = $request->input('country_id', '');
            $type = $request->input('type');
            $excludeSameCountryId = $request->input('excludeSameCountryId', false);
            $sortBy = $request->input('sortBy', '');
            $page = $request->input('page', 1);
            $length = $request->input('length', 10);
            $query = Delegate::whereNotIn('id', [$loggedInUserId])->where('type', 'delegate');
            if ($searchBy !== null && $countryId !== null) {
                $delegates = $query->paginate($length);
            } else {
                $delegates = $query->where(function ($query) use ($searchBy, $value, $countryId, $type, $excludeSameCountryId, $sortBy) {
                    if ($searchBy == "company_name") {
                        $query->whereHas('user', function ($subQuery) use ($value) {
                            $subQuery->where('name', 'like', '%' . $value . '%');
                        });
                    }
                    if ($countryId !== null) {
                        $query->orWhereHas('user', function ($subQuery) use ($countryId) {
                            $subQuery->where('country_id', $countryId);
                        });
                    }
                    if ($searchBy == "delegate_name") {
                        $query->where('name', 'like', '%' . $value . '%');
                    }
                    if ($type !== null) {
                        $query->orWhereHas('user.conferences', function ($subQuery) use ($type) {
                            $subQuery->where('type', $type);
                        });
                    }
                    if ($excludeSameCountryId) {
                        $userId = Auth::user()->user_id;
                        $userCountryId = DB::table('users')->where('id', $userId)->value('country_id');
                        $query->whereDoesntHave('user', function ($subQuery) use ($userCountryId) {
                            $subQuery->where('country_id', $userCountryId);
                        });
                    }
                })->paginate($length);
            }
            if ($sortBy == "company") {
                $delegates = $delegates->sortBy('user_id');
            } elseif ($sortBy == "delegate") {
                $delegates = $delegates->sortBy('id');
            } elseif ($sortBy == "country") {
                $delegates = $delegates->sortBy(function ($delegate) {
                    return optional($delegate->user)->country_id;
                });
            }
            $formattedDelegates = DelegateMeetingResource::collection($delegates);
            return response()->json(['data' => $formattedDelegates, 'page' => $page, 'length' => $length,]);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    /**
     * Book a time slot for a one-to-one meeting.
     *
     * @param Request $request The HTTP request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookTimeSlot(Request $request)
    {
        if (Auth::check()) {
            $loggedInDelegate = Auth::user();

            $delegateId = $request->input('delegate_id');
            $timeSlotId = $request->input('time_slot_id');

            $existingRecord = DB::table('delegates_time_slots')
                ->where('delegate_id', $delegateId)
                ->where('time_slot_id', $timeSlotId)
                ->first();

            if ($existingRecord && $existingRecord->status != 'true' && $existingRecord->status != "null") {
                return response()->json(['result' => false, 'message' => "cannot_book_appointment_time_slot_unavailable.", 'status' => 401], 401);
            }

            DB::table('delegates_time_slots')
                ->where('delegate_id', $delegateId)
                ->where('time_slot_id', $timeSlotId)
                ->update([
                    'delegate_request_id' => $loggedInDelegate->id,
                    'status' => "true",
                    'status_request' => json_encode([
                        'sender' => $loggedInDelegate->id,
                        'receiver' => $delegateId
                    ])
                ]);

            DB::table('delegates_time_slots')
                ->where('delegate_id', $loggedInDelegate->id)
                ->where('time_slot_id', $timeSlotId)
                ->update([
                    'delegate_request_id' => $loggedInDelegate->id,
                    'status' => "true",
                    'status_request' => json_encode([
                        'sender' => $loggedInDelegate->id,
                        'receiver' => $delegateId
                    ])
                ]);

            try {
                // Queue the email sending process
                Queue::push(function () use ($loggedInDelegate, $delegateId, $timeSlotId) {
                    FileUploadAction::sendAppointmentEmails($loggedInDelegate, $delegateId, $timeSlotId);
                });
            } catch (Exception $e) {
                Log::error('Error sending appointment emails: ' . $e->getMessage(), ['context' => $e]);
                JsonResponse::respondError($e->getMessage());
            }
            return response()->json(['result' => "Success", 'message' => "appointment_booked_successfully.", 'status' => 200]);
        } else {
            return response()->json(['result' => false, 'message' => "please_log_in_to_book_appointments.", 'status' => 401], 401);
        }
    }

    /**
     * Change the status of a time slot.
     *
     * @param Request $request The HTTP request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeTimeSlotStatus(Request $request)
    {
        if (Auth::check()) {
            $delegateId = Auth::user()->id;
            $timeSlotId = $request->route('delegates_time_slots_id');
            $timeSlot = DB::table('delegates_time_slots')
                ->where('id', $timeSlotId)
                ->where('delegate_id', $delegateId)
                ->first();

            if ($timeSlot) {
                $newStatus = $request->input('status');
                if ($newStatus == 1) {
                    $newStatusData = 'true';
                } else {
                    $newStatusData = 'false';
                }
                DB::table('delegates_time_slots')
                    ->where('id', $timeSlotId)
                    ->update(['status' => $newStatusData]);
                return response()->json(['result' => "Success", 'message' => "Session status has been updated successfully.", 'status' => 200]);
            } else {
                return response()->json(['result' => false, 'message' => "you_do_not_have_permission_to_change_the_status_of_the_time_slot.", 'status' => 403], 403);
            }
        } else {
            return response()->json(['message' => 'please_log_in_to_cancel_the_reservation.'], 401);
        }
    }

    /**
     * Cancel a booking for a one-to-one meeting.
     *
     * @param Request $request The HTTP request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelBooking(Request $request)
    {
        if (Auth::check()) {
            $loggedInDelegate = Auth::user();
            $delegateId = $request->input('delegate_id');
            $timeSlotId = $request->input('time_slot_id');

            DB::table('delegates_time_slots')
                ->where('delegate_id', $delegateId)
                ->where('time_slot_id', $timeSlotId)
                ->update([
                    'delegate_request_id' => null,
                    'status' => 'true',
                    'status_request' => null
                ]);

            DB::table('delegates_time_slots')
                ->where('delegate_id', $loggedInDelegate->id)
                ->where('time_slot_id', $timeSlotId)
                ->update([
                    'delegate_request_id' => null,
                    'status' => 'true',
                    'status_request' => null
                ]);

            try {
                FileUploadAction::sendCancellationEmail($loggedInDelegate, $delegateId, $timeSlotId);
            } catch (Exception $e) {
                Log::error('error sending cancellation email: ' . $e->getMessage(), ['context' => $e]);
                JsonResponse::respondError($e->getMessage());
            }
            return response()->json(['result' => "Success", 'message' => "reservation_cancelled_successfully.", 'status' => 200]);
        } else {
            return response()->json(['result' => false, 'message' => "please_log_in_to_cancel_the_reservation.", 'status' => 401], 401);
        }
    }
}
