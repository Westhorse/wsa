<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Common\ContinentRequest;
use App\Http\Resources\Common\ContinentResource;
use App\Interfaces\ContinentRepositoryInterface;
use App\Models\Continent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContinentController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(ContinentRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $continents = ContinentResource::collection($this->crudRepository->all(
                ['countries','users','voters','members'],
                [],
                ['id', 'name', 'order_id', 'active', 'created_at', 'updated_at', 'deleted_at']
            ));
            return $continents->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Continent $continent)
    {
        try {
            $continent = new ContinentResource($continent);
            return $continent->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(ContinentRequest $request)
    {
        try {
            $continents = $this->crudRepository->create($request->validated());
            return new ContinentResource($continents);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(ContinentRequest $request, Continent $continent)
    {
        try {
            $data = $request->only('name', 'active', 'order_id');
            $this->crudRepository->update($data, $continent->id);
            if ($request->has('countries')) {
                DB::table('countries')->whereIn('id', request('countries'))->update(['continent_id' => $continent->id]);
            }
            activity()->performedOn($continent)->withProperties(['attributes' => $continent])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('continents', $request['items']);
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
            $this->crudRepository->restoreItem(Continent::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Continent::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Continent::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


}
