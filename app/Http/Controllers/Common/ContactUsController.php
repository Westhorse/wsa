<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Common\ContactUsRequest;
use App\Http\Resources\Common\ContactUsResource;
use App\Interfaces\ContactUsRepositoryInterface;
use App\Mail\ContactUsMail;
use App\Mail\ContactUsRequestMail;
use App\Models\ContactUs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(ContactUsRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index(): \Illuminate\Http\JsonResponse|AnonymousResourceCollection
    {
        try {
            $message = ContactUsResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'email', 'phone', 'address', 'subject', 'message', 'network_id', 'deleted_at', 'created_at'],
                true,
                true
            ));
            return $message->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function show(ContactUs $contactu): \Illuminate\Http\JsonResponse|ContactUsResource
    {
        try {
            $message = new ContactUsResource($contactu);
            return $message->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(ContactUsRequest $request)
    {
        try {
            $contactUs = $this->crudRepository->create($request->validated());
            DB::table('contact_us')->where('id', $contactUs->id)->update(['network_id' => $request->header('X-Network-ID')]);
            try {
                $result = DB::table('email_templates')->where('id', 1)->select('bcc', 'body', 'subject')->first();
                $emails = $result->bcc;
                $template = $result->body;
                $subject = $result->subject;
                $emails_bcc = explode(',', $emails->bcc);
                foreach ($emails_bcc as $email) {
                    Mail::to($email)->queue(new ContactUsMail($contactUs));
                }
                Mail::to($contactUs->email)->queue(new ContactUsRequestMail($template, $subject));
            } catch (Exception $e) {
                Log::error('Error sending contact Us emails: ' . $e->getMessage(), ['context' => $e]);
                JsonResponse::respondError($e->getMessage());
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(ContactUsRequest $request, ContactUs $contactu): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->update($request->validated(), $contactu->id);
            activity()->performedOn($contactu)->withProperties(['attributes' => $contactu])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $count = $this->crudRepository->deleteRecords('contact_us', $request['items']);
            return $count > 1
                ? JsonResponse::respondError(trans("responses.msg_multi_resources_cannot_deleted"))
                : ($count == 1 ? JsonResponse::respondError(trans("responses.msg_cannot_deleted"))
                    : JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY)));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(ContactUs::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




   
}
