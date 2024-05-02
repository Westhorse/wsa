<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\ContentRequest;
use App\Http\Resources\Dashboard\ContentResource;
use App\Interfaces\ContentRepositoryInterface;
use App\Models\Content;
use Exception;
use Illuminate\Http\Request;

class ContentController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(ContentRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $content = ContentResource::collection($this->crudRepository->all(
                ['benefit','parent','children','events'],
                [],
                ['id', 'name', 'order_id', 'active']
            ));
            return $content->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Content $content)
    {
        try {
            $content = new ContentResource($content);
            return $content->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(ContentRequest $request)
    {
        try {
            $networkSlug = getNetworkSlug();
            $model = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model, $networkSlug);
            }
            return new ContentResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(ContentRequest $request, Content $content)
    {
        try {
            $networkSlug = getNetworkSlug();
            $this->crudRepository->update($request->validated(), $content->id);
            if (request('image') !== null) {
                $data = Content::find($content->id);
                $this->crudRepository->AddMediaCollection('image', $data, $networkSlug);
            }
            activity()->performedOn($content)->withProperties(['attributes' => $content])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $content = Content::find($id);
            $content->delete();
            return  JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Content::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Content::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Content::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
