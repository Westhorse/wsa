<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\CouponRequest;
use App\Http\Resources\Conference\CouponResource;
use App\Http\Resources\Conference\ShowCouponResource;
use App\Interfaces\CouponRepositoryInterface;
use App\Models\Coupon;
use Exception;
use Illuminate\Http\Request;


class CouponController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(CouponRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            return CouponResource::collection($this->crudRepository->allEvent([], [], ['*']));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic(Request $request)
    {
        try {
            $conferenceId = $request->header('X-Conference-Id');
            $CouponData = Coupon::where('active', 1)->where('conference_id',$conferenceId)->get();
            $Coupon = CouponResource::collection($CouponData);
            return $Coupon->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(CouponRequest $request)
    {
        try {
            $this->crudRepository->create($request->validated());
            return JsonResponse::success();
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param Coupon $coupon
     * @return \Illuminate\Http\JsonResponse|ShowCouponResource
     */

    public function show(Coupon $coupon)
    {
        try {
            $couponResource = new ShowCouponResource($coupon);
            return $couponResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param CouponRequest $request
     * @param Coupon $coupon
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CouponRequest $request, Coupon $coupon): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $coupon->id);
            activity()->performedOn($coupon)->withProperties(['attributes' => $coupon])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('coupons', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 222 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Coupon::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Coupon::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Coupon::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
