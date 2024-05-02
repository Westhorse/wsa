<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Dashboard\GroupRequest;
use App\Http\Resources\Dashboard\GroupResource;
use App\Interfaces\GroupRepositoryInterface;
use App\Models\Group;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(GroupRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $group = GroupResource::collection($this->crudRepository->all());
            return $group->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(GroupRequest $request)
    {
        try {
            $model = $this->crudRepository->create($request->validated());
            return new GroupResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Group $group)
    {
        try {
            $group = new GroupResource($group);
            return $group->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function update(GroupRequest $request, Group $group)
    {
        try {
            $data = $request->only('name', 'active');
            $this->crudRepository->update($data, $group->id);

            if ($request->has('companies')) {
                $requestBody = json_decode($request->getContent(), true);
                $companyData = $requestBody['companies'];

                // Get the existing user IDs associated with the group
                $existingUserIds = $group->users()->pluck('id')->toArray();

                foreach ($companyData as $company) {
                    $companyId = $company['id_company'];
                    $type = $company['type_company'];

                    // Update or add the user to the group
                    DB::table('users')
                        ->where('id', $companyId)
                        ->update([
                            'group_id' => $group->id,
                            'type_company' => $type
                        ]);

                    // Remove the user ID from the existingUserIds array
                    $existingUserIds = array_diff($existingUserIds, [$companyId]);
                }

                // Remove users from the group that were not included in the request
                DB::table('users')
                    ->whereIn('id', $existingUserIds)
                    ->update([
                        'group_id' => null,
                        'type_company' => null
                    ]);
            }
            activity()->performedOn($group)->withProperties(['attributes' => $group])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('groups', $request['items']);
            return $count > 122
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
            $this->crudRepository->restoreItem(Group::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Group::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Group::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
