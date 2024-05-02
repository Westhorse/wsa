<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\TshirtSizeRequest;
use App\Http\Resources\Conference\TshirtSizeResource;
use App\Interfaces\TshirtSizeRepositoryInterface;
use App\Models\TshirtSize;
use Exception;
use Illuminate\Http\Request;
use Str;

class TshirtSizeController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(TshirtSizeRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {

                $tshirtSizes = TshirtSizeResource::collection($this->crudRepository->all([], [], ['*']));


            return $tshirtSizes->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function indexPublic()
    {
        try {
            $TshirtSizeResource = TshirtSize::where('active',1)->get();
            $TshirtSize = TshirtSizeResource::collection($TshirtSizeResource);
            return $TshirtSize->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(TshirtSize $tshirt_size)
    {
        try {

                $cachedTshirtSize = new TshirtSizeResource($tshirt_size);

            return $cachedTshirtSize->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(TshirtSizeRequest $request)
    {
        try {
            $data = $request->validated();
            $slugOptions = getSlugOptions();
            $data['slug'] = Str::slug($data['name'], $slugOptions->slugSeparator, $slugOptions->slugLanguage);

            $model = $this->crudRepository->create($data);
            $models   = new TshirtSizeResource($model);
            return $models;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(TshirtSizeRequest $request, TshirtSize $tshirt_size)
    {
        try {
            $this->crudRepository->update($request->validated(), $tshirt_size->id);
            activity()->performedOn($tshirt_size)->withProperties(['attributes' => $tshirt_size])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY), null);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('tshirt_sizes', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans("responses.msg_multi_resources_cannot_deleted"))
                : ($count == 1 ? JsonResponse::respondError(trans("responses.msg_cannot_deleted"))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(TshirtSize::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = TshirtSize::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(TshirtSize::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function getPublicTShirtSizes() {
        try {
            dd("fg");
            $data = TshirtSize::where('active', true)->get();
            $dataResource = TshirtSizeResource::collection($data);
            return JsonResponse::respondSuccess(null, $dataResource);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }

    }
}
