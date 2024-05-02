<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\FileUploadAction;
use App\Helpers\JsonResponse;
use App\Http\Resources\Conference\DelegateMeetingResource;
use App\Http\Resources\Conference\DelegateScheduleMeetingResource;
use App\Http\Resources\Conference\OneToOneMeetingResource;
use App\Mail\Event\EventCancelOneToOneMail;
use App\Mail\Event\EventReceiverOneToOneMail;
use App\Mail\Event\EventSenderOneToOneMail;
use App\Models\Delegate;
use App\Models\EmailTemplate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Log;
use TCPDF;

class OneToOneMeetingController extends Controller
{

    public function viewPdfOneToOneMeeting($day_id)
    {
        $delegatesWithReservations = DB::table('delegates_time_slots')
            ->select('delegate_id')
            ->distinct()
            ->get()
            ->pluck('delegate_id');

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Your Name');
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('One to One Meetings');
        $pdf->SetMargins(5, 0.2, 5);
        $pdf->SetAutoPageBreak(true, 0);

        foreach ($delegatesWithReservations as $delegateId) {
            $delegateDetailsMain = DB::table('delegates')
                ->join('users', 'delegates.user_id', '=', 'users.id')
                ->join('countries', 'users.country_id', '=', 'countries.id')
                ->where('delegates.id', $delegateId)
                ->select('delegates.name as delegate_name', 'users.name as user_name', 'countries.name as name_country')
                ->first();

            $reservations = DB::table('delegates_time_slots')
                ->join('time_slots', 'delegates_time_slots.time_slot_id', '=', 'time_slots.id')
                ->join('event_days', 'time_slots.day_id', '=', 'event_days.id')
                ->where('delegates_time_slots.delegate_id', $delegateId)
                ->where('event_days.id', '=', $day_id)
                ->select(
                    'time_slots.time_from',
                    'time_slots.time_to',
                    'delegates_time_slots.status',
                    'event_days.name as day_name',
                    'event_days.date as day_date',
                    'delegates_time_slots.status_request',
                    'delegates_time_slots.table_number',
                )
                ->get();

            if (!$reservations->isEmpty()) {
                $delegateName = $delegateDetailsMain->delegate_name;
                $userName = $delegateDetailsMain->user_name;
                $nameCountry = $delegateDetailsMain->name_country;

                $pdf->AddPage('P', 'A4');
                $pdf->SetFont('helvetica', '', 11);

                $html = '
                    <!DOCTYPE html>
                    <html>
                    <head>
                    <style>
                    table {
                        width: 100%;
                    }

                    td, th {
                        border: 1px solid black;
                        text-align: center;
                    }
                    </style>
                    </head>
                    <body>';

                $html .= '<div style="text-align: center;">';
                $html .= '<img src="https://dashboard.wsa-network.com/storage/media/2024/04/18/1075/Events-Logo-Istanbul.jpg" alt="Events Logo" style=" width: 250px; height: auto;">';
                $html .= '</div>';
                $html .= '<h1 style="text-align: center;">One To One Meeting</h1>';
                $html .= '<h1 style="text-align: center; text-decoration: underline;">' . $delegateName . '</h1>';
                $html .= '<h1 style="text-align: center; text-decoration: underline;">' . $userName . ' - ' . $nameCountry .  '</h1>';
                $html .= '<table>';
                $html .= '
                    <tr class="ahmed">
                        <th colspan="6" style="line-height: 15px;">' . date('d F Y', strtotime($reservations[0]->day_date)) . '</th>
                    </tr>
                    <tr>
                        <th colspan="2">Meeting Time</th>
                        <th rowspan="2">Delegate</th>
                        <th rowspan="2">Company</th>
                        <th rowspan="2">Country</th>
                        <th rowspan="2">Table</th>
                    </tr>
                    <tr>
                        <td>From</td>
                        <td>To</td>
                    </tr>';

                foreach ($reservations as $reservation) {
                    $statusRequest = json_decode($reservation->status_request);

                    if ($statusRequest) {
                        $senderId = DB::table('delegates')
                            ->where('id', $statusRequest->sender)
                            ->value('id');

                        $receiverId = DB::table('delegates')
                            ->where('id', $statusRequest->receiver)
                            ->value('id');

                        $meetingDelegateId = $senderId === $delegateId ? $receiverId : $senderId;

                        $delegateDetails = DB::table('delegates')
                            ->join('users', 'delegates.user_id', '=', 'users.id')
                            ->join('countries', 'users.country_id', '=', 'countries.id')
                            ->where('delegates.id', $meetingDelegateId)
                            ->select('delegates.name as delegate_name', 'delegates.job_title as job_title', 'users.name as user_name', 'countries.name as name_country')
                            ->first();

                        $html .= '<tr>';
                        $html .= '<td style="line-height: 19px;">' . date("h:i A", strtotime($reservation->time_from)) . '</td>';
                        $html .= '<td style="line-height: 19px;">' . date("h:i A", strtotime($reservation->time_to)) . '</td>';
                        $html .= '<td style="line-height: 19px;">' . $delegateDetails->delegate_name . ' - ' . $delegateDetails->job_title . '</td>';
                        $html .= '<td style="line-height: 19px;">' . $delegateDetails->user_name . '</td>';
                        $html .= '<td style="line-height: 19px;">' . $delegateDetails->name_country . '</td>';
                        $html .= '<td style="line-height: 19px;">' . $reservation->table_number . '</td>';
                        $html .= '</tr>';
                    }
                }
                $html .= '</table>';
                $html .= '</body></html>';

                $pdf->writeHTML($html, true, false, true, false, '');

                // Add separator line
                $pdf->SetY(-25);
                $pdf->SetFont('helvetica', '', 11);
                $pdf->Cell(0, 0, '--------------------------------------------', 0, false, 'C', 0, '', 0, false, 'T', 'M');

                // Add date
                $pdf->SetY(-20);
                $pdf->Cell(0, 10, 'World Shipping Alliance Elite Conference – One to One Meeting List – Last Modified at ' . date('d F Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            }
        }
        $pdf->Output('one_to_one_meetings.pdf', 'I');
    }



    public function downloadPdfOneToOneMeeting($day_id)
    {
        $delegatesWithReservations = DB::table('delegates_time_slots')
            ->select('delegate_id')
            ->distinct()
            ->get()
            ->pluck('delegate_id');

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Your Name');
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('One to One Meetings');
        $pdf->SetMargins(5, 0.2, 5);
        $pdf->SetAutoPageBreak(true, 0);

        foreach ($delegatesWithReservations as $delegateId) {
            $delegateDetailsMain = DB::table('delegates')
                ->join('users', 'delegates.user_id', '=', 'users.id')
                ->join('countries', 'users.country_id', '=', 'countries.id')
                ->where('delegates.id', $delegateId)
                ->select('delegates.name as delegate_name', 'users.name as user_name', 'countries.name as name_country')
                ->first();

            $reservations = DB::table('delegates_time_slots')
                ->join('time_slots', 'delegates_time_slots.time_slot_id', '=', 'time_slots.id')
                ->join('event_days', 'time_slots.day_id', '=', 'event_days.id')
                ->where('delegates_time_slots.delegate_id', $delegateId)
                ->where('event_days.id', '=', $day_id)
                ->select(
                    'time_slots.time_from',
                    'time_slots.time_to',
                    'delegates_time_slots.status',
                    'event_days.name as day_name',
                    'event_days.date as day_date',
                    'delegates_time_slots.status_request',
                    'delegates_time_slots.table_number',
                )
                ->get();

            if (!$reservations->isEmpty()) {
                $delegateName = $delegateDetailsMain->delegate_name;
                $userName = $delegateDetailsMain->user_name;
                $nameCountry = $delegateDetailsMain->name_country;

                $pdf->AddPage('P', 'A4');
                $pdf->SetFont('helvetica', '', 11);

                $html = '
                    <!DOCTYPE html>
                    <html>
                    <head>
                    <style>
                    table {
                        width: 100%;
                    }

                    td, th {
                        border: 1px solid black;
                        text-align: center;
                    }
                    </style>
                    </head>
                    <body>';

                $html .= '<div style="text-align: center;">';
                $html .= '<img src="https://dashboard.wsa-network.com/storage/media/2024/04/18/1075/Events-Logo-Istanbul.jpg" alt="Events Logo" style=" width: 250px; height: auto;">';
                $html .= '</div>';
                $html .= '<h1 style="text-align: center;">One To One Meeting</h1>';
                $html .= '<h1 style="text-align: center; text-decoration: underline;">' . $delegateName . '</h1>';
                $html .= '<h1 style="text-align: center; text-decoration: underline;">' . $userName . ' - ' . $nameCountry .  '</h1>';
                $html .= '<table>';
                $html .= '
                    <tr class="ahmed">
                        <th colspan="6" style="line-height: 15px;">' . date('d F Y', strtotime($reservations[0]->day_date)) . '</th>
                    </tr>
                    <tr>
                        <th colspan="2">Meeting Time</th>
                        <th rowspan="2">Delegate</th>
                        <th rowspan="2">Company</th>
                        <th rowspan="2">Country</th>
                        <th rowspan="2">Table</th>
                    </tr>
                    <tr>
                        <td>From</td>
                        <td>To</td>
                    </tr>';

                foreach ($reservations as $reservation) {
                    $statusRequest = json_decode($reservation->status_request);

                    if ($statusRequest) {
                        $senderId = DB::table('delegates')
                            ->where('id', $statusRequest->sender)
                            ->value('id');

                        $receiverId = DB::table('delegates')
                            ->where('id', $statusRequest->receiver)
                            ->value('id');

                        $meetingDelegateId = $senderId === $delegateId ? $receiverId : $senderId;

                        $delegateDetails = DB::table('delegates')
                            ->join('users', 'delegates.user_id', '=', 'users.id')
                            ->join('countries', 'users.country_id', '=', 'countries.id')
                            ->where('delegates.id', $meetingDelegateId)
                            ->select('delegates.name as delegate_name', 'delegates.job_title as job_title', 'users.name as user_name', 'countries.name as name_country')
                            ->first();

                        $html .= '<tr>';
                        $html .= '<td style="line-height: 19px;">' . date("h:i A", strtotime($reservation->time_from)) . '</td>';
                        $html .= '<td style="line-height: 19px;">' . date("h:i A", strtotime($reservation->time_to)) . '</td>';
                        $html .= '<td style="line-height: 19px;">' . $delegateDetails->delegate_name . ' - ' . $delegateDetails->job_title . '</td>';
                        $html .= '<td style="line-height: 19px;">' . $delegateDetails->user_name . '</td>';
                        $html .= '<td style="line-height: 19px;">' . $delegateDetails->name_country . '</td>';
                        $html .= '<td style="line-height: 19px;">' . $reservation->table_number . '</td>';
                        $html .= '</tr>';
                    }
                }
                $html .= '</table>';
                $html .= '</body></html>';

                $pdf->writeHTML($html, true, false, true, false, '');

                // Add separator line
                $pdf->SetY(-25);
                $pdf->SetFont('helvetica', '', 11);
                $pdf->Cell(0, 0, '--------------------------------------------', 0, false, 'C', 0, '', 0, false, 'T', 'M');

                // Add date
                $pdf->SetY(-20);
                $pdf->Cell(0, 10, 'World Shipping Alliance Elite Conference – One to One Meeting List – Last Modified at ' . date('d F Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            }
        }
        $pdf->Output('one_to_one_meetings.pdf', 'D');
    }



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
            return response()->json(['result' => "Success", 'data' => $responseData->values()->all(), 'status' => 200], 200);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function oneToOneSaveTableNumber(Request $request, $id)
    {
        try {
            if ($request['is_online'] == false) {
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
            $permission = new DelegateScheduleMeetingResource($delegate);
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
            $value = $request->input('value');
            $countryId = $request->input('country_id');
            $type = $request->input('type');
            $excludeSameCountryId = $request->input('excludeSameCountryId', false);
            $sortBy = $request->input('sortBy', '');
            $page = $request->input('page', 1);
            $length = $request->input('length', 10);
            $query = Delegate::whereNotIn('id', [$loggedInUserId])->where('type', 'delegate');
            $query->whereHas('order', function ($subQuery) {
                $subQuery->whereIn('status', ['approved_online_payment', 'approved_bank_transfer']);
            });
            if ($value !== null) {
                $query->where(function ($query) use ($searchBy, $value) {
                    if ($searchBy == "company_name") {
                        $query->whereHas('user', function ($subQuery) use ($value) {
                            $subQuery->where('name', 'like', '%' . $value . '%');
                        });
                    }
                    if ($searchBy == "delegate_name") {
                        $query->where('name', 'like', '%' . $value . '%');
                    }
                });
            }

            if ($countryId !== null) {
                $query->whereHas('user', function ($subQuery) use ($countryId) {
                    $subQuery->where('country_id', $countryId);
                });
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

            $userId = Auth::user()->user_id;
            $userIds = DB::table('users')->where('id', $userId)->value('id');
            $query->whereDoesntHave('user', function ($subQuery) use ($userIds) {
                $subQuery->where('id', $userIds);
            });

            $delegates = $query->paginate($length);

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

            return $formattedDelegates;
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }





    /**
     * Book a time slot for a one-to-one meeting.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     * @return string|\Illuminate\Http\JsonResponse
     */
    public function bookTimeSlot(Request $request)
    {
        try {
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
                ->where('time_slot_id', $timeSlotId)
                ->whereIn('delegate_id', [$delegateId, $loggedInDelegate->id])
                ->update([
                    'delegate_request_id' => $loggedInDelegate->id,
                    'status' => "true",
                    'status_request' => json_encode([
                        'sender' => $loggedInDelegate->id,
                        'receiver' => $delegateId
                    ])
                ]);



            try {
                $this->sendAppointmentEmails($loggedInDelegate, $delegateId, $timeSlotId);
                return response()->json(['result' => "Success", 'message' => "appointment_booked_successfully.", 'status' => 200], 200);
            } catch (\Exception $e) {
                \Log::error('error sending appointment email: ' . $e->getMessage(), ['context' => $e]);
                JsonResponse::respondError($e->getMessage());
            }
            return response()->json(['result' => "Success", 'message' => "appointment_booked_successfully.", 'status' => 200], 200);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function sendAppointmentEmails($loggedInDelegate, $delegateId, $timeSlotId)
    {
        //time
        $timeSlot = DB::table('time_slots')
            ->select(
                DB::raw("DATE_FORMAT(time_slots.time_from, '%h:%i %p') as time_from"),
                DB::raw("DATE_FORMAT(time_slots.time_to, '%h:%i %p') as time_to"),
                DB::raw("DATE_FORMAT(event_days.date, '%d %b %Y') as formatted_date")
            )
            ->join('event_days', 'time_slots.day_id', '=', 'event_days.id')
            ->where('time_slots.id', request('time_slot_id'))
            ->first();
        $time_from = $timeSlot->time_from;
        $time_to = $timeSlot->time_to;
        $day_date =  $timeSlot->formatted_date ?? null;
        //receiver
        $receiver = Delegate::with(['user.country'])
            ->where('id', request('delegate_id'))
            ->first();
        // Sender
        $sender = Delegate::with(['user.country'])
            ->where('id', $loggedInDelegate->id)
            ->first();
        if ($receiver && $sender) {
            // Receiver Details
            $receiver_name = $receiver->name;
            $receiver_title = ucfirst($receiver->title);
            $receiver_email = $receiver->email;
            $receiver_job_title = $receiver->job_title;
            $receiver_company_country = $receiver->user->country->name ?? null;
            $receiver_company_city = $receiver->user->city ?? null;
            $receiver_company_name = $receiver->user->name ?? null;
            // Sender Details
            $sender_name = $sender->name;
            $sender_title = ucfirst($sender->title);
            $sender_email = $sender->email;
            $sender_job_title = $sender->job_title;
            $sender_company_country = $sender->user->country->name ?? null;
            $sender_company_city = $sender->user->city ?? null;
            $sender_company_name = $sender->user->name ?? null;
        }
        // email
        $templates = EmailTemplate::whereIn('slug', [
            'one_to_one_receiver_request_email_template',
            'one_to_one_sender_request_email_template'
        ])->get()->keyBy('slug');
        $subjectReceiver = $templates['one_to_one_receiver_request_email_template']->subject ?? null;
        $subjectReceiver = str_replace('{{sender_name}}', ucwords(strtolower($sender_name)), $subjectReceiver);

        $templateReceiver = $templates['one_to_one_receiver_request_email_template']->body ?? null;

        $subjectSender = $templates['one_to_one_sender_request_email_template']->subject ?? null;
        $subjectSender = str_replace('{{receiver_name}}', ucwords(strtolower($receiver_name)), $subjectSender);

        $templateSender = $templates['one_to_one_sender_request_email_template']->body ?? null;
        $login_button = '<a href="https://wsa-events.com/login" class="es-button" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#FFFFFF;font-size:18px;border-style:solid;border-color:#31CB4B;border-width:10px 20px 10px 20px;display:inline-block;background:#31CB4B;border-radius:5px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center">LOGIN HERE</a>';

        $templateReceiver = str_replace(
            [
                '{{time_from}}',
                '{{time_to}}',
                '{{day_date}}',

                '{{receiver_name}}',
                '{{receiver_title}}',

                '{{sender_name}}',
                '{{sender_title}}',
                '{{sender_job_title}}',

                '{{sender_company_name}}',
                '{{sender_company_city}}',
                '{{sender_company_country}}',
                '{{login_button}}'
            ],
            [
                $time_from,
                $time_to,
                $day_date,

                $receiver_name,
                $receiver_title,

                $sender_name,
                $sender_title,
                $sender_job_title,

                $sender_company_name,
                $sender_company_city,
                $sender_company_country,
                $login_button
            ],
            $templateReceiver
        );

        $templateSender = str_replace(
            [

                '{{sender_name}}',
                '{{sender_title}}',
                '{{time_from}}',
                '{{time_to}}',
                '{{day_date}}',

                '{{receiver_name}}',
                '{{receiver_title}}',
                '{{receiver_job_title}}',
                '{{receiver_company_name}}',
                '{{receiver_company_city}}',
                '{{receiver_company_country}}',

                '{{login_button}}'
            ],
            [
                $sender_name,
                $sender_title,

                $time_from,
                $time_to,
                $day_date,

                $receiver_name,
                $receiver_title,
                $receiver_job_title,
                $receiver_company_name,
                $receiver_company_city,
                $receiver_company_country,

                $login_button
            ],
            $templateSender
        );
        Mail::to($receiver_email)->queue(new EventReceiverOneToOneMail($templateReceiver, $subjectReceiver ));
        Mail::to($sender_email)->queue(new EventSenderOneToOneMail($templateSender, $subjectSender));
    }

    /**
     * Change the status of a time slot.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
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
                return response()->json(['result' => "Success", 'message' => "Session status has been updated successfully.", 'status' => 200], 200);
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
     * @param \Illuminate\Http\Request $request The HTTP request.
     * @return string|\Illuminate\Http\JsonResponse
     */
    public function cancelBooking(Request $request)
    {
        try {
            $loggedInDelegate = Auth::user();
            $delegateId = $request->input('delegate_id');
            $timeSlotId = $request->input('time_slot_id');
            DB::table('delegates_time_slots')
                ->whereIn('delegate_id', [$delegateId, $loggedInDelegate->id])
                ->where('time_slot_id', $timeSlotId)
                ->update([
                    'delegate_request_id' => null,
                    'status' => 'true',
                    'status_request' => null
                ]);
            try {
                $this->sendCancellationEmail($loggedInDelegate, $delegateId, $timeSlotId);
            } catch (\Exception $e) {
                \Log::error('error sending cancellation email: ' . $e->getMessage(), ['context' => $e]);
                JsonResponse::respondError($e->getMessage());
            }
            return response()->json(['result' => "Success", 'message' => "Meeting has been canceled.", 'status' => 200], 200);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function sendCancellationEmail($loggedInDelegate, $delegateId, $timeSlotId)
    {
        $delegate = Delegate::select('name', 'email')->find($delegateId);
        $delegate_name = $delegate->name ?? null;
        $receiver_email = $delegate->email ?? null;
        $sender_name = $loggedInDelegate->name;
        $sender_email = $loggedInDelegate->email;

        $timeSlot = DB::table('time_slots')
            ->select(
                DB::raw("DATE_FORMAT(time_slots.time_from, '%h:%i %p') as time_from"),
                DB::raw("DATE_FORMAT(time_slots.time_to, '%h:%i %p') as time_to"),
                DB::raw("DATE_FORMAT(event_days.date, '%d %b %Y') as formatted_date")
            )
            ->join('event_days', 'time_slots.day_id', '=', 'event_days.id')
            ->where('time_slots.id', request('time_slot_id'))
            ->first();
        $time_from = $timeSlot->time_from ?? null;
        $time_to = $timeSlot->time_to ?? null;
        $day_date = $timeSlot->formatted_date ?? null;
        $login_button = '<a href="https://wsa-events.com/login" class="es-button" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#FFFFFF;font-size:18px;border-style:solid;border-color:#31CB4B;border-width:10px 20px 10px 20px;display:inline-block;background:#31CB4B;border-radius:5px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center">LOGIN HERE</a>';

        $emailTemplate = EmailTemplate::where('slug', 'one_to_one_cancel_request_email_template')->first();
        $subject = $emailTemplate->subject;
        $template = $emailTemplate->body;
        $template = str_replace(
            [
                '{{delegate_name}}',
                '{{sender_name}}',
                '{{sender_email}}',
                '{{time_from}}',
                '{{time_to}}',
                '{{day_date}}',
                '{{login_button}}'
            ],
            [
                $delegate_name,
                $sender_name,
                $sender_email,
                $time_from,
                $time_to,
                $day_date,
                $login_button
            ],
            $template
        );
        Mail::to($receiver_email)->queue(new EventCancelOneToOneMail($template, $subject));
    }
}
