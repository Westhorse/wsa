<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\SpouseRequest;
use App\Http\Resources\Conference\SpouseResource;
use App\Interfaces\SpouseRepositoryInterface;
use App\Models\Delegate;
use Exception;
use Illuminate\Http\Request;

class SpouseController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(SpouseRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {

            $spouse = SpouseResource::collection($this->crudRepository->all([], [], ['*']));

            return $spouse->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic(Request $request)
    {
        try {
            $conferenceId = $request->header('X-Conference-Id');
            $Spouses = Delegate::where('conference_id',$conferenceId)->where('type','spouse')->get();
            $Spouse = SpouseResource::collection($Spouses);
            return $Spouse->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(SpouseRequest $request)
    {
        try {
            $model = $this->crudRepository->create($request->validated());
            if ($request->dietaries) $model->dietaries()->sync($request->dietaries);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            return new SpouseResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(delegate $spouse)
    {
        try {

            $cachedSpouse = new SpouseResource($spouse);

            return $cachedSpouse->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(SpouseRequest $request, Delegate $spouse): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $spouse->id);
            if ($request->dietaries) {
                $spouse->dietaries()->sync($request->dietaries, 'dietary_id');
            }
            if (request('image') !== null) {
                $spouse = Delegate::find($spouse->id);
                $image = $this->crudRepository->AddMediaCollection('image', $spouse);
            }
            activity()->performedOn($spouse)->withProperties(['attributes' => $spouse])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('delegates', $request['items']);
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
            $this->crudRepository->restoreItem(Delegate::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecordsFinial(Delegate::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
