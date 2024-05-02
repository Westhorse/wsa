<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\BenefitRequest;
use App\Http\Resources\Dashboard\BenefitResource;
use App\Interfaces\BenefitRepositoryInterface;
use App\Models\Benefit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class BenefitController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(BenefitRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $benefit = BenefitResource::collection($this->crudRepository->all(
                ['networks', 'contents'],
                [],
                ['id', 'name', 'slug','order_id', 'icon', 'short_des']
            ));
            return $benefit->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Benefit $benefit): \Illuminate\Http\JsonResponse|BenefitResource
    {
        try {
            $benefit = new BenefitResource($benefit);
            return $benefit->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function publicShow($slug)
    {
        try {
            $benefit = Benefit::with('networks')->with('contents')->where('slug', $slug)->first();
            $benefit = new BenefitResource($benefit);
            return $benefit->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(BenefitRequest $request)
    {
        try {
            $data = $request->validated();
            $slugOptions = getSlugOptions();
            $data['slug'] = Str::slug($data['name'], $slugOptions->slugSeparator, $slugOptions->slugLanguage);

            $networkSlug = getNetworkSlug();
            $benefit = $this->crudRepository->create($data);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $benefit , $networkSlug);
            }
            if ($request->networks) $benefit->networks()->sync($request->networks);
            return $benefit;
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] == 1062) {
                return JsonResponse::respondUniqueError();
            } else {
                return JsonResponse::respondError($e->getMessage());
            }
        }
    }



    public function update(BenefitRequest $request, Benefit $benefit)
    {
        try {
            $networkSlug = getNetworkSlug();
            $data = $request->only('name','icon','order_id','short_des');
            $this->crudRepository->update($data, $benefit->id);
            if (request('image') !== null) {
                $data = Benefit::find($benefit->id);
                $this->crudRepository->AddMediaCollection('image', $data, $networkSlug);
            }
            if ($request->networks) $benefit->networks()->sync(customSync($request->networks, 'network_id'));

            activity()->performedOn($benefit)->withProperties(['attributes' => $benefit])->log('update');

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('benefits', $request['items'] , ['networks']);
            return $count > 1
            ? JsonResponse::respondError(trans("responses.msg_multi_resources_cannot_deleted"))
            : ($count == 222 ? JsonResponse::respondError(trans("responses.msg_cannot_deleted"))
            : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch(Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Benefit::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Benefit::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Benefit::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
