<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\EventSectionPageRequest;
use App\Http\Resources\Conference\EventSectionPageResource;
use App\Interfaces\EventSectionPageRepositoryInterface;
use App\Models\EventSectionPage;
use Exception;
use Illuminate\Http\Request;

class EventSectionPageController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(EventSectionPageRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {

            $eventSectionPageResources = EventSectionPageResource::collection($this->crudRepository->all(
                [],
                [],
                ['*']
            ));

            return $eventSectionPageResources->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic(Request $request)
    {
        try {
            $EventSectionPages = EventSectionPage::where('active', 1)->get();
            $EventSectionPage = EventSectionPageResource::collection($EventSectionPages);
            return $EventSectionPage->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(EventSectionPageRequest $request)
    {
        try {
            $model = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            if (request('gallery') !== null) {
                $this->crudRepository->AddMediaCollectionArray('gallery', $model, 'gallery');
            }
            return new EventSectionPageResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(EventSectionPage $event_section_page)
    {
        try {
            $eventSectionPageResource = new EventSectionPageResource($event_section_page);
            return $eventSectionPageResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(EventSectionPageRequest $request, EventSectionPage $event_section_page): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $event_section_page->id);
            if (request('image') !== null) {
                $eventPage = EventSectionPage::find($event_section_page->id);
                $image = $this->crudRepository->AddMediaCollection('image', $eventPage);
            }
            if (request('gallery') !== null) {
                $network = EventSectionPage::find($event_section_page->id);
                $image = $this->crudRepository->AddMediaCollectionArray('gallery', $network, 'gallery');
            }
            activity()->performedOn($event_section_page)->withProperties(['attributes' => $event_section_page])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('event_section_pages', $request['items']);
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
            $this->crudRepository->restoreItem(EventSectionPage::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = EventSectionPage::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(EventSectionPage::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
