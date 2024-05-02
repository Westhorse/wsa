<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\SpouseRequest;
use App\Http\Resources\Conference\SpouseDashboardResource;
use App\Http\Resources\Conference\SpouseOrderResource;
use App\Interfaces\SpouseRepositoryInterface;
use App\Models\Delegate;
use App\Repositories\SpouseRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardSpouseController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(SpouseRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {

                $delegateRepository = new SpouseRepository(new Delegate());
                $delegates = SpouseDashboardResource::collection($delegateRepository->allSpouse([
                    'user',
                    'tshirt_size',
                    'phoneKey',
                    'cellKey',
                    'dietaries'
                ], [], ['*']));

            return $delegates;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function show(delegate $spouse)
    {
        try {

                $cachedSpouse = new SpouseOrderResource($spouse);

            return $cachedSpouse->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(SpouseRequest $request, Delegate $spouse): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $spouse->id);
            DB::table('delegates')->where('id', $spouse->id)->update(['type' =>$request['type']]);

            if ($request->dietaries) {
                $spouse->dietaries()->sync($request->dietaries, 'dietary_id');
            }
            if (request('image') !== null) {
                $spouse = Delegate::find($spouse->id);
                $image = $this->crudRepository->AddMediaCollection('image', $spouse);
            }
            activity()->performedOn($spouse)->withProperties(['attributes' => $spouse])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('delegates', $request['items']);
            return $count > 122
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 122 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


}
