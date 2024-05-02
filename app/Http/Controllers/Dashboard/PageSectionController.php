<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\PageSectionRequest;
use App\Http\Resources\Dashboard\PageSectionResource;
use App\Interfaces\PageSectionRepositoryInterface;
use App\Models\PageSection;
use Exception;
use Illuminate\Http\Request;
use Str;

class PageSectionController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(PageSectionRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $page_section = PageSectionResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'type', 'title', 'order_id', 'active']
            ));
            return $page_section->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(PageSection $page_section)
    {
        try {
            $page_section = new PageSectionResource($page_section);
            return $page_section->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(PageSectionRequest $request)
    {
        try {
            $networkSlug = getNetworkSlug();
            $data = $request->validated();
            $slugOptions = getSlugOptionTitle();
            $data['slug'] = Str::slug($data['title'], $slugOptions->slugSeparator, $slugOptions->slugLanguage);

            $model = $this->crudRepository->create($data);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model, $networkSlug);
            }
            $models   = new PageSectionResource($model);
            return $models;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(PageSectionRequest $request, PageSection $page_section)
    {
        try {
            $networkSlug = getNetworkSlug();
            $pageSection =  $this->crudRepository->update($request->validated(), $page_section->id);
            if (request('image') !== null) {
                $network = PageSection::find($page_section->id);
                $image = $this->crudRepository->AddMediaCollection('image', $network, $networkSlug);
            }
            activity()->performedOn($page_section)->withProperties(['attributes' => $page_section])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY), null);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('page_sections', $request['items']);
            return $count > 122
                ? JsonResponse::respondError(trans("responses.msg_multi_resources_cannot_deleted"))
                : ($count == 122 ? JsonResponse::respondError(trans("responses.msg_cannot_deleted"))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(PageSection::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = PageSection::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(PageSection::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
