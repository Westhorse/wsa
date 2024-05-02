<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\NetworkRequest;
use App\Http\Resources\Dashboard\NetworkResource;
use App\Http\Resources\Dashboard\NetworkSettingResource;
use App\Interfaces\NetworkRepositoryInterface;
use App\Models\Network;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NetworkController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(NetworkRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index(): \Illuminate\Http\JsonResponse|AnonymousResourceCollection
    {
        try {
            $network = NetworkResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name',  'slug', 'domain', 'order_id', 'active', 'collection']
            ));
            return $network->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Network $network): \Illuminate\Http\JsonResponse|NetworkResource
    {
        try {
            $network = new NetworkResource($network);
            return $network->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(NetworkRequest $request): \Illuminate\Http\JsonResponse|NetworkResource
    {
        try {
            $network = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $network);
            }
            return new NetworkResource($network);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(NetworkRequest $request, Network $network): \Illuminate\Http\JsonResponse
    {

            $updatedNetwork = $this->crudRepository->update($request->validated(), $network->id);
            if (request('image') !== null) {
                $network = Network::find($network->id);
                $image = $this->crudRepository->AddMediaCollection('image', $network);
            }
            activity()->performedOn($network)->withProperties(['attributes' => $network])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));

    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('networks', $request['items']);
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
            $this->crudRepository->restoreItem(Network::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Network::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Network::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function getNetworkData(): \Illuminate\Http\JsonResponse|NetworkResource
    {

        try {
            $networkId = request()->header('X-Network-ID');
            $network = Network::where('id', $networkId)->first();

            if (!$network) {
                // Handle the case when the network is not found
                return JsonResponse::respondError('Network not found');
            }

            return new NetworkResource($network);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function getNetworkByDomain(Request $request)
    {
        try {
            $domain = $request->query('domain');

            if (!$domain) {
                return JsonResponse::respondError('Domain parameter is missing');
            }

            $network = Network::where('domain', $domain)->first();

            if (!$network) {
                return JsonResponse::respondError('Network not found');
            }

            return new NetworkSettingResource($network);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function getDefaultNetwork() {
        $currentNetworkId = request()->header('X-Network-ID');
        if($currentNetworkId) {
            $currentNetwork = Network::where('id', $currentNetworkId)->firstOrFail();
            if($currentNetwork) {
                return new NetworkResource($currentNetwork);
            }
        }
        $defaultNetwork = Network::first();
        return new NetworkResource($defaultNetwork);
    }
}

