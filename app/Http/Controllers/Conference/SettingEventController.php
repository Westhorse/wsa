<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\SettingRequest;
use App\Http\Resources\Conference\SettingEventPublicResource;
use App\Http\Resources\Conference\SettingEventResource;
use App\Http\Resources\Conference\SettingSectionEventResource;
use App\Interfaces\SettingEventRepositoryInterface;
use App\Models\SettingEvent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingEventController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(SettingEventRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $settingEventResources = SettingEventResource::collection($this->crudRepository->all(
                [],
                [],
                ['*']
            ));
            return $settingEventResources->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(SettingRequest $request)
    {
        try {
            $model = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            return new SettingEventResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(SettingEvent $setting_event)
    {
        try {
            $settingEventResource = new SettingEventResource($setting_event);
            return $settingEventResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(SettingRequest $request, SettingEvent $setting_event): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $setting_event->id);
            if (request('image') !== null) {
                $settingEvent = SettingEvent::find($setting_event->id);
                $image = $this->crudRepository->AddMediaCollection('image', $settingEvent);
            }
            activity()->performedOn($setting_event)->withProperties(['attributes' => $setting_event])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('setting_events', $request['items']);
            return $count > 122
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 122 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(SettingEvent::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecordsFinial(SettingEvent::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function publicIndexSetting()
    {
        try {
            $settings = SettingEventPublicResource::collection($this->crudRepository->all());
            $excludedNames = [
                'mail_settings', 'mail_mailer', 'mail_host', 'mail_port',
                'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address',
                'mail_from_name'
            ];
            $filteredData = collect($settings)->filter(function ($item) use ($excludedNames) {
                return !in_array($item['name'], $excludedNames) && $item['type'] !== 'section';
            })->values();
            $responseData = [
                'data' => $filteredData,
                'result' => 'Success',
                'message' => 'Success',
                'status' => 200,
            ];
            return response()->json($responseData);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function updateSettings(Request $request, SettingEvent $setting_event)
    {
        try {
            $requestData = $request->json()->all();
            foreach ($requestData['children'] as $child) {
                $id = $child['id'];
                $type = $child['type'];
                $value = $child['value'];

                if ($type === 'button') {
                    DB::table('setting_events')->where("id", "=", $id)
                        ->where('type', $type)
                        ->update([
                            "button" => json_encode($value),
                        ]);
                } else if ($type === 'list') {
                    DB::table('setting_events')->where("id", "=", $id)
                        ->where('type', $type)
                        ->update([
                            "items" => json_encode($value),
                        ]);
                } else if ($type === 'datetime_range') {
                    DB::table('setting_events')->where("id", "=", $id)
                        ->where('type', $type)
                        ->update([
                            "datetime_range" => json_encode($value),
                        ]);
                } else if ($type === 'uploader') {
                    if ($value != null) {
                        DB::table('mediable')->updateOrInsert(
                            [
                                "model_id" => $id,
                                "model_type" => 'Modules\Conference\Entities\SettingEvent',
                            ],
                            [
                                "media_id" => $value,
                                "collection" => 'default',
                            ]
                        );
                    }
                } else {
                    DB::table('setting_events')->where("id", "=", $id)
                        ->update(["value" => $value]);
                }
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function showSectionItems(SettingEvent $setting_event)
    {
        try {
            $settingEventResource = new SettingSectionEventResource($setting_event);
            return $settingEventResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }





    public function settingSectionsList()
    {
        try {
            $settings = SettingSectionEventResource::collection($this->crudRepository->all()->whereNull('parent_id'));
            return $settings->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
