<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Common\FaqRequest;
use App\Http\Resources\Common\FaqResource;
use App\Interfaces\FaqRepositoryInterface;
use App\Models\Faq;
use Exception;
use Illuminate\Http\Request;

class FaqController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(FaqRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $faq = FaqResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'order_id', 'des']
            ));
            return $faq->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Faq $faq)
    {
        try {
            $faq = new FaqResource($faq);
            return $faq->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(FaqRequest $request)
    {
        try {
            $faq = $this->crudRepository->create($request->validated());
            if ($request->networks) $faq->networks()->sync($request->networks);
            return new FaqResource($faq);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function update(FaqRequest $request, Faq $faq)
    {
        try {
            $data = $request->only('name','order_id','des');
            $this->crudRepository->update($data, $faq->id);
            if ($request->networks) $faq->networks()->sync(customSync($request->networks, 'network_id'));
            activity()->performedOn($faq)->withProperties(['attributes' => $faq])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('faqs', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count ==222 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch(Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Faq::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Faq::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Faq::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
