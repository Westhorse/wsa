<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\ReferralRequest;
use App\Http\Resources\Dashboard\ReferralResource;
use App\Interfaces\ReferralRepositoryInterface;
use App\Models\Referral;
use Exception;
use Illuminate\Http\Request;
use Str;

class ReferralController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(ReferralRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $referral = ReferralResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'slug', 'order_id', 'active']
            ));
            return $referral->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Referral $referral)
    {
        try {
            $referral = new ReferralResource($referral);
            return $referral->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(ReferralRequest $request)
    {
        try {
            $data = $request->validated();
            $slugOptions = getSlugOptions();
            $data['slug'] = Str::slug($data['name'], $slugOptions->slugSeparator, $slugOptions->slugLanguage);
            $model = $this->crudRepository->create($data);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            $models = new ReferralResource($model);
            return $models;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function update(ReferralRequest $request, Referral $referral)
    {
        try {
            $this->crudRepository->update($request->validated(), $referral->id);
            activity()->performedOn($referral)->withProperties(['attributes' => $referral])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY), null);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('referrals', $request['items']);
            return $count > 1
            ? JsonResponse::respondError(trans("responses.msg_multi_resources_cannot_deleted"))
            : ($count == 1 ? JsonResponse::respondError(trans("responses.msg_cannot_deleted"))
            : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch(Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Referral::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecordsFinial(Referral::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
