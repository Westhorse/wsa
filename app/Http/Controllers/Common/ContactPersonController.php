<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\ContactPersonRequest;
use App\Http\Resources\Common\ContactPersonResource;
use App\Interfaces\ContactPersonRepositoryInterface;
use App\Models\ContactPerson;
use Exception;
use Illuminate\Http\Request;

class ContactPersonController extends Controller
{
    protected mixed $crudRepository;

    public function __construct(ContactPersonRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $contactPerson = ContactPersonResource::collection($this->crudRepository->allcontact());
            return $contactPerson->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    // public function index(request $request)
    // {
    //     try {
    //         $allContactPersons = ContactPersonResource::collection($this->crudRepository->all());
    //         $contactPersons = DB::table('contact_people')
    //             ->join('users', 'contact_people.user_id', '=', 'users.id')
    //             ->whereNull('users.deleted_at')
    //             ->join('users_networks', 'users.id', '=', 'users_networks.user_id')
    //             ->whereIn('users_networks.status', ['approved','suspended'])
    //             ->where('users_networks.network_id','=', $request->header('X-Network-Id'))
    //             ->select('contact_people.*')
    //             ->paginate(request('perPage'));
    //         $filteredContactPersons = $allContactPersons->filter(function ($contactPerson) use ($contactPersons) {
    //             return $contactPersons->contains('id', $contactPerson->id);
    //         });
    //         return ContactPersonResource::collection($filteredContactPersons);
    //     } catch (Exception $e) {
    //         return JsonResponse::respondError($e->getMessage());
    //     }
    // }

    public function show(ContactPerson $contact_person)
    {
        try {
            $contactPerson = new ContactPersonResource($contact_person);
            return $contactPerson->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(ContactPersonRequest $request)
    {
        try {
            $contactPerson = $this->crudRepository->create($request->validated());
            if (request('image') !== null) {
                $this->crudRepository->AddMediaCollection('image', $contactPerson);
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function update(ContactPersonRequest $request, ContactPerson $contact_person)
    {
        try {
            $this->crudRepository->update($request->validated(), $contact_person->id);
            if (request('image') !== null) {
                $data = ContactPerson::find($contact_person->id);
                $this->crudRepository->AddMediaCollection('image', $data);
            }
            activity()->performedOn($contact_person)->withProperties(['attributes' => $contact_person])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function destroy(Request $request)
    {
        try {
            $count = $this->crudRepository->deleteRecords('contact_people', $request['items']);
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
            $this->crudRepository->restoreItem(ContactPerson::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request)
    {
        try {
            $exists = ContactPerson::whereIn('id', $request['items'])->exists();
            if (!$exists) {
                return JsonResponse::respondError("One or more records do not exist. Please refresh the page.");
            }
            $this->crudRepository->deleteRecordsFinial(ContactPerson::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
