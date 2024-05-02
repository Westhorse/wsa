<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\TradeReferenceRequest;
use App\Http\Resources\Common\TradeReferenceResource;
use App\Interfaces\TradeReferenceRepositoryInterface;
use App\Models\TradeReference;
use Exception;
use Illuminate\Http\Request;

class TradeReferenceController extends Controller
{
    protected mixed $crudRepository;

    public function __construct(TradeReferenceRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $tradeReference = TradeReferenceResource::collection($this->crudRepository->all());
            return $tradeReference->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(TradeReference $trade_reference)
    {
        try {
            $tradeReference = new TradeReferenceResource($trade_reference);
            return $tradeReference->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(TradeReferenceRequest $request)
    {
        try {
            $tradeReference = $this->crudRepository->create($request->validated());

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(TradeReferenceRequest $request, TradeReference $trade_reference)
    {
        try {
            $this->crudRepository->update($request->validated(), $trade_reference->id);
            activity()->performedOn($trade_reference)->withProperties(['attributes' => $trade_reference])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('trade_references', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 222 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        try {
            $this->crudRepository->restoreItem(TradeReference::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request)
    {
        try {
            $exists = TradeReference::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(TradeReference::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
