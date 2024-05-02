<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Common\CertificateRequest;
use App\Http\Resources\Common\CertificateResource;
use App\Interfaces\CertificateRepositoryInterface;
use App\Models\Certificate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificateController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(CertificateRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $certificate = CertificateResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'slug', 'order_id', 'active']
            ));
            return $certificate->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Certificate $certificate)
    {
        try {
            $certificate = new CertificateResource($certificate);
            return $certificate->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(CertificateRequest $request)
    {
        try {
            $data = $request->validated();
            $slugOptions = getSlugOptions();
            $data['slug'] = Str::slug($data['name'], $slugOptions->slugSeparator, $slugOptions->slugLanguage);

            $model = $this->crudRepository->create($data);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            return new CertificateResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(CertificateRequest $request, Certificate $certificate)
    {
        try {
            $this->crudRepository->update($request->validated(), $certificate->id);
            activity()->performedOn($certificate)->withProperties(['attributes' => $certificate])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('certificates', $request['items']);
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
            $this->crudRepository->restoreItem(Certificate::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecordsFinial(Certificate::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
