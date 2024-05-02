<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SettingWsaRequest;
use App\Http\Resources\Dashboard\SettingResource;
use App\Interfaces\SettingRepositoryInterface;
use App\Models\Media;
use App\Models\Network;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    protected mixed $crudRepository;

    public function __construct(SettingRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse|AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $settings = SettingResource::collection($this->crudRepository->all());
            return $settings->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**  01146405732
     * Store a newly created resource in storage.
     * @param SettingRequest $request
     * @return Renderable
     */
    public function store(SettingWsaRequest $request)
    {
        $network = Network::where('id', $request->header('X-Network-ID'))->pluck('slug');
        $networkId = $request->header('X-Network-ID');
        try {
            $setting = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $setting, $network[0]);
            }
            DB::table('settings_networks')->insert(['setting_id' => $setting->id, 'value' => $request['value'], 'network_id' => $networkId]);
            return new SettingResource($setting);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Setting $setting)
    {
        try {
            $data = [
                'data' => new SettingResource($setting),
                'result' => 'Success',
                'message' => 'Success',
                'status' => 200
            ];
            return response()->json($data);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param SettingRequest $request
     * @param Setting $setting
     * @return Renderable
     */
    public function update(SettingWsaRequest $request, Setting $setting)
    {
        try {
            $networkSlug = getNetworkSlug();
            $networkId = $request->header('X-Network-ID');
            $data = $request->only('label', 'placeholder', 'des', 'name', 'type', 'data', 'class', 'rules', 'parent_id');
            $this->crudRepository->update($data, $setting->id);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $setting, $networkSlug);
            }
            if (DB::table('settings_networks')->where('network_id', $networkId)->where('setting_id', $setting->id)->first() == null) {
                DB::table('settings_networks')->insert(['setting_id' => $setting->id, 'value' => $request['value'], 'network_id' => $networkId]);
            } else {
                DB::table('settings_networks')->where('setting_id', $setting->id)->where('network_id', $networkId)->update(['value' => $request['value'], 'network_id' => $networkId]);
            }
            activity()->performedOn($setting)->withProperties(['attributes' => $setting])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('settings', $request['items']);
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
            $this->crudRepository->restoreItem(Setting::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Setting::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Setting::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function updateSettings(SettingWsaRequest $request)
    {
        $networkSlug = getNetworkSlug();
        $networkId = $request->header('X-Network-ID');
        $settings = $request->input('children');
        foreach ($settings as $setting) {
            $id = $setting['id'];
            $value = $setting['value'];
            $existingSetting = Setting::find($id);
            $collectionElement = DB::table('mediable')->where("model_id", "=", $id)
                ->where('model_type', 'LIKE', '%' . 'Setting' . '%')
                ->where("collection", "=", $networkSlug)
                ->pluck('collection')->first();
            if (
                $existingSetting and DB::table('mediable')->where("model_id", "=", $id)
                ->where('model_type', 'LIKE', '%' . 'Setting' . '%')->exists() and $collectionElement == $networkSlug
            ) {
                if ($setting['image']) {
                    $newMediaId = $setting['image'];
                    $newMedia = Media::find($newMediaId);
                    DB::table('mediable')->where("model_id", "=", $id)
                        ->where('model_type', 'LIKE', '%' . 'Setting' . '%')
                        ->where('collection', $networkSlug)
                        ->update([
                            "media_id" => $setting['image'],
                        ]);

                    $existingSetting->getMedia('image')->add($newMedia);
                }
                $existingSetting->save();
            } else {
                DB::table('settings_networks')->where("setting_id", "=", $existingSetting->id)->where("network_id", "=", $networkId)
                ->update([
                    "value" => $value,
                ]);
                if ($setting['image'] != null) {

                    DB::table('mediable')->insert([
                        "model_type" => 'Modules\Setting\Entities\Setting',
                        "model_id" => $existingSetting->id,
                        "media_id" => $setting['image'],
                        "collection" => $networkSlug
                    ]);
                }
                if (DB::table('settings_networks')->where('setting_id' , $existingSetting->id)->doesntExist()) {
                    DB::table('settings_networks')->insert([
                        "setting_id" => $existingSetting->id,
                        "network_id" => $networkId,
                        "value" => $value,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Settings updated successfully']);
    }




    public function settingSectionsList()
    {
        try {
            $settings = SettingResource::collection($this->crudRepository->all()->whereNull('parent_id'));
            return $settings->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    //    public function settingPublic(): \Illuminate\Http\JsonResponse|AnonymousResourceCollection
    //    {
    //        try {
    //            $settings = SettingPublicResource::collection($this->crudRepository->all()->whereNotNull('parent_id'));
    //            return $settings->additional(JsonResponse::success());
    //        } catch (Exception $e) {
    //            return JsonResponse::respondError($e->getMessage());
    //        }
    //    }

    public function settingPublic()
    {
        try {
            $settings = SettingResource::collection($this->crudRepository->all());
            $filteredData = collect($settings)->filter(function ($item) {
                return !in_array(
                    $item['name'],
                    [
                        'mail_settings', 'mail_mailer', 'mail_host', 'mail_port',
                        'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address',
                        'mail_from_name'
                    ]
                );
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
}
