<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Conference\ConferenceRequest;
use App\Http\Resources\Conference\ConferenceResource;
use App\Http\Resources\Conference\DietaryResource;
use App\Http\Resources\Conference\OrderResource;
use App\Http\Resources\Conference\OrderRoomResource;
use App\Http\Resources\Conference\PackageResource;
use App\Http\Resources\Conference\PersonResource;
use App\Http\Resources\Conference\RoomResource;
use App\Http\Resources\Conference\SponsorshipItemResource;
use App\Http\Resources\Conference\SponsorshipItemSimpleResource;
use App\Http\Resources\Conference\TshirtSizeResource;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Models\Conference;
use App\Models\Dietary;
use App\Models\EventDay;
use App\Models\Package;
use App\Models\Room;
use App\Models\SponsorshipItem;
use App\Models\TshirtSize;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ConferenceController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(ConferenceRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $conferences = ConferenceResource::collection($this->crudRepository->all(['country', 'city'], [], ['id', 'name', 'city_id', 'country_id', 'early_bird_active', 'duration']));
            return $conferences->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function indexPublic(Request $request)
    {
        try {
            $conferenceData = Conference::where('active', 1)->get();
            $conference = ConferenceResource::collection($conferenceData);
            return $conference->additional(JsonResponse::success());
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function store(ConferenceRequest $request)
    {
        try {
            $data = $request->validated();
            $model = $this->crudRepository->create($data);
            if (isset($data['duration']) && is_array($data['duration']) && count($data['duration']) === 2) {
                $startDate = Carbon::parse($data['duration'][0]);
                $endDate = Carbon::parse($data['duration'][1]);
                $dayIndex = 0;

                while ($startDate <= $endDate) {
                    $dayIndex++;
                    EventDay::create([
                        'name' => 'Day ' . $dayIndex,
                        'active' => true,
                        'date' => $startDate,
                        'conference_id' => $model->id
                    ]);
                    $startDate->addDay();
                }
            }
            if (request('logo') !== null) {
                $this->crudRepository->AddMediaCollection('logo', $model, 'logo');
            }
            if (request('logo_dark') !== null) {
                $this->crudRepository->AddMediaCollection('logo_dark', $model, 'logo_dark');
            }

            return new ConferenceResource($model);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    /**
     * Show the specified resource.
     * @param Conference $conference
     * @return ConferenceResource|\Illuminate\Http\JsonResponse
     */
    public function show(Conference $conference)
    {
        try {
            $cacheKey = 'conference_' . $conference->id;
            if (cache()->has($cacheKey)) {
                $cachedConference = cache($cacheKey);
            } else {
                $cachedConference = new ConferenceResource($conference);
                cache([$cacheKey => $cachedConference], 5);
            }
            return $cachedConference->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param ConferenceRequest $request
     * @param Conference $conference
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ConferenceRequest $request, Conference $conference)
    {
        try {
            $data = $request->validated();
            unset($data['id']);
            $conference->update($data);
            if (isset($data['duration']) && is_array($data['duration']) && count($data['duration']) === 2) {
                $startDate = Carbon::parse($data['duration'][0]);
                $endDate = Carbon::parse($data['duration'][1]);
                $conferenceId = $conference->id;

                EventDay::where('conference_id', $conferenceId)
                    ->whereNotBetween('date', [$startDate, $endDate])
                    ->delete();

                $dayIndex = 0;
                while ($startDate <= $endDate) {
                    $dayIndex++;
                    EventDay::updateOrCreate(
                        ['date' => $startDate, 'conference_id' => $conferenceId],
                        ['name' => 'Day ' . $dayIndex, 'active' => true]
                    );
                    $startDate->addDay();
                }
            }
            if (request('logo') !== null) {
                $image = $this->crudRepository->AddMediaCollection('logo', $conference, 'logo');
            }
            if (request('logo_dark') !== null) {
                $image = $this->crudRepository->AddMediaCollection('logo_dark', $conference, 'logo_dark');
            }
            activity()->performedOn($conference)->withProperties(['attributes' => $conference])->log('update');
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
            $count = $this->crudRepository->deleteRecords('conference', $request['items']);
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
            $this->crudRepository->restoreItem(Conference::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecordsFinial(Conference::class, $request['items']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function getCurrentConference()
    {
        try {
            $currentDate = today()->toDateString();

            $conferences = Conference::all();

            foreach ($conferences as $conference) {
                $durationEnd = $conference->duration[1];

                if ($currentDate <= $durationEnd) {
                    $activeConference = new ConferenceResource($conference);
                    return $activeConference->additional(JsonResponse::success());
                }
            }

            return JsonResponse::respondError('No current conference found.', 404);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }





    public function getApplicationFormResourcesData()
    {
        $sponsorshipItems = SponsorshipItem::where('active', true)->orderBy('active')->orderBy('order_id')->get();
        $packages = Package::where('active', true)->orderBy('order_id')->get();
        $tshirtSizes = TshirtSize::where('active', true)->orderBy('active')->orderBy('order_id')->get();
        $dietaries = Dietary::where('active', true)->orderBy('active')->orderBy('order_id')->get();
        $rooms = Room::where('active', true)->orderBy('active')->orderBy('order_id')->get();

        $data = [
            'sponsorshipItems' => SponsorshipItemResource::collection($sponsorshipItems),
            'packages' => PackageResource::collection($packages),
            'tshirtSizes' => TshirtSizeResource::collection($tshirtSizes),
            'dietaries' => DietaryResource::collection($dietaries),
            'rooms' => RoomResource::collection($rooms),
        ];

        return JsonResponse::respondSuccess('Data fetched successfully', $data);
    }


    /**
     * Returns all the orders associated with the user.
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function getAllOrder(User $user, Request $request)
    {
        try {
            $user->load(['orders' => function ($query) use ($request) {
                $query->where('conference_id', $request->header('X-Conference-Id'));
            }, 'orders.sponsorshipItems', 'orders.rooms']);

            $formattedOrders = $user->orders->map(function ($order) {
                return new OrderResource($order);
            });
            return JsonResponse::respondSuccess('Orders retrieved successfully', $formattedOrders);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    /**
     * Returns all the sponsorship items associated with the user's orders.
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function getAllSponsorshipItem(User $user, Request $request)
    {
        try {
            $orders = $user->orders()
                ->where(function ($query) use ($request) {
                    $query->where('status', 'approved_bank_transfer')
                        ->orWhere('status', 'approved_online_payment');
                })
                ->where('conference_id', $request->header('X-Conference-Id'))
                ->get();

            $sponsorshipItems = collect();

            foreach ($orders as $order) {
                $sponsorshipItems = $sponsorshipItems->merge($order->sponsorshipItems);
            }

            $formattedSponsorshipItems = SponsorshipItemSimpleResource::collection($sponsorshipItems);
            return JsonResponse::respondSuccess('sponsorship items retrieved successfully', $formattedSponsorshipItems);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Returns all the rooms associated with the user's orders.
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function getAllRoom(User $user, Request $request)
    {
        try {
            $orders = $user->orders()
                ->where(function ($query) use ($request) {
                    $query->where('status', 'approved_bank_transfer')
                        ->orWhere('status', 'approved_online_payment');
                })
                ->where('conference_id', $request->header('X-Conference-Id'))
                ->get();
            $rooms = collect();
            foreach ($orders as $order) {
                $rooms = $rooms->merge($order->rooms);
            }
            $formattedRooms = OrderRoomResource::collection($rooms);
            return JsonResponse::respondSuccess('rooms retrieved successfully', $formattedRooms);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function getAllPerson(User $user)
    {
        try {
            $orders = $user->orders()->where(function ($query) {
                $query->where('status', 'approved_bank_transfer')
                    ->orWhere('status', 'approved_online_payment');
            })->where('conference_id', 1)->get();
            $userPersons = collect();
            foreach ($orders as $order) {
                $userPersons = $userPersons->merge($order->userPersons);
            }
            $formattedUserPersons = PersonResource::collection($userPersons->whereNull('delegate_id'));
            return JsonResponse::respondSuccess('Persons retrieved successfully', $formattedUserPersons);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
