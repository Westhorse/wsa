<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\EventPageRequest;
use App\Http\Resources\Conference\EventPageResource;
use App\Http\Resources\Conference\PublicEventPageResource;
use App\Interfaces\EventPageRepositoryInterface;
use App\Models\EventPage;
use Exception;
use Illuminate\Http\Request;
use Str;

class EventPageController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(EventPageRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {

            $eventPageResources = EventPageResource::collection($this->crudRepository->all(
                ['eventSectionPages'],
                [],
                ['id', 'title', 'slug', 'name', 'keywords', 'description', 'order_id', 'active']
            ));

            return $eventPageResources->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function indexPublic(Request $request)
    {
        try {
            $EventPages = EventPage::where('active', 1)->get();
            $EventPage = EventPageResource::collection($EventPages);
            return $EventPage->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(EventPageRequest $request)
    {
        try {
            $data = $request->validated();
            $slugOptions = getSlugOptions();
            $data['slug'] = Str::slug($data['name'], $slugOptions->slugSeparator, $slugOptions->slugLanguage);

            $model = $this->crudRepository->create($data);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(EventPage $event_page)
    {
        try {

            $eventPageResource = new EventPageResource($event_page);

            return $eventPageResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function showSlug($slug, Request $request)
    {
        try {
            $event_page = EventPage::where('slug', $slug)->first();
            if ($event_page) {
                $eventPageResource = new EventPageResource($event_page);
            } else {
                return JsonResponse::respondError('Event Page not found');
            }
            return $eventPageResource;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function publicShowSlug($slug, Request $request)
    {
        try {
            $event_page =  EventPage::where('slug', $slug)->where('active', true)->first();
            return new PublicEventPageResource($event_page);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function update(EventPageRequest $request, EventPage $event_page): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $event_page->id);
            if (request('image') !== null) {
                $eventPage = EventPage::find($event_page->id);
                $image = $this->crudRepository->AddMediaCollection('image', $eventPage);
            }
            activity()->performedOn($event_page)->withProperties(['attributes' => $event_page])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('event_pages', $request['items']);
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
            $this->crudRepository->restoreItem(EventPage::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = EventPage::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(EventPage::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
