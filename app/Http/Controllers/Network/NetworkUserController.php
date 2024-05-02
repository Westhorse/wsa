<?php

namespace App\Http\Controllers\Common;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Country;
use App\Models\User;
use Exception;
use Request;

class NetworkUserController extends BaseController
{
    protected mixed $crudRepository;

    public function __construct(UserRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }



    public function addVoting(Request $request)
    {
        try {
            $user = User::findOrFail($request['user_id']);
            foreach ($request['vote'] as $voteData) {
                $country = Country::findOrFail($voteData['country_id']);
                $member = User::findOrFail($voteData['member_id']);
                $existingVote = $user->votedMembers()
                    ->where('countries_users.user_id', $user->id)
                    ->where('countries_users.country_id', $country->id)
                    ->where('countries_users.member_id', $member->id)
                    ->first();
                if ($existingVote) {
                    $pivotModel = $existingVote->pivot;
                    $pivotModel->update([
                        'member_id' => $voteData['member_id'],
                        'updated_at' => now()
                    ]);
                } else {
                    $user->votedMembers()->attach($country, [
                        'country_id' => $voteData['country_id'],
                        'member_id' => $voteData['member_id'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
  }
