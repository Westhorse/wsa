<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\EmailTemplateRequest;
use App\Http\Resources\Common\EmailTemplateResource;
use App\Interfaces\EmailTemplateRepositoryInterface;
use App\Models\EmailTemplate;
use Exception;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    protected mixed $crudRepository;

    public function __construct(EmailTemplateRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $emailTemplates = EmailTemplateResource::collection($this->crudRepository->all());
            return $emailTemplates->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(EmailTemplate $emailtemplate)
    {
        try {
            $emailTemplate = new EmailTemplateResource($emailtemplate);
            return $emailTemplate->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(EmailTemplateRequest $request)
    {
        try {
            $emailTemplate = $this->crudRepository->create($request->validated());
            return new EmailTemplateResource($emailTemplate);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(EmailTemplateRequest $request, EmailTemplate $emailtemplate)
    {
        try {
            $this->crudRepository->update($request->validated(), $emailtemplate->id);
            activity()->performedOn($emailtemplate)->withProperties(['attributes' => $emailtemplate])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY), null);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }

    }

    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('email_templates', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED_MULTI_RESOURCE))
                : ($count == 222 ? JsonResponse::respondError(trans(JsonResponse::MSG_CANNOT_DELETED))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function restore(Request $request)
    {
        try {
            $this->crudRepository->restoreItem(EmailTemplate::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request)
    {
        try {
            $exists = EmailTemplate::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(EmailTemplate::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
