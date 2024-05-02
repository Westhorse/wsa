<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\PageRequest;
use App\Http\Resources\Dashboard\PageResource;
use App\Http\Resources\Dashboard\PublicPageResource;
use App\Interfaces\PageRepositoryInterface;
use App\Models\Page;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class PageController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(PageRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $page = PageResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'slug', 'order_id', 'active', 'network_id'],
                true,
                false
            ));
            return $page->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Page $page): \Illuminate\Http\JsonResponse|PageResource
    {
        try {
            $page = new PageResource($page);
            return $page->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(PageRequest $request)
    {
        try {
            $data = $request->validated();
            $slugOptions = getSlugOptions();
            $data['slug'] = Str::slug($data['name'], $slugOptions->slugSeparator, $slugOptions->slugLanguage);

            $model = $this->crudRepository->create($data, $request->except('pageSections'));
            DB::table('pages')->where('id', $model->id)->update(['network_id' => $request->header('X-Network-ID')]);
            $model->pageSections()->sync($request->pageSections);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            return new PageResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(PageRequest $request, Page $page): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->only('name', 'slug', 'order_id', 'active', 'des');
            $this->crudRepository->update($data, $page->id);

            if ($request->has('pageSections')) {
                $pageSectionIds = customSyncItem($request->pageSections, 'page_section_id');
                $page->pageSections()->sync($pageSectionIds);
            } else {
                $page->pageSections()->detach();
            }

            $updatedData = new PageResource($page->refresh());
            activity()->performedOn($page)->withProperties(['attributes' => $page])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY), $updatedData);
        } catch (Exception $e) {
            return JsonResponse::respondError('Error: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('pages', $request['items']);
            return $count > 122
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 122 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        try {
            $this->crudRepository->restoreItem(Page::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request)
    {
        try {
            $exists = Page::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $deletedCount = $this->crudRepository->deleteRecordsFinial(Page::class, $request['items']);
            DB::table('pages_page_sections')->whereIn('page_id', $request['items'])->delete();
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    // Get Page By Slug
    public function publicShow($slug, Request $request)
    {
        try {
            $networkId = $request->header('X-Network-Id');
            $new = Page::with('pageSections')->with('network')->where('slug', $slug)
                ->where('active', true)
                ->where('network_id', $networkId)
                ->first();

            if (!$new) {
                return response()->json(['message' => 'Page not found'], 404);
            }
            return new PublicPageResource($new);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
