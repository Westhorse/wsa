<?php

namespace App\Http\Controllers\Conference;


use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\EventContactUsRequest;
use App\Http\Resources\Conference\EventContactUsResource;
use App\Interfaces\EventContactUsRepositoryInterface;
use App\Mail\Event\EventApplicationContactUsRequestMail;
use App\Mail\Event\EventContactUsMail;
use App\Models\EventContactUs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Log;

class EventContactUsController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(EventContactUsRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $eventContactUsResources = EventContactUsResource::collection($this->crudRepository->all(
                ['country'],
                [],
                ['id', 'name', 'email', 'created_at']
            ));
            return $eventContactUsResources->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(EventContactUsRequest $request)
    {
        try {
            $contactUs =  $this->crudRepository->create($request->validated());
            try {
                Queue::push(function () use ($contactUs) {
                    $emails = DB::table('email_templates')->where('slug', 'conference_contact_us_email_template')->select('bcc')->first();
                    $emails_bcc = explode(',', $emails->bcc);
                    foreach ($emails_bcc as $email) {
                        Mail::to($email)->queue(new EventContactUsMail($contactUs));
                    }
                    $template = DB::table('email_templates')->where('slug', 'conference_contact_us_email_template')->value('body');
                    $subject = DB::table('email_templates')->where('slug', 'conference_contact_us_email_template')->value('subject');
                    Mail::to($contactUs->email)->queue(new EventApplicationContactUsRequestMail($template, $subject));
                });
            } catch (Exception $e) {
                Log::error('Error sending contact Us emails: ' . $e->getMessage(), ['context' => $e]);
                JsonResponse::respondError($e->getMessage());
            }

            return JsonResponse::success();
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    /**
     * Show the specified resource.
     * @param EventContactUs $event_contact_u
     * @return EventContactUsResource|\Illuminate\Http\JsonResponse
     */
    public function show(EventContactUs $event_contact_u)
    {
        try {
            $eventContactUsResource = new EventContactUsResource($event_contact_u);
            return $eventContactUsResource->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    /**
     * Update the specified resource in storage.
     * @param EventContactUsRequest $request
     * @param EventContactUs $event_contact_u
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EventContactUsRequest $request, EventContactUs $event_contact_u)
    {
        try {
            $this->crudRepository->update($request->validated(), $event_contact_u->id);
            activity()->performedOn($event_contact_u)->withProperties(['attributes' => $event_contact_u])->log('update');
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
            $count = $this->crudRepository->deleteRecords('event_contact_us', $request['items']);
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
            $this->crudRepository->restoreItem(EventContactUs::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $exists = EventContactUs::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(EventContactUs::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
