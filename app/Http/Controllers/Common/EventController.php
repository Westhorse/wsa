<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Common\EventRequest;
use App\Http\Resources\Common\EventResource;
use App\Interfaces\EventRepositoryInterface;
use App\Models\Event;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class EventController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(EventRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $event = EventResource::collection($this->crudRepository->all(
                ['country'],
                [],
                ['*'],
                false
                , false
            ));
            return $event->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(EventRequest $request)
    {
        try {
            $data = $request->validated();
            $slugOptions = getSlugOptionTitle();
            $data['slug'] = Str::slug($data['title'], $slugOptions->slugSeparator, $slugOptions->slugLanguage);
            $model = $this->crudRepository->create($data);
            DB::table('events')->where('id', $model->id)->update(['network_id' => $request->header('X-Network-ID')]);

            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model, 'image');
            }
            if (request('gallery') !== null) {
                $this->crudRepository->AddMediaCollectionArray('gallery', $model, 'gallery');
            }
            return new EventResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Event $event): \Illuminate\Http\JsonResponse|EventResource
    {
        try {
            $event = new EventResource($event);
            return $event->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function showPublic($slug)
    {
        try {
            $data = Event::where('slug', $slug)->first();
            $news = new EventResource($data);
            return $news->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function update(EventRequest $request, Event $event): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->crudRepository->update($request->validated(), $event->id);
            if (request('image') !== null) {
                $network = Event::find($event->id);
                $image = $this->crudRepository->AddMediaCollection('image', $network, 'image');
            }
            if (request('gallery') !== null) {
                $network = Event::find($event->id);
                $image = $this->crudRepository->AddMediaCollectionArray('gallery', $network, 'gallery');
            }
            activity()->performedOn($event)->withProperties(['attributes' => $event])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('events', $request['items']);
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
            $this->crudRepository->restoreItem(Event::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Event::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Event::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
