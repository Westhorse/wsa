<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\EventDataHelper;
use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\DelegateRequest;
use App\Http\Resources\Conference\DelegateResource;
use App\Http\Resources\Conference\EventCompanyLoginResource;
use App\Http\Resources\Conference\EventDelegateLoginResource;
use App\Interfaces\DelegateRepositoryInterface;
use App\Mail\Event\EventResetPasswordMail;
use App\Models\Delegate;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;
use Throwable;

class DelegateController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(DelegateRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $delegates = DelegateResource::collection($this->crudRepository->all([
                'user',
                'tshirt_size',
                'phoneKey',
                'cellKey',
                'dietaries'
            ], [], ['*']));

            return $delegates;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic(Request $request)
    {
        try {
            $conferenceId = $request->header('X-Conference-Id');
            $delegates = Delegate::where('conference_id',$conferenceId)->where('type','delegate')->get();
            $delegate = DelegateResource::collection($delegates);
            return $delegate->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(DelegateRequest $request)
    {
        try {
            $delegate =  $this->crudRepository->create($request->validated());
            DB::table('delegates')->where('id', $delegate->id)->update(['password' => Hash::make($request['unhashed_password']), 'unhashed_password' => $request['unhashed_password'],]);
            if ($request->dietaries) $delegate->dietaries()->sync($request->dietaries);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $delegate);
            }
            if (request('bc') !== null) {
                $this->crudRepository->AddMediaCollection('bc', $delegate, 'bc');
            }
            return JsonResponse::success();
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param Delegate $delegate
     * @return DelegateResource|\Illuminate\Http\JsonResponse
     */

    public function show(Delegate $delegate)
    {
        try {

            $delegateResource = new DelegateResource($delegate);

            return $delegateResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param DelegateRequest $request
     * @param Delegate $delegate
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DelegateRequest $request, Delegate $delegate): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $delegate->id);
            $user = Delegate::find($delegate->id);
            if ($request->dietaries) {
                $user->dietaries()->sync($request->dietaries, 'dietary_id');
            }
            if (request('image') !== null) {
                $image = $this->crudRepository->AddMediaCollection('image', $user);
            }
            if (request('bc') !== null) {
                $bc = $this->crudRepository->AddMediaCollection('bc', $user, 'bc');
            }
            activity()->performedOn($user)->withProperties(['attributes' => $user])->log('update');
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
            $count = $this->crudRepository->deleteRecords('delegates', $request['items']);
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
            $this->crudRepository->restoreItem(Delegate::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecordsFinial(Delegate::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function loginEvent(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::where('email', $request->email)->where('unhashed_password', $request->password)->first();

            $delegate = Delegate::where('email', $request->email)->where('unhashed_password', $request->password)->first();
            if ($user || $delegate) {
                $authenticatedUser = $user ?? $delegate;
                if ($delegate) $users = new EventDelegateLoginResource($delegate);

                if ($user) {
                    $users = new EventCompanyLoginResource($user);
                    if ($user->orders()->count() === 0) {

                        $orderData = [
                            'user_id' => $authenticatedUser->id,
                            'conference_id' => $request->header('X-Conference-Id'),
                        ];
                        if ($request->header('X-Conference-Id')) $user->conferences()->sync($request->header('X-Conference-Id'));

                        $order = Order::create($orderData);
                        EventDataHelper::sumOrderTotal($order->id);
                        $uuid = generateUUID($order->id);
                        DB::table('orders')->where('id', $order->id)->update(['uuid' => $uuid]);
                        DB::table('conferences_users')->where('conference_id', $request->header('X-Conference-Id'))->where('user_id', $user->id)->update(['type' => 'member']);
                    }
                }

                return response()->json([
                    'user' => $users,
                    "result"=> "Success",
                    'message' => 'Logged In Successfully',
                    'status' => true,
                    'token' => $authenticatedUser->createToken("API TOKEN")->plainTextToken
                ], 200);
            }
            return response()->json([
                "data" => null,
                "result" => "Error",
                'message' => 'Email & Password do not match with our records.',
                'status' => false,
            ], 401);
        } catch (Throwable $th) {
            return response()->json([
                "data" => null,
                "result" => "Error",
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function loginAsDelegate(Delegate $delegate)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Login As Token Generated Successfully',
                'token' => $delegate->createToken("LoginAsToken")->plainTextToken
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }



    public function resetPassword(Request $request)
    {
        try {

            if ($request->input('type') == 'company') {
                $person_id = $request->input('person_id');
                $person =  User::where('id', $person_id)->first();
            } else {
                $person_id = $request->input('person_id');
                $person =  Delegate::where('id', $person_id)->first();
            }

            Queue::push(function () use ($person) {
                $subject = DB::table('email_templates')->where('slug', 'event_reset_password_email_template')->value('subject'); // send email
                $template = DB::table('email_templates')->where('slug', 'event_reset_password_email_template')->value('body');  // send email
                $template = str_replace(
                    [
                        '{{email}}',
                        '{{password}}',
                    ],
                    [
                        $person->email,
                        $person->unhashed_password,
                    ],
                    $template
                );
                Mail::to($person->email)->send(new EventResetPasswordMail($template, $subject));
            });
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_EMAIL_SENDING));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage(), 401);
        }
    }
}
