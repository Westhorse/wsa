<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Conference\VisitRequest;
use App\Http\Resources\Common\CountryResource;
use App\Http\Resources\Conference\DelegateMeetingResource;
use App\Http\Resources\Conference\DietaryReportResource;
use App\Http\Resources\Conference\TshirtSizeReportResource;
use App\Models\Country;
use App\Models\Delegate;
use App\Models\Dietary;
use App\Models\EventDay;
use App\Models\Order;
use App\Models\Room;
use App\Models\TshirtSize;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function oneToOneReport(Delegate $delegate)
    {
        try {
            $conferenceId = request()->header('X-Conference-Id');
            $dataByDays = EventDay::where('conference_id', $conferenceId)->with('timeSlots.delegates')->get()->groupBy('id');
            $responseData = $dataByDays->map(function ($day) {
                return [
                    'id' => $day->first()->id,
                    'name' => $day->first()->name,
                    'conference_id' => $day->first()->conference_id,
                    'date' => Carbon::parse($day->first()->date)->format('F d, Y'),
                    'time_slots' => $day->flatMap(function ($day) {
                        $processedTimeSlots = [];
                        return $day->timeSlots->map(function ($timeSlot) use (&$processedTimeSlots) {
                            if (!in_array($timeSlot->id, $processedTimeSlots)) {
                                $processedTimeSlots[] = $timeSlot->id;
                                $delegateIds = collect();
                                $processedPairs = collect(); // مجموعة لتخزين الأزواج المعالجة
                                foreach ($timeSlot->delegates as $delegate) {
                                    // إذا وجدت زوجًا متكررًا من time_slot_id و delegate_request_id
                                    if (!$processedPairs->contains([$delegate->pivot->time_slot_id, $delegate->pivot->delegate_request_id, $delegate->pivot->status_request])) {
                                        $processedPairs->push([$delegate->pivot->time_slot_id, $delegate->pivot->delegate_request_id, $delegate->pivot->status_request]);
                                        if (!empty($delegate->pivot->status_request)) {
                                            $senderId = json_decode($delegate->pivot->status_request, true)['sender'];
                                            $senderDelegate = Delegate::find($senderId);
                                            $receiverId = json_decode($delegate->pivot->status_request, true)['receiver'];
                                            $receiverDelegate = Delegate::find($receiverId);
                                            $delegateIds->push([
                                                'id' => $delegate->pivot->id,
                                                'time_slot_id' => $delegate->pivot->time_slot_id,
                                                'table_number' => $delegate->pivot->table_number,
                                                'zoom_link' => $delegate->pivot->zoom_link,
                                                'is_online' => $delegate->pivot->is_online,
                                                'sender' => new DelegateMeetingResource($senderDelegate),
                                                'receiver' => new DelegateMeetingResource($receiverDelegate),
                                            ]);
                                        }
                                    }
                                }
                                return [
                                    'id' => $timeSlot->id,
                                    'from' => Carbon::parse($timeSlot->time_from)->format('h:i A'),
                                    'to' => Carbon::parse($timeSlot->time_to)->format('h:i A'),
                                    'note' => $timeSlot->note,
                                    'sessionsRequests' => $delegateIds->isNotEmpty() ? $delegateIds->toArray() : [],
                                ];
                            }
                        });
                    })
                ];
            });
            return response()->json(['result' => "Success", 'data' => $responseData->values()->all(), 'status' => 200], 200);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function roomReport()
    {
        try {
            $rooms = Room::with(['orders' => function ($query) {
                $query->selectRaw('orders_rooms.id AS order_id,orders_rooms.id, orders_rooms.start_date, orders_rooms.delegate_id, orders_rooms.end_date, orders_rooms.total_price, orders_rooms.bed_type');
            }])->get();

            $formattedRooms = $rooms->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'type' => $room->type,
                    'bookedRooms' => $room->orders->map(function ($order) {
                        $start = Carbon::parse($order->start_date);
                        $startFormatted = Carbon::parse($order->start_date)->format('F d, Y');
                        $end = Carbon::parse($order->end_date);
                        $endFormatted = Carbon::parse($order->end_date)->format('F d, Y');
                        $days = $end->diffInDays($start) + 1;
                        $nights = $end->diffInDays($start);
                        $delegateIds = json_decode($order->delegate_id, true);
                        $delegates = Delegate::whereIn('id', $delegateIds)->get();
                        $persons = DelegateMeetingResource::collection($delegates);

                        return [
                            'id' => $order->id,
                            'from' => $order->start_date,
                            'to' => $order->end_date,
                            'startFormatted' => $startFormatted,
                            'endFormatted' => $endFormatted,
                            'days' => $days,
                            'nights' => $nights,
                            'totalPrice' => $order->total_price,
                            'bedType' => $order->bed_type,
                            'persons' => $persons,
                        ];
                    }),
                ];
            });

            return response()->json(['result' => "Success", 'rooms' => $formattedRooms, 'status' => 200], 200);
        } catch (\Exception $e) {
            return response()->json(['result' => "Error", 'message' => $e->getMessage(), 'status' => 500], 500);
        }
    }


    // conferenceId
    public function dashboardReport()
    {
        try {
            $conferenceId = request()->header('X-Conference-Id');
            $approvedStatuses = ['approved_online_payment', 'approved_bank_transfer'];
            $totalAmount = Order::whereIn('status', $approvedStatuses)->sum('amount');
            $totalRegisteredCompanies = DB::table('conferences_users')
                ->where('conference_id', $conferenceId)
                ->distinct('user_id')
                ->count('user_id');
            $totalApprovedDelegates = Delegate::whereHas('order', function ($query) {
                $query->whereIn('status', ['approved_online_payment', 'approved_bank_transfer']);
            })->count();
            $totalBookedRooms = Order::where('status', 'approved_online_payment')
                ->orWhere('status', 'approved_bank_transfer')
                ->with('rooms')
                ->get()
                ->pluck('rooms.*.id')
                ->flatten()
                ->unique()
                ->count();
            return [
                'total_amount' => $totalAmount,
                'total_registered_companies' => $totalRegisteredCompanies,
                'total_approved_delegates' => $totalApprovedDelegates,
                'total_booked_rooms' => $totalBookedRooms,
            ];
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function PaymentsCountPerMonth()
    {
        $conferenceId = request()->header('X-Conference-Id');
        $currentYear = Carbon::now()->year;
        $memberPayments = array_fill(0, 12, 0);
        $nonMemberPayments = array_fill(0, 12, 0);

        $totalAmountPerMonthForMember = Order::whereHas('user', function ($query) {
            $query->where('active_member', 1);
        })
            ->whereYear('created_at', $currentYear)
            ->where('conference_id', $conferenceId)
            ->whereIn('status', ['approved_online_payment', 'approved_bank_transfer'])
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($totalAmountPerMonthForMember as $payment) {
            $month = $payment->month - 1;
            $memberPayments[$month] = $payment->count;
        }

        $totalAmountPerMonthForNonMember = Order::whereHas('user', function ($query) {
            $query->where('active_member', 0);
        })
            ->whereYear('created_at', $currentYear)
            ->where('conference_id', $conferenceId)
            ->whereIn('status', ['approved_online_payment', 'approved_bank_transfer'])
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($totalAmountPerMonthForNonMember as $payment) {
            $month = $payment->month - 1;
            $nonMemberPayments[$month] = $payment->count;
        }

        $currentMonth = Carbon::now()->month;
        $monthly = $memberPayments[$currentMonth - 1] + $nonMemberPayments[$currentMonth - 1];

        $previousMonth = Carbon::now()->subMonth()->month;
        $previousMonthly = $memberPayments[$previousMonth - 1] + $nonMemberPayments[$previousMonth - 1];


        $percentChange = 0;
        if ($previousMonthly != 0) {
            $percentChange = (($monthly - $previousMonthly) / $previousMonthly) * 100;
        }

        $response = [
            "status" => "success",
            "message" => "success",
            "data" => [
                "months_number" => [
                    "member" => $memberPayments,
                    "non_member" => $nonMemberPayments,
                ],
                "months" => [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ],
                "total" => array_sum($memberPayments) + array_sum($nonMemberPayments),
                'percent' => number_format($percentChange, 2, '.', ','),
                "monthly" => $monthly,
                "previousMonthly" => $previousMonthly
            ]
        ];
        return response()->json($response);
    }


    public function getDietaryUsersReport()
    {
        $dietaries = Dietary::get();
        return DietaryReportResource::collection($dietaries);
    }

    public function getTshirtSizesReport()
    {
        $tshirtSizes = TshirtSize::get();
        return TshirtSizeReportResource::collection($tshirtSizes);
    }

    public function PaymentsChartPerMonth()
    {
        $conferenceId = request()->header('X-Conference-Id');
        $currentYear = Carbon::now()->year;
        $memberPayments = array_fill(0, 12, 0);
        $nonMemberPayments = array_fill(0, 12, 0);

        $totalAmountPerMonthForMember = Order::whereHas('user', function ($query) {
            $query->where('active_member', 1);
        })
            ->whereYear('created_at', $currentYear)
            ->whereIn('status', ['approved_online_payment', 'approved_bank_transfer'])
            ->where('conference_id', $conferenceId)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total_amount')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($totalAmountPerMonthForMember as $payment) {
            $month = $payment->month - 1;
            $memberPayments[$month] = $payment->total_amount;
        }

        $totalAmountPerMonthForNonMember = Order::whereHas('user', function ($query) {
            $query->where('active_member', 0);
        })
            ->whereYear('created_at', $currentYear)
            ->where('conference_id', $conferenceId)
            ->whereIn('status', ['approved_online_payment', 'approved_bank_transfer'])
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total_amount')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($totalAmountPerMonthForNonMember as $payment) {
            $month = $payment->month - 1;
            $nonMemberPayments[$month] = $payment->total_amount;
        }

        $currentMonth = Carbon::now()->month;
        $monthly = $memberPayments[$currentMonth - 1] + $nonMemberPayments[$currentMonth - 1];

        $previousMonth = Carbon::now()->subMonth()->month;
        $previousMonthly = $memberPayments[$previousMonth - 1] + $nonMemberPayments[$previousMonth - 1];

        $percentChange = 0;
        if ($previousMonthly != 0) {
            $percentChange = (($monthly - $previousMonthly) / $previousMonthly) * 100;
        }

        $response = [
            "status" => "success",
            "message" => "success",
            "data" => [
                "months_number" => [
                    "member" => $memberPayments,
                    "non_member" => $nonMemberPayments,
                ],
                "months" => [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ],
                "total" => array_sum($memberPayments) + array_sum($nonMemberPayments),
                'percent' => number_format($percentChange, 2, '.', ','),
                "monthly" => $monthly,
                "previousMonthly" => $previousMonthly
            ]
        ];
        return response()->json($response);
    }


    // not conferenceId
    public function countVisitCountry(VisitRequest $request)
    {
        try {
            $visitsByCountry = DB::table('visits')
                ->select('country_id', DB::raw('COUNT(*) as visit_count'))
                ->whereNotNull('country_id')
                ->groupBy('country_id')
                ->orderByDesc('visit_count')
                ->take(10)
                ->get();
            $result = [];
            foreach ($visitsByCountry as $visit) {
                $result[] = [
                    'country' => new CountryResource(Country::find($visit->country_id)),
                    'count' => $visit->visit_count,
                ];
            }
            return response()->json(['result' => "Success", 'data' => $result, 'status' => 200], 200);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
