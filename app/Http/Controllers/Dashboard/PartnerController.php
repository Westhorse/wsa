<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\PartnerRequest;
use App\Http\Resources\Dashboard\PartnerResource;
use App\Interfaces\PartnerRepositoryInterface;
use App\Models\Partner;
use Exception;
use Illuminate\Http\Request;

class PartnerController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(PartnerRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $partner = PartnerResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name','order_id', 'active', 'link']
            ));
            return $partner->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Partner $partner): PartnerResource|\Illuminate\Http\JsonResponse
    {
        try {
            $partner = new PartnerResource($partner);
            return $partner->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(PartnerRequest $request): PartnerResource|\Illuminate\Http\JsonResponse
    {
        try {
            $partner = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $partner);
            }
            return new PartnerResource($partner);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(PartnerRequest $request, Partner $partner): \Illuminate\Http\JsonResponse
    {

            $updatedNetwork = $this->crudRepository->update($request->validated(), $partner->id);
            if (request('image') !== null) {
                $network = Partner::find($partner->id);
                $image = $this->crudRepository->AddMediaCollection('image', $network);
            }
            activity()->performedOn($partner)->withProperties(['attributes' => $partner])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));

    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('partners', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 1 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Partner::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Partner::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Partner::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
