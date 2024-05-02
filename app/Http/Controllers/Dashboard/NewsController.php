<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\NewsRequest;
use App\Http\Resources\Dashboard\NewsResource;
use App\Interfaces\NewsRepositoryInterface;
use App\Models\News;
use Exception;
use Illuminate\Http\Request;
use Str;

class NewsController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(NewsRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $news = NewsResource::collection($this->crudRepository->all(
                [],
                [],
                [
                    'id', 'name',
                    'featured',
                    'publish_date',
                    'short_des',
                    'slug', 'order_id', 'active', 'created_at'
                ]
            ));
            return $news->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(NewsRequest $request)
    {
        try {
            $data = $request->validated();
            $slugOptions = getSlugOptions();
            $data['slug'] = Str::slug($data['name'], $slugOptions->slugSeparator, $slugOptions->slugLanguage);
    
            $model = $this->crudRepository->create($data);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
    
            return new NewsResource($model);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] == 1062) {
                return JsonResponse::respondUniqueError();
            } else {
                return JsonResponse::respondError($e->getMessage());
            }
        }
    }
    

    public function show(News $news)
    {
        try {
            $news = new NewsResource($news);
            return $news->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function showPublic($slug)
    {
        try {
            $data = News::where('slug', $slug)->first();
            $news = new NewsResource($data);
            return $news->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function update(NewsRequest $request, News $news): \Illuminate\Http\JsonResponse
    {
        try {
            $content = $this->crudRepository->find($news->id);
            $content->update($request->only([
                'name',
                'active',
                'order_id',
                'publish_date',
                'short_des',
                'des',
                'featured',
            ]));
            if (request('image') !== null) {
                $image = $this->crudRepository->AddMediaCollection('image', $content);
            }
            activity()->performedOn($news)->withProperties(['attributes' => $news])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('news', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 1 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch(Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(News::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = News::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(News::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
