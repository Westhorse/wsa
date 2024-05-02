<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Common\ServiceRequest;
use App\Http\Resources\Common\ServiceResource;
use App\Interfaces\ServiceRepositoryInterface;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Str;

class ServiceController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(ServiceRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $service = ServiceResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'slug', 'order_id', 'active']
            ));
            return $service->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Service $service)
    {
        try {
            $service = new ServiceResource($service);
            return $service->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(ServiceRequest $request)
    {
        try {
            $data = $request->validated();
            $slugOptions = getSlugOptions();
            $data['slug'] = Str::slug($data['name'], $slugOptions->slugSeparator, $slugOptions->slugLanguage);
            $model = $this->crudRepository->create($data);

            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            $models   = new ServiceResource($model);
            return $models;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(ServiceRequest $request, Service $service)
    {
        try {
            $this->crudRepository->update($request->validated(), $service->id);
            activity()->performedOn($service)->withProperties(['attributes' => $service])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY), null);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('services', $request['items']);
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
            $this->crudRepository->restoreItem(Service::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecordsFinial(Service::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
