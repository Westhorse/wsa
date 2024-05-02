<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\ProgramRequest;
use App\Http\Resources\Conference\ProgramResource;
use App\Http\Resources\Conference\PublicEventDayWithProgramsResource;
use App\Interfaces\ProgramRepositoryInterface;
use App\Models\EventDay;
use App\Models\Program;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends BaseController
{
    protected mixed $crudRepository;

    /**
     * @param ProgramRepositoryInterface $pattern
     */
    public function __construct(ProgramRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }


    public function index()
    {
        try {
            return ProgramResource::collection($this->crudRepository->allEvent(
                ['eventDay'],
                [],
                ['id', 'name', 'from', 'to', 'day_id','active']
            ));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(ProgramRequest $request)
    {
        try {
            $model = $this->crudRepository->create($request->validated());
            DB::table('programs')->where('id', $model->id)->update(['conference_id' => $request->header('X-Conference-Id')]);
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $model);
            }
            return new ProgramResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function show(Program $program)
    {
        try {
            $programResource = new ProgramResource($program);
            return $programResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function update(ProgramRequest $request, Program $program)
    {
        try {
            $this->crudRepository->update($request->validated(), $program->id);
            activity()->performedOn($program)->withProperties(['attributes' => $program])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('programs', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 222 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = Program::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(Program::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function publicIndex(Request $request): \Illuminate\Http\JsonResponse
    {
        $conferenceId = $request->header('X-Conference-Id');
        if ($conferenceId) {
            $programDays = EventDay::with(['programs' => function ($query) {
                $query->where('active', true)->orderBy('from', 'asc');
            }])->where('active', true)->where('conference_id', $conferenceId)->get();
            $daysResource = PublicEventDayWithProgramsResource::collection($programDays);
            return JsonResponse::respondSuccess(null, $daysResource);
        } else {
            return JsonResponse::respondError('No conferences available at this moment', 404);
        }
    }
}
