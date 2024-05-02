<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\DelegateRequest;
use App\Http\Resources\Conference\DelegateDashboardResource;
use App\Http\Resources\Conference\DelegateResource;
use App\Interfaces\DelegateRepositoryInterface;
use App\Models\Delegate;
use App\Repositories\DelegateRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardDelegateController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(DelegateRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $delegateRepository = new DelegateRepository(new Delegate());
            return DelegateResource::collection($delegateRepository->allDelegate([
                'user',
                'tshirt_size',
                'phoneKey',
                'cellKey',
                'dietaries'
            ]));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Delegate $delegate)
    {
        try {
            $delegateResource = new DelegateDashboardResource($delegate);
            return $delegateResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function update(DelegateRequest $request, Delegate $delegate): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $delegate->id);
            $user = Delegate::find($delegate->id);
            DB::table('delegates')->where('id', $user->id)->update(['password' => Hash::make($request['unhashed_password']), 'unhashed_password' => $request['unhashed_password'],]);

            if ($request->dietaries) {
                $user->dietaries()->sync($request->dietaries, 'dietary_id');
            }
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $user);
            }
            if (request('bc') !== null) {
                $this->crudRepository->AddMediaCollection('bc', $user, 'bc');
            }
            activity()->performedOn($user)->withProperties(['attributes' => $user])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('delegates', $request['items']);
            return $count > 122
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 122 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
