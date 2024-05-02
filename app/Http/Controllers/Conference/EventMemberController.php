<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\EventDataHelper;
use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\ApplicationFormRequestEventNonMember;
use App\Http\Requests\Conference\DashboardMemberEventRequest;
use App\Http\Requests\Conference\MemberEventRequest;
use App\Http\Resources\Conference\DelegateOrderResource;
use App\Http\Resources\Conference\EventCompanyLoginResource;
use App\Http\Resources\Conference\EventCompanyShowLoginResource;
use App\Http\Resources\Conference\EventCompanyShowResource;
use App\Http\Resources\Conference\EventCompanyUpdateResource;
use App\Http\Resources\Conference\EventDelegateLoginResource;
use App\Interfaces\UserRepositoryInterface;
use App\Mail\Event\EventUserMail;
use App\Mail\Event\EventUserRequestMail;
use App\Models\Delegate;
use App\Models\Order;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class EventMemberController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(UserRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }


    public function storeEventNonMember(ApplicationFormRequestEventNonMember $request)
    {
        try {
            // Store user data
            $user = $this->crudRepository->create($request->validated());
            $passwordRandom = Str::random(8);
            DB::table('users')->where('id', $user->id)->update(['password' => Hash::make($passwordRandom), 'unhashed_password' => $passwordRandom]);
            DB::table('users')->where('id', $user->id)->update(['detected_country_id' => $request['detected_country_id']]);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $user);
            }
            if ($request->header('X-Conference-Id')) $user->conferences()->sync($request->header('X-Conference-Id'));
            DB::table('users')->where('id', $user->id)->update(['active_member' => 0]);

            // Create order if user doesn't have any orders
            $order = $user->orders;
            if ($user && $user->orders()->count() === 0) {
                $orderData = [
                    'user_id' => $user->id,
                    'conference_id' => $request->header('X-Conference-Id'),
                ];
                $order = Order::create($orderData);
                $uuid = generateUUID($order->id); 
                DB::table('orders')->where('id', $order->id)->update(['uuid' => $uuid]);
                                EventDataHelper::sumOrderTotal($order->id);
                cache()->forget('user_event_' . auth()->id());
            }

            try {

                    $emails = DB::table('email_templates')->where('slug', 'conference_new_application_confirmation_email_template')->select('bcc')->first();
                    $emails_bcc = explode(',', $emails->bcc);
                    foreach ($emails_bcc as $email) {
                        Mail::to($email)->queue(new EventUserMail($user));
                    }
                    $template = DB::table('email_templates')->where('slug', 'conference_new_application_confirmation_email_template')->value('body');
                    $subject = DB::table('email_templates')->where('slug', 'conference_new_application_confirmation_email_template')->value('subject');
                    $template = str_replace(
                        [
                            '{{email}}',
                            '{{password}}',
                        ],
                        [
                            $email = $user->email,
                            $password = $passwordRandom,
                        ],
                        $template
                    );
                    Mail::to($user->email)->queue(new EventUserRequestMail($template, $subject));

            } catch (Exception $e) {
                Log::error('Error sending contact Us emails: ' . $e->getMessage(), ['context' => $e]);
                JsonResponse::respondError($e->getMessage());
            }

            // Return response
            return response()->json([
                'status' => true,
                'message' => 'Company Registered Successfully',
                'user' => new EventCompanyLoginResource($user, $order),
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (Exception $e) {
            // Catch any exceptions
            return JsonResponse::respondError($e->getMessage());
        }
    }





    public function indexAllEventMember()
    {
        try {
            $delegateRepository = new UserRepository(new User);
            return EventCompanyLoginResource::collection($delegateRepository->allMember(['orders'], [], ['*']));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /////////////////////show//////////////////////////////

    public function show(User $user)
    {
        try {
            $user = new EventCompanyShowResource($user);
            return $user->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function getUserEvent()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'error' => 'Member is not loggedin, please login'
                ], 401);
            }
            $resource = null;

            if ($user instanceof Delegate) {
                $resource = new EventDelegateLoginResource($user);
            } elseif ($user instanceof User) {
                $resource = new EventCompanyShowLoginResource($user);
            }
            $userData = $resource;
            return response()->json([
                'data' => $userData,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /////////////////////show//////////////////////////////
    /////////////////////update//////////////////////////////


    public function updateDashboardMember(DashboardMemberEventRequest $request, User $user)
    {
        try {
            $this->crudRepository->update($request->validated(), $user->id);
            DB::table('users')->where('id', $user->id)->update(['password' => Hash::make($request['unhashed_password']), 'unhashed_password' => $request['unhashed_password'],]);
            if (request('image') !== null) {
                $network = User::find($user->id);
                $this->crudRepository->AddMediaCollection('image', $network);
            }
            if ($request->header('X-Conference-Id')) $user->conferences()->sync($request->header('X-Conference-Id'), 'service_id');
            DB::table('conferences_users')->where('conference_id', $request->header('X-Conference-Id'))->where('user_id', $user->id)->update(['type' => $request['membershipType']]);

            if (DB::table('conferences_users')->where('conference_id', $request->header('X-Conference-Id'))->where('user_id', $user->id)->value('type') == "member") {
                DB::table('users')->where('id', $user->id)->update(['active_member' => 1]);
            }

            activity()->performedOn($user)->withProperties(['attributes' => $user])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function updateMember(MemberEventRequest $request, User $user)
    {
        try {
            $authUser = Auth::user();
            if ($authUser->id === $user->id) {
                $this->crudRepository->update($request->validated(), $user->id);
                if (request('image') !== null) {
                    $network = User::find($user->id);
                    $this->crudRepository->AddMediaCollection('image', $network);
                }
                if ($request->header('X-Conference-Id')) $user->conferences()->sync($request->header('X-Conference-Id'), 'service_id');
                activity()->performedOn($user)->withProperties(['attributes' => $user])->log('update');
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            } else {
                return JsonResponse::respondError('You do not have permission to do this action');
            }
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function getEventUserProfile()
    {
        try {
            $user = Auth::user();
            $userResource = new EventCompanyUpdateResource($user);
            return JsonResponse::respondSuccess(null, $userResource);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function getEventDelegateProfile()
    {
        try {
            $user = Auth::user();
            $userResource = new DelegateOrderResource($user);
            return JsonResponse::respondSuccess(null, $userResource);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function loginAsCompany(User $user)
    {
        try {
            $user = User::where('id', $user->id)->first();
            $userAs = new EventCompanyLoginResource($user);
            return response()->json([
                'status' => true,
                'message' => 'Logged In Successfully',
                'delegate' => $userAs,
                'token' => $userAs->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
