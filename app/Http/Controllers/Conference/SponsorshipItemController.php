<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\SponsorshipItemRequest;
use App\Http\Resources\Conference\SponsorshipItemResource;
use App\Interfaces\SponsorshipItemRepositoryInterface;
use App\Models\SponsorshipItem;
use Exception;
use Illuminate\Http\Request;

class SponsorshipItemController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(SponsorshipItemRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {

                $sponsorshipItems = SponsorshipItemResource::collection($this->crudRepository->all([], [], ['*']));

            return $sponsorshipItems->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic()
    {
        try {
            $SponsorshipItems = SponsorshipItem::where('active',1)->get();
            $SponsorshipItem = SponsorshipItemResource::collection($SponsorshipItems);
            return $SponsorshipItem->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(SponsorshipItemRequest $request)
    {
        try {
            $model = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            return new SponsorshipItemResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(SponsorshipItem $sponsorshipItem)
    {
        try {

                $cachedSponsorshipItem = new SponsorshipItemResource($sponsorshipItem);

            return $cachedSponsorshipItem->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(SponsorshipItemRequest $request, SponsorshipItem $sponsorshipItem): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $sponsorshipItem->id);
            if (request('image') !== null) {
                $sponsorshipItem = SponsorshipItem::find($sponsorshipItem->id);
                $image = $this->crudRepository->AddMediaCollection('image', $sponsorshipItem);
            }
            activity()->performedOn($sponsorshipItem)->withProperties(['attributes' => $sponsorshipItem])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('sponsorship_items', $request['items']);
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
            $this->crudRepository->restoreItem(SponsorshipItem::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = SponsorshipItem::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(SponsorshipItem::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
