<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\EventDataHelper;
use App\Helpers\FileUploadAction;
use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Conference\DelegateRequest;
use App\Http\Requests\Conference\SpouseRequest;
use App\Http\Resources\Conference\DelegateOrderResource;
use App\Http\Resources\Conference\OrderResource;
use App\Http\Resources\Conference\OrderRoomResource;
use App\Http\Resources\Conference\PersonRoomResource;
use App\Http\Resources\Conference\SpouseOrderResource;
use App\Interfaces\OrderRepositoryInterface;
use App\Models\Coupon;
use Illuminate\Validation\ValidationException;
use App\Models\Delegate;
use App\Models\Order;
use App\Models\Room;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ConferenceOrderController extends Controller
{
    protected mixed $crudRepository;

    /**
     * @param OrderRepositoryInterface $pattern
     */
    public function __construct(OrderRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    /**
     * Show the order by ID while recalculation all the order prices before showing it with sumOrderTotal() private
     * method to ensure all prices are up-to-date
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse|OrderResource
     */
    public function show(Order $order)
    {
        try {
            EventDataHelper::sumOrderTotal($order->id);
            $orderResource = new OrderResource($order);
            return $orderResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Update or remove package option for the order where to remove requested package_id should be null
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderPackage(Request $request, Order $order)
    {
        try {
            $packageId = $request->input('package_id');
            $order->update(['package_id' => $packageId]);
            EventDataHelper::sumOrderTotal($order->id);

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * @param Request $request
     *  Add or remove or update -> sync the sponsorship items for the order_sponsorship_item pivot table
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncSponsorshipItem(Request $request, Order $order)
    {
        try {
            $order->sponsorshipItems()->sync($request->sponsorshipItems);
            EventDataHelper::sumOrderTotal($order->id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    /**
     * Add coupon id to order coupon_id column while checking for total used count
     * @param Request $request
     * @param Order $order
     * @var Coupon $coupon
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCouponToOrder(Request $request, Order $order)
    {
        try {
            $couponCode = $request->input('coupon_code');
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon instanceof Coupon) {
                $couponUsageCount = Order::where('coupon_id', $coupon->id)->count();
                if ($coupon->count > 0 && $couponUsageCount >= $coupon->count || $coupon->count === 0) {
                    return JsonResponse::respondError('Coupon has reached its usage limit', 404);
                }
                if ($coupon->count > 0) {
                    $order->update(['coupon_id' => $coupon->id]);
                } else {
                    return JsonResponse::respondError('Coupon has reached its usage limit', 404);
                }
            } else {
                return JsonResponse::respondError('Coupon code is invalid', 404);
            }
            EventDataHelper::sumOrderTotal($order->id);

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Remove coupon from the order by setting its coupon_id value to null
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeCouponFromOrder(Order $order)
    {
        try {
            $order->update(['coupon_id' => null]);
            EventDataHelper::sumOrderTotal($order->id);

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_COUPON_REMOVED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Assign Room with duration to Order
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRoomToOrder(Request $request, Order $order)
    {
        try {
            $roomId = $request->input('room_id');
            $delegateIds = $request->input('persons');
            $bedType = $request->input('bed_type');
            $dates = $request->input('date');

            $delegateIdArray = [];
            foreach ($delegateIds as $delegateData) {
                $person = Delegate::find($delegateData['id']);
                $delegateIdArray[] = ['id' => $person->id];
            }

            // Calculate the number of nights between start and end dates
            $startDate = Carbon::parse($dates[0]);
            $endDate = Carbon::parse($dates[1]);
            $numberOfNights = $endDate->diffInDays($startDate);

            // Calculate the total price based on the number of nights
            $room = Room::findOrFail($roomId);
            $price = $room->price;
            $totalPrice = $numberOfNights * $price;


            // Store the data in the pivot table
            $order->rooms()->attach($roomId, [
                'delegate_id' => json_encode($delegateIdArray),
                'total_price' => $totalPrice,
                'bed_type' => $bedType,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
            EventDataHelper::sumOrderTotal($order->id);

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function showRoomInOrder(Order $order, $bookedRoomID)
    {
        try {
            $room = $order->rooms()->wherePivot('id', $bookedRoomID)->first();
            if (!$room) {
                return response()->json(['message' => 'Room not found in order'], 404);
            }
            return new OrderRoomResource($room);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Update Room Record in Order
     * @param Request $request
     * @param Order $order
     * @param int $bookedRoomID
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRoomInOrder(Request $request, Order $order, int $bookedRoomID)
    {
        try {
            // Check if the room is associated with the order
            if (!$order->rooms()->wherePivot('id', $bookedRoomID)->wherePivot('order_id', $order->id)->exists()) {
                return JsonResponse::respondError('Room is not found or not associated with this order', 404);
            }

            // Update room details
            $roomId = $request->input('room_id');
            $persons = $request->input('persons');
            $room = Room::findOrFail($roomId);
            $maxDelegates = $room->delegates_count;
            if (count($persons) > $maxDelegates) {
                return JsonResponse::respondError('Number of delegates exceeds room capacity.');
            }
            $bedType = $request->input('bed_type');
            $dates = $request->input('date');
            $startDate = Carbon::parse($dates[0]);
            $endDate = Carbon::parse($dates[1]);
            $numberOfNights = $endDate->diffInDays($startDate);
            $price = $room->price;
            $totalPrice = $numberOfNights * $price;
            $delegateIdArray = [];
            foreach ($persons as $delegateData) {
                $person = Delegate::find($delegateData['id']);
                $delegateIdArray[] = ['id' => $person->id];
            }
            // Update the pivot table record
            $order->rooms()->wherePivot('id', $bookedRoomID)->update([
                'delegate_id' => json_encode($delegateIdArray),
                'total_price' => $totalPrice,
                'bed_type' => $bedType,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
            // Update the total order price
            EventDataHelper::sumOrderTotal($order->id);


            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    /**
     * Remove Room from Order.
     *
     * @param Order $order
     * @param int $bookedRoomID
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeRoomFromOrder(Order $order, int $bookedRoomID)
    {
        try {
            // Find the room in the pivot table
            $roomInOrder = $order->rooms()->wherePivot('id', $bookedRoomID)->first();

            // If the room is not found or does not belong to the specified order, return an error response
            if (!$roomInOrder || $roomInOrder->pivot->order_id !== $order->id) {
                return JsonResponse::respondError('Room not found with this order.', 404);
            }
            $order->rooms()->wherePivot('id', $bookedRoomID)->detach();

            // Update the total order price
            EventDataHelper::sumOrderTotal($order->id);

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Add Delegate to the Order
     * @param DelegateRequest $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDelegateToOrder(DelegateRequest $request, Order $order)
    {
        try {
            $existingDelegate = Delegate::where('email', $request->input('email'))
                ->where('order_id', $order->id)
                ->exists();
            if ($existingDelegate) {
                return response()->json([
                    'result' => 'Error',
                    'data' => null,
                    'message' => 'The email has already been taken for this order',
                    'status' => 404
                ], 404);
            }
            $userId = Auth::user()->id;
            $passwordRandom = Str::random(8);
            $delegate = new Delegate(['type' => 'delegate', 'user_id' => $userId, 'password' => Hash::make($passwordRandom), 'unhashed_password' => $passwordRandom]);
            $delegate->fill($request->validated());
            $delegate->order()->associate($order);
            $delegate->save();
            if ($request->filled('dietaries')) {
                $dietaries = $request->input('dietaries');
                $delegate->dietaries()->sync($dietaries);
            }
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $delegate);
            }
            if (request('bc') !== null) {
                $this->crudRepository->AddMediaCollection('bc', $delegate, 'bc');
            }
            EventDataHelper::sumOrderTotal($order->id);


            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Add Spouse to the Order
     * @param SpouseRequest $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSpouseToOrder(SpouseRequest $request, Order $order)
    {
        try {
            $userId = Auth::user()->id;
            $spouse = new Delegate(['type' => 'spouse', 'user_id' => $userId]);
            $spouse->fill($request->validated());
            $spouse->order()->associate($order);
            $spouse->save();
            if ($request->filled('dietaries')) {
                $dietaries = $request->input('dietaries');
                $spouse->dietaries()->sync($dietaries);
            }
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $spouse);
            }
            EventDataHelper::sumOrderTotal($order->id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Remove Delegate form the order while checking if the delegate is associated with the corresponding order ID
     * @param Order $order
     * @param Delegate $delegate
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeDelegateFromOrder(Order $order, Delegate $delegate)
    {
        try {
            if ($delegate->type === 'delegate' && $delegate->order_id === $order->id) {
                $rowsToDelete = DB::table('orders_rooms')->where('order_id', $order->id)->get();
                foreach ($rowsToDelete as $row) {
                    $delegateIds = json_decode($row->delegate_id, true);
                    if (is_array($delegateIds)) {
                        foreach ($delegateIds as $key => $id) {
                            if ($delegateIds[$key]['id'] == $delegate->id) {
                                unset($delegateIds[$key]);
                                break;
                            }
                        }
                        if (empty($delegateIds)) {
                            DB::table('orders_rooms')->where('id', $row->id)->delete();
                        } else {
                            DB::table('orders_rooms')->where('id', $row->id)->update(['delegate_id' => json_encode(array_values($delegateIds))]);
                        }
                    }
                }
                $delegate->delete();
                EventDataHelper::sumOrderTotal($order->id);

                return JsonResponse::respondSuccess(['message' => 'Delegate deleted successfully']);
            } else {
                return JsonResponse::respondError(['message' => 'Delegate not found or not associated with the order'], 404);
            }
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    /**
     * Remove Spouse form the order while checking if the delegate is associated with the corresponding order ID
     * @param Order $order
     * @param Delegate $spouse
     * @return \Illuminate\Http\JsonResponse
     * @throws Throwable
     */
    public function removeSpouseFromOrder(Order $order, Delegate $spouse)
    {
        try {
            if ($spouse->type === 'spouse' && $spouse->order_id === $order->id) {
                $rowsToDelete = DB::table('orders_rooms')->where('order_id', $order->id)->get();
                foreach ($rowsToDelete as $row) {
                    $spouseIds = json_decode($row->delegate_id, true);
                    if (is_array($spouseIds)) {
                        foreach ($spouseIds as $key => $id) {
                            if ($spouseIds[$key]['id'] == $spouse->id) {
                                unset($spouseIds[$key]);
                                break;
                            }
                        }
                        if (empty($spouseIds)) {
                            DB::table('orders_rooms')->where('id', $row->id)->delete();
                        } else {
                            DB::table('orders_rooms')->where('id', $row->id)->update(['delegate_id' => json_encode(array_values($spouseIds))]);
                        }
                    }
                }
                $spouse->delete();
                EventDataHelper::sumOrderTotal($order->id);

                return JsonResponse::respondSuccess(['message' => 'Spouse deleted successfully']);
            } else {
                return JsonResponse::respondError(['message' => 'Spouse not found or not associated with the order'], 404);
            }
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Order $order)
    {
        try {
            $order = Order::find($order->id);
            if (!in_array($order->status, ['approved_online_payment', 'approved_bank_transfer'])) {
                $order->delegates()->delete();
                $order->sponsorshipItems()->detach();
                $order->rooms()->detach();
                $order->delete();
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
            } else {
                return JsonResponse::respondError('Order can not be deleted because it has been Approved', 401);
            }
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function changOrderStatus(Request $request, Order $order)
    {
        try {
            $user = Auth::user();
            if ($order->user_id !== $user->id) {
                return JsonResponse::respondError(trans('user_not_authorized'), 403);
            }
            $allowedStatuses = ['in_application_form', 'pending_payment', 'pending_bank_transfer'];
            $status = $request->input('status');
            if (!in_array($status, $allowedStatuses)) {
                throw ValidationException::withMessages([
                    'status' => 'The provided status is invalid.',
                ]);
            }
            $order->update(['status' => $status]);
            if ($status == 'pending_bank_transfer') {
                try {
                    $fileUploadAction = new FileUploadAction();
                    $fileUploadAction->sendInvoiceEmails($order,$user);
                } catch (\Exception $e) {
                    \Log::error('error sending invoice email: ' . $e->getMessage(), ['context' => $e]);
                    JsonResponse::respondError($e->getMessage());
                }
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * @param Order $order
     * @param Delegate $delegate
     * @return \Illuminate\Http\JsonResponse
     */
    public function showOrderDelegate(Order $order, Delegate $delegate)
    {
        try {
            $userId = Auth::user()->id;
            if ($delegate->type === 'delegate' && $delegate->order_id === $order->id && $userId === $delegate->user_id) {
                $delegateResource = new DelegateOrderResource($delegate);
                return JsonResponse::respondSuccess('Delegate Fetched Successfully', $delegateResource);
            } else {
                return JsonResponse::respondError('Delegate not found or not associated with the order', 404);
            }
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * @param Order $order
     * @param Delegate $spouse
     * @return \Illuminate\Http\JsonResponse
     */
    public function showOrderSpouse(Order $order, Delegate $spouse)
    {
        try {
            if ($spouse->type === 'spouse' && $spouse->order_id === $order->id) {
                $spouseResource = new SpouseOrderResource($spouse);
                return JsonResponse::respondSuccess('Spouse Fetched Successfully', $spouseResource);
            } else {
                return JsonResponse::respondError('Spouse not found or not associated with the order', 404);
            }
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * @param DelegateRequest $request
     * @param Order $order
     * @param Delegate $delegate
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderDelegate(DelegateRequest $request, Order $order, Delegate $delegate)
    {
        try {
            $userId = Auth::user()->id;
            if ($delegate->user_id !== $userId) {
                return JsonResponse::respondError('You are not authorized to edit delegate that not belongs to your company');
            }
            if ($delegate->order_id !== $order->id) {
                return JsonResponse::respondError('This Delegate is not associated with this Order');
            }
            $delegate->fill($request->validated());
            $delegate->update();
            if ($request->filled('dietaries')) {
                $dietaries = $request->input('dietaries');
                $delegate->dietaries()->sync($dietaries);
            }
            if (request('image') !== null) {
                $image = $this->crudRepository->AddMediaCollection('image', $delegate);
            }
            if (request('bc') !== null) {
                $bc = $this->crudRepository->AddMediaCollection('bc', $delegate, 'bc');
            }
            EventDataHelper::sumOrderTotal($order->id);

            $delegateResource = new DelegateOrderResource($delegate);
            return JsonResponse::respondSuccess('Delegate Fetched Successfully', $delegateResource);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * @param SpouseRequest $request
     * @param Order $order
     * @param Delegate $spouse
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderSpouse(SpouseRequest $request, Order $order, Delegate $spouse)
    {
        try {
            $userId = Auth::user()->id;
            if ($spouse->user_id !== $userId) {
                return JsonResponse::respondError('You are not authorized to edit spouse that not belongs to your company');
            }
            if ($spouse->order_id !== $order->id) {
                return JsonResponse::respondError('This Spouse is not associated with this Order');
            }
            $spouse->fill($request->validated());
            $spouse->update();
            if ($request->filled('dietaries')) {
                $dietaries = $request->input('dietaries');
                $spouse->dietaries()->sync($dietaries);
            }
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $spouse);
            }
            EventDataHelper::sumOrderTotal($order->id);

            $spouseResource = new SpouseOrderResource($spouse);
            return JsonResponse::respondSuccess('Delegate Fetched Successfully', $spouseResource);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse|AnonymousResourceCollection
     */
    public function personsWithRoom(User $user)
    {
        try {
            $delegates = $user->delegates()->get();
            $spouses = $user->spouses()->get();
            $allPersons = $delegates->merge($spouses);
            $delegates = $user->delegates;
            $delegatesWithoutRooms = [];
            foreach ($allPersons as $delegate) {
                $delegateId = $delegate->id;
                $delegateData = DB::table('orders_rooms')
                    ->whereJsonContains('delegate_id', ['id' => $delegateId])
                    ->get();
                if ($delegateData->isEmpty()) {
                    $delegatesWithoutRooms[] = $delegateId;
                }
            }
            $delegatesWithoutRoomsInfo = Delegate::whereIn('id', $delegatesWithoutRooms)->get();
            $delegatesWithoutRoomsInfos =   PersonRoomResource::collection($delegatesWithoutRoomsInfo);
            return $delegatesWithoutRoomsInfos->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function storeOrder(Request $request, Order $order, User $user)
    {
        try {
            $authenticatedUser = Auth::user();
            $previousOrders = $authenticatedUser->orders()->whereNotIn('status', ['in_application_form', 'pending_payment', 'pending_bank_transfer'])->exists();
            if ($previousOrders) {
                $orderData = [
                    'user_id' => $authenticatedUser->id,
                    'conference_id' => $request->header('X-Conference-Id'),
                ];
                $order = Order::create($orderData);
                EventDataHelper::sumOrderTotal($order->id);
                $uuid = generateUUID($order->id); 
                DB::table('orders')->where('id', $order->id)->update(['uuid' => $uuid]);
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            } else {
                return JsonResponse::respondError("Cannot create order. All company's orders are not approved.");
            }
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
