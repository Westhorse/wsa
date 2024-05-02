<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Common\ApplicationFormRequest;
use App\Http\Requests\Common\MemberRequest;
use App\Http\Requests\Common\UserRequest;
use App\Http\Resources\Common\GetMemberResource;
use App\Http\Resources\Common\UserAuthResource;
use App\Http\Resources\Common\UserIndexResource;
use App\Http\Resources\Common\UserMapResource;
use App\Http\Resources\Common\UserResource;
use App\Http\Resources\Common\UserSimpleIndexResource;
use App\Http\Resources\Dashboard\UserSimpleResource;
use App\Interfaces\UserRepositoryInterface;
use App\Mail\ApprovedUser;
use App\Mail\ResetPasswordUserMail;
use App\Mail\UserMail;
use App\Mail\UserRequestMail;
use App\Models\Country;
use App\Models\Network;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;
use Stevebauman\Location\Facades\Location;
use Throwable;

class UserController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(UserRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index(Request $request)
    {
        try {
            if ($request['resource'] == 'directory') {
                $users = UserSimpleResource::collection($this->crudRepository->all());
            } elseif ($request['resource'] == 'map') {
                $users = UserMapResource::collection($this->crudRepository->all());
            } else {
                $users = UserIndexResource::collection($this->crudRepository->all(
                    ['networks', 'country', 'media'],
                    [],
                    ['*'],
                ));
            }
            return $users->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function blackList()
    {
        try {
            $users = UserSimpleIndexResource::collection($this->crudRepository->blackList(
                ['networks', 'country'],
                [],
                ['id', 'name', 'wsa_id', 'city', 'state', 'company_email', 'type_company', 'country_id']
            ));
            return $users->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(ApplicationFormRequest $request)
    {
        try {
            $user = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $user);
            }
            if ($request->contactPersons) $user->contactPersons()->createMany($request->contactPersons);
            if ($request->tradeReferences) $user->tradeReferences()->createMany($request->tradeReferences);
            if ($request->services) $user->services()->sync($request->services);
            if ($request->certificates) $user->certificates()->sync($request->certificates);
            if ($request->networks) $user->networks()->sync($request->networks);
            DB::table('users')->where('id', $user->id)->update(['ref_id' => $request->input('ref')]);

            try {
                $template = DB::table('email_templates')->where('id', 4)->value('body');
                $subject = DB::table('email_templates')->where('id', 4)->value('subject');
                Mail::to($user->email)->queue(new UserRequestMail($template, $subject));

                $emails = DB::table('email_templates')->where('id', 4)->select('bcc')->first();
                $emails_bcc = explode(',', $emails->bcc);
                foreach ($emails_bcc as $email) {
                    Mail::to($email)->queue(new UserMail($user));
                }
            } catch (Exception $e) {
                Log::error('Error sending application form emails: ' . $e->getMessage(), ['context' => $e]);
                JsonResponse::respondError($e->getMessage());
            }

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function createApplication(UserRequest $request)
    {
        try {
            $user = $this->crudRepository->create($request->validated());
            DB::table('users')->where('id', $user->id)->update(['password' => Hash::make($request['unhashed_password']), 'unhashed_password' => $request['unhashed_password'],]);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $user);
            }
            if ($request->contactPersons) $user->contactPersons()->createMany($request->contactPersons);
            if ($request->contactPersons && array_filter($request->contactPersons)) {
                $user->contactPersons()->createMany(array_filter($request->contactPersons, function ($item) {
                    return !empty(array_filter($item));
                }));
            }
            if ($request->tradeReferences && array_filter($request->tradeReferences)) {
                $user->tradeReferences()->createMany(array_filter($request->tradeReferences, function ($item) {
                    return !empty(array_filter($item));
                }));
            }
            if ($request->services) $user->services()->sync($request->services);
            if ($request->certificates) $user->certificates()->sync($request->certificates);
            if ($request->networks) $user->networks()->sync($request->networks);
            DB::table('users')->where('id', $user->id)->update(['ref_id' => $request->input('ref')]);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY), $user);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function show(User $user)
    {
        try {
            $user = new UserResource($user);
            return $user->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function memberUpdate(MemberRequest $request, User $user)
    {
        try {
            $this->crudRepository->update($request->validated(), $user->id);
            if (request('image') !== null) {
                $network = User::find($user->id);
                $this->crudRepository->AddMediaCollection('image', $network);
            }
            if ($request->services) $user->services()->sync($request->services, 'service_id');
            if ($request->certificates) $user->certificates()->sync($request->certificates, 'certificate_id');
            activity()->performedOn($user)->withProperties(['attributes' => $user])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            $this->crudRepository->update($request->validated(), $user->id);
            DB::table('users')->where('id', $user->id)->update(['password' => Hash::make($request['unhashed_password']), 'unhashed_password' => $request['unhashed_password'],]);
            if (request('image') !== null) {
                $network = User::find($user->id);
                $this->crudRepository->AddMediaCollection('image', $network);
            }
            if ($request->services) $user->services()->sync($request->services, 'service_id');
            if ($request->certificates) $user->certificates()->sync($request->certificates, 'certificate_id');
            if ($request->conferences) $user->conferences()->sync($request->conferences, 'conference_id');
            activity()->performedOn($user)->withProperties(['attributes' => $user])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('users', $request['items'], [
                'certificates',
                'services',
                'networks',
                'contactPersons',
                'tradeReferences',
                'Referral'
            ]);
            return $count > 111
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 111 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(User::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecordsFinial(User::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function loginCompany(Request $request): \Illuminate\Http\JsonResponse
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
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password do not match with our records.',
                ], 401);
            }
            $blacklistedNetworks = $user->networks()->wherePivot('status', 'blacklisted')->get();
            if ($blacklistedNetworks->isNotEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User is blacklisted and cannot log in.',
                ], 401);
            }
            $activeNetworks = $user->networks()->wherePivot('active', '1')->get();
            if ($activeNetworks->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User is notActive and cannot log in.',
                ], 401);
            }
            activity()->performedOn($user)->withProperties(['attributes' => $user])->log('login');
            return response()->json([
                'status' => true,
                'user' => new UserAuthResource($user),
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logoutCompany(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function ip(Request $request)
    {
        try {
            $ip = $request['ip'];
            $code = Location::get($ip)->countryCode;
            $country = Country::where('code', $code)->first();
            return response()->json([
                'success' => JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY)),
                'country_id' => $country->id,
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateActiveNetwork($id, Request $request)
    {
        try {
            $user = User::find($id);
            $network = $request['network_id'];
            $existingNetwork = DB::table('users_networks')
                ->where('user_id', $user->id)
                ->where('network_id', $network)
                ->first();
            $updateData = [
                'status' => $request['status'],
                'type' => $request['type'],
                'start_date' => $request['start_date'],
                'expire_date' => $request['expire_date'],
                'updated_at' => now()
            ];
            if ($request->has('network')) {
                $updateData['network'] = $request['network'];
            }
            if ($request->has('active')) {
                $updateData['active'] = $request['active'];
            }
            if ($request->has('fpp') && $request['status'] === 'suspended') {
                $updateData['fpp'] = false;
            } elseif ($request->has('fpp')) {
                $updateData['fpp'] = $request['fpp'];
            }
            if ($existingNetwork) {
                DB::table('users_networks')
                    ->where('user_id', $user->id)
                    ->where('network_id', $network)
                    ->update($updateData);
            } else {
                $insertData = [
                    'user_id' => $user->id,
                    'network_id' => $network,
                    'status' => $request['status'],
                    'type' => $request['type'],
                    'start_date' => $request['start_date'],
                    'expire_date' => $request['expire_date'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                if ($request->has('network')) {
                    $insertData['network'] = $request['network'];
                }
                if ($request->has('active')) {
                    $insertData['active'] = $request['active'];
                }
                if ($request->has('fpp')) {
                    $insertData['fpp'] = $request['fpp'];
                }
                DB::table('users_networks')->insert($insertData);
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function getActiveNetwork($id, Request $request)
    {
        $users = $request->input('user_id');
        $query = DB::table('users_networks')
            ->select('id', 'user_id', 'network_id', 'fpp', 'type', 'status', 'network', 'active', 'expire_date', 'start_date', 'created_at', 'updated_at')
            ->where('network_id', $id)
            ->where('user_id', $users)
            ->get();
        return $query[0];
    }

    public function resetPassword(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $user =  User::where('id', $user_id)->first();
            $network_Id = $request->header('X-Network-Id');


            $subject = DB::table('email_templates')->where('id', 3)->value('subject'); // send email
            $template = DB::table('email_templates')->where('id', 3)->value('body');  // send email
            $template = str_replace(
                [
                    '{{email}}',
                    '{{password}}',
                ],
                [
                    $email = $user->email,
                    $password = $user->unhashed_password,
                ],
                $template
            );

            Mail::to($user->email)->queue(new ResetPasswordUserMail($template, $subject));
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_EMAIL_SENDING));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage(), 401);
        }
    }

    public function emailApproved(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $network_Id = $request->header('X-Network-Id');
            $user =  User::where('id', $user_id)->first();
            $network =  Network::where('id', $network_Id)->value('domain');

            $subject = DB::table('email_templates')->where('id', 2)->value('subject'); // send email
            $template = DB::table('email_templates')->where('id', 2)->value('body');  // send email

            $template = str_replace(
                [
                    '{{website_button}}',
                    '{{email}}',
                    '{{password}}',
                ],
                [
                    $website_button = '<a href="https://' . $network . '/login" class="es-button" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#FFFFFF;font-size:18px;border-style:solid;border-color:#31CB4B;border-width:10px 20px 10px 20px;display:inline-block;background:#31CB4B;border-radius:5px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center">LOGIN HERE</a>',
                    $email = $user->email,
                    $password = $user->unhashed_password,
                ],
                $template
            );
            Mail::to($user->email)->queue(new ApprovedUser($template, $subject));

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_EMAIL_SENDING));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage(), 401);
        }
    }

    public function getDashUser()
    {
        try {
            $user = auth()->user();
            return response()->json([
                'data' =>  new UserAuthResource($user)
            ]);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage(), 401);
        }
    }

    public function showMember($wsaId)
    {
        try {
            $data = User::where('wsa_id', $wsaId)->first();
            $user =  new UserAuthResource($data);
            return $user->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function log()
    {
        activity()->log("sadas");
        return  Activity::all()->last();
    }

    public function getUsers(Request $request)
    {
        try {
            $type = $request->input('type');
            $cacheKey = 'users_' . $type;

            $users = Cache::remember($cacheKey, now()->addMinutes(1), function () use ($type) {
                return match ($type) {
                    'all' => User::with('country')->get(),
                    'pending' => User::with('country')->whereHas('networks', function ($query) {
                        $query->where('status', 'pending');
                    })->get(),
                    'members' => User::with('country')->whereDoesntHave('networks', function ($query) {
                        $query->where('status', 'pending');
                    })->get(),
                    default => null,
                };
            });

            $user = GetMemberResource::collection($users);
            return $user->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
