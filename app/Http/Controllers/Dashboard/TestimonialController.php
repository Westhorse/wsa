<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\TestimonialRequest;
use App\Http\Resources\Dashboard\TestimonialResource;
use App\Interfaces\TestimonialRepositoryInterface;
use App\Models\Testimonial;
use Exception;
use Illuminate\Http\Request;

class TestimonialController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(TestimonialRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $testimonial = TestimonialResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'company', 'title', 'country_id', 'show_home', 'order_id', 'active', 'short_des' ]
            ));
            return $testimonial->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(TestimonialRequest $request): TestimonialResource|\Illuminate\Http\JsonResponse
    {
        try {
            $model = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            return new TestimonialResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Testimonial $testimonial)
    {
        try {
            $testimonial = new TestimonialResource($testimonial);
            return $testimonial->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function update(TestimonialRequest $request, Testimonial $testimonial): \Illuminate\Http\JsonResponse
    {
        try {
            $testimonials = $this->crudRepository->update($request->validated(), $testimonial->id);
            if (request('image') !== null) {
                $network = Testimonial::find($testimonial->id);
                $image = $this->crudRepository->AddMediaCollection('image', $network);
            }
            activity()->performedOn($testimonial)->withProperties(['attributes' => $testimonial])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('testimonials', $request['items']);
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
            $this->crudRepository->restoreItem(Testimonial::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Testimonial::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Testimonial::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
