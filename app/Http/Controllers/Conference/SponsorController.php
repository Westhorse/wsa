<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\SponsorRequest;
use App\Http\Resources\Conference\SponsorResource;
use App\Interfaces\SponsorRepositoryInterface;
use App\Models\Sponsor;
use Exception;
use Illuminate\Http\Request;

class SponsorController extends  BaseController
{
    protected mixed $crudRepository;

    public function __construct(SponsorRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $sponsors = SponsorResource::collection($this->crudRepository->allEvent([], [], ['id','name','sponsorship_name','order_id','active']));
            return $sponsors->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic(Request $request)
    {
        try {
            $conferenceId = $request->header('X-Conference-Id');
            $sponsorsData = Sponsor::where('active', 1)
                ->where('conference_id', $conferenceId)
                ->get();
            $sponsors = SponsorResource::collection($sponsorsData);
            return $sponsors->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(SponsorRequest $request)
    {
        try {
            $model = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            return new SponsorResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Sponsor $sponsor)
    {
        try {
            $cachedSponsor = new SponsorResource($sponsor);
            return $cachedSponsor->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(SponsorRequest $request, Sponsor $sponsor): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $sponsor->id);
            if (request('image') !== null) {
                $eventPage = Sponsor::find($sponsor->id);
                $image = $this->crudRepository->AddMediaCollection('image', $eventPage);
            }
            activity()->performedOn($sponsor)->withProperties(['attributes' => $sponsor])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('sponsors', $request['items']);
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
            $this->crudRepository->restoreItem(Sponsor::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecordsFinial(Sponsor::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
