<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\FileUploadAction;
use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Conference\OrderDashboardResource;
use App\Http\Resources\Conference\OrderDashboardViewResource;
use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\TimeSlot;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardOrderController extends Controller
{
    protected mixed $crudRepository;

    /**
     * @param OrderRepositoryInterface $pattern
     */
    public function __construct(OrderRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $orderResources = OrderDashboardResource::collection($this->crudRepository->allOrder(
                ['user', 'package', 'delegates', 'spouses', 'sponsorshipItems', 'rooms', 'coupon', 'conference'],
                [],
                ['*']
            ));
            return $orderResources;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Order $order)
    {
        try {
            $orderResource = new OrderDashboardViewResource($order);
            return $orderResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function delete(Order $order)
    {
        try {
            $order = Order::find($order->id);
            $order->delegates()->delete();
            $order->sponsorshipItems()->detach();
            $order->rooms()->detach();
            $order->delete();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    ///// دى تغير حالته من لوحه التحكم لما طلب انه يدفع
    public function changOrderStatusDashboard(Request $request, Order $order)
    {
        try {
            $status = $request->input('status');
            $order->update(['status' => $status]);
            if ($status == 'approved_bank_transfer') {
                $user = $order->user;
                $delegates = $user->delegates;
                foreach ($delegates as $delegate) {
                    $timeSlots = TimeSlot::all();
                    $delegate->timeSlots()->sync($timeSlots);
                }
                $fileUploadAction = new FileUploadAction();
                $fileUploadAction->sendConfirmationEmails($order);
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function conferenceMemberDelete(Request $request, User $user)
    {
        try {
            $conferenceId = $request->header('X-Conference-Id');
            $userConference = DB::table('conferences_users')->where('user_id', $user->id)
                ->where('conference_id', $conferenceId)->first();
            if ($userConference->type !== "non_member") {
                // member
                DB::table('conferences_users')
                    ->where('user_id', $user->id)
                    ->where('conference_id', $conferenceId)
                    ->delete();

                DB::table('orders')
                    ->where('user_id', $user->id)
                    ->where('conference_id', $conferenceId)
                    ->delete();
            } else {
                // non_member
                DB::table('conferences_users')
                    ->where('user_id', $user->id)
                    ->where('conference_id', $conferenceId)
                    ->delete();

                DB::table('orders')
                    ->where('user_id', $user->id)
                    ->where('conference_id', $conferenceId)
                    ->delete();

                DB::table('users')
                    ->where('id', $user->id)
                    ->where('active_member', 0)
                    ->delete();
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
