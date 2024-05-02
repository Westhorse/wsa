<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\SliderRequest;
use App\Http\Resources\Dashboard\SliderResource;
use App\Interfaces\SliderRepositoryInterface;
use App\Models\Slider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SliderController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(SliderRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $slider = SliderResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'sub_title', 'order_id', 'active', 'network_id', 'created_at', 'updated_at','bg_url','link_type','deleted_at',
                    "des",
                    "title",
                    "button_one_active",
                    "button_text_one",
                    "button_style_one",
                    "button_route_one",
                    "button_icon_one",
                    "button_link_type_one",
                    "button_two_active",
                    "button_text_two",
                    "button_style_two",
                    "button_route_two",
                    "button_icon_two",
                    "button_link_type_two"],
                true, false
            ));
            return $slider->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Slider $slider): SliderResource|\Illuminate\Http\JsonResponse
    {
        try {
            $slider = new SliderResource($slider);
            return $slider->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(SliderRequest $request)
    {
        try {
            $slider = $this->crudRepository->create($request->validated());
            DB::table('sliders')->where('id', $slider->id)->update(['network_id' => $request->header('X-Network-ID')]);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $slider);
            }
            return new SliderResource($slider);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(SliderRequest $request, Slider $slider)
    {
        try {
            $networkSlider= $this->crudRepository->update($request->validated(), $slider->id);
            if (request('image') !== null) {
                $network = Slider::find($slider->id);
                $image = $this->crudRepository->AddMediaCollection('image', $network);
            }
            activity()->performedOn($slider)->withProperties(['attributes' => $slider])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('sliders', $request['items']);
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
            $this->crudRepository->restoreItem(Slider::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Slider::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Slider::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
