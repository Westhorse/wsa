<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\DietaryRequest;
use App\Http\Resources\Conference\DietaryResource;
use App\Interfaces\DietaryRepositoryInterface;
use App\Models\Dietary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DietaryController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(DietaryRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $dietaryResources = DietaryResource::collection($this->crudRepository->all(
                ['dietPersons'],
                [],
                ['id', 'name', 'slug', 'order_id', 'active']
            ));
            return $dietaryResources;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function indexPublic(Request $request)
    {
        try {
            $dietaryResources = Dietary::where('active', 1)->get();
            $dietary = DietaryResource::collection($dietaryResources);
            return $dietary->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(DietaryRequest $request)
    {
        try {
            $data = $request->validated();
            $slugOptions = getSlugOptions();
            $data['slug'] = Str::slug($data['name'], $slugOptions->slugSeparator, $slugOptions->slugLanguage);

            $model = $this->crudRepository->create($data);
            return new DietaryResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param Dietary $dietary
     * @return DietaryResource|\Illuminate\Http\JsonResponse
     */
    public function show(Dietary $dietary)
    {
        try {
            $cacheKey = 'dietary_' . $dietary->id;
            if (cache()->has($cacheKey)) {
                $dietaryResource = cache($cacheKey);
            } else {
                $dietaryResource = new DietaryResource($dietary);
                cache([$cacheKey => $dietaryResource], 5); // تحديث كل 5 ثوانٍ
            }
            return $dietaryResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param DietaryRequest $request
     * @param Dietary $dietary
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(dietaryRequest $request, Dietary $dietary)
    {
        try {
            $this->crudRepository->update($request->validated(), $dietary->id);
            activity()->performedOn($dietary)->withProperties(['attributes' => $dietary])->log('update');
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
            $count = $this->crudRepository->deleteRecords('dietaries', $request['items']);
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
            $this->crudRepository->restoreItem(Dietary::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Dietary::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Dietary::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
