<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\VisitRequest;
use App\Http\Resources\Conference\VisitResource;
use App\Interfaces\VisitRepositoryInterface;
use App\Models\Delegate;
use App\Models\User;
use App\Models\Visit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(VisitRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {

                $Visit = VisitResource::collection($this->crudRepository->allVisit([], [], ['*']));

            return $Visit->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function show(Visit $visit)
    {
        try {
            $visit = new VisitResource($visit);
            return $visit->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function storeAuth(VisitRequest $request)
    {
        try {
            $user = Auth::user();
            $modelType = $user instanceof Delegate ? Delegate::class : User::class;
            $personId = $user->id;
            $visitData = $request->validated();
            $visitData['model_type'] = $modelType;
            $visitData['person_id'] = $personId;
            $this->crudRepository->create($visitData);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function storeGuest(VisitRequest $request)
    {
        try {
            $visitData = $request->validated();
            $this->crudRepository->create($visitData);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('visits', $request['items']);
            return $count > 122
                ? JsonResponse::respondError(trans("responses.msg_multi_resources_cannot_deleted"))
                : ($count == 122 ? JsonResponse::respondError(trans("responses.msg_cannot_deleted"))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
