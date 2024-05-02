<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Common\IncotermRequest;
use App\Http\Resources\Common\IncotermResource;
use App\Interfaces\IncotermRepositoryInterface;
use App\Models\Incoterm;
use App\Models\Network;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IncotermController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(IncotermRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index(): \Illuminate\Http\JsonResponse|AnonymousResourceCollection
    {
        try {
            $incoterm = IncotermResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'title','des', 'code', 'order_id', 'active', 'deleted_at', 'created_at']
            ));
            return $incoterm->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Incoterm $incoterm): IncotermResource|\Illuminate\Http\JsonResponse
    {
        try {
            $incoterm = new IncotermResource($incoterm);
            return $incoterm->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(IncotermRequest $request): IncotermResource|\Illuminate\Http\JsonResponse
    {
        try {
            $network = Network::where('id', $request->header('X-Network-ID'))->pluck('slug');
            $incoterm = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $incoterm, $network[0]);
            }
            return new IncotermResource($incoterm);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(IncotermRequest $request, Incoterm $incoterm): \Illuminate\Http\JsonResponse
    {
        try {
            $networkId = Network::where('id', $request->header('X-Network-ID'))->pluck('slug');
            $this->crudRepository->update($request->validated(), $incoterm->id);
            if (request('image') !== null) {
                $data = Incoterm::find($incoterm->id);
                $image = $this->crudRepository->AddMediaCollection('image', $data, $networkId[0]);
            }
            activity()->performedOn($incoterm)->withProperties(['attributes' => $incoterm])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('incoterms', $request['items']);
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
            $this->crudRepository->restoreItem(Incoterm::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecordsFinial(Incoterm::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
