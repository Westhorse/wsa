<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Common\CountryRequest;
use App\Http\Resources\Common\CountryResource;
use App\Interfaces\CountryRepositoryInterface;
use App\Models\Country;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CountryController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(CountryRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index(): \Illuminate\Http\JsonResponse|AnonymousResourceCollection
    {
        try {
            $countries = CountryResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'key', 'code', 'order_id', 'active', 'created_at', 'updated_at', 'deleted_at']
            ));
            return $countries->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Country $country): CountryResource|\Illuminate\Http\JsonResponse
    {
        try {
            $country = new CountryResource($country);
            return $country->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(CountryRequest $request)
    {
        try {
            $country = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $country);
            }
            return new CountryResource($country);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function update(CountryRequest $request, Country $country)
    {
        try {
            $this->crudRepository->update($request->validated(), $country->id);
            if (request('image') !== null) {
                $data = Country::find($country->id);
                $this->crudRepository->AddMediaCollection('image', $data);
            }
            activity()->performedOn($country)->withProperties(['attributes' => $country])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('countries', $request['items']);
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
            $this->crudRepository->restoreItem(Country::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecordsFinial(Country::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
