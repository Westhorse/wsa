<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Common\ContinentSimpleResource;
use App\Http\Resources\Common\GetUserVoteResource;
use App\Http\Resources\Common\UserVotingResource;
use App\Models\Country;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends BaseController
{
    public function updateVotingActiveStatus(Request $request)
    {
        try {
            $data = $request->all();
            $users = [];
            foreach ($data as $user) {
                $id = $user['id'];
                $votingStatus = $user['voting_active'];
                $users[$id] = [
                    'voting_active' => $votingStatus
                ];
            }
            foreach ($users as $id => $user) {
                DB::table('users')->where('id', $id)->update($user);
            }
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function voteStatistics()
    {
        try {
            // Get All Votes Count
            $totalVotesCount = DB::table('countries_users')->count();

            // Get All Votes - { user_id } Count
            $userVotesCount = DB::table('countries_users')
                ->distinct('user_id')
                ->count('user_id');

            // Get All Voted Members - { member_id } Count
            $votedMembersCount = DB::table('countries_users')
                ->distinct('member_id')
                ->count('member_id');

            // Get All Continents Count
            $totalCountriesCount = DB::table('countries')->where('active', 1)->count();

            // Get Total Countries with Members
            $totalCountriesWithMembersCount = DB::table('countries')
                ->where('active', 1)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('users')->join('users_networks', 'users.id', '=', 'users_networks.user_id')
                        ->whereRaw('countries.id = users.country_id')->whereIn('users_networks.status', ['approved', 'suspended']);
                })
                ->count();
            return [
                'total_votes' => $totalVotesCount,
                'total_voters' => $userVotesCount,
                'total_voted_members' => $votedMembersCount,
                'total_countries' => $totalCountriesCount,
                'total_countries_with_members' => $totalCountriesWithMembersCount,

            ];
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function getUserVote(Request $request , $user_id)
    {
        try {
            $getUserVote = DB::table('countries_users')->where('user_id', $request->input('user_id'))->get();
            return GetUserVoteResource::collection($getUserVote);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function getTopVotedMembersPerContinent()
    {
        $countries = Country::with(['members', 'voters'])->get();

        $topVotedMembersByContinent = [];

        foreach ($countries as $country) {
            if ($country->members->isEmpty()) {
                continue;
            }
            $topVotedMember = $country->members->sortByDesc(function ($member) {
                return $member->voters->count();
            })->first();

            if ($topVotedMember) {
                $totalVotes = $topVotedMember->voters->count();

                $topVotedMembersByContinent[] = [
                    'country' => new ContinentSimpleResource($country),
                    'member' => new UserVotingResource($topVotedMember),
                    'total_votes' => $totalVotes,
                ];
            }
        }

        return $topVotedMembersByContinent;
    }

    //done
    public function getUser(Request $request , $country_id)
    {
        try {
            $country = Country::findOrFail($country_id);
            $usersWithApprovedOrSuspendedStatus = $country->users()
                ->userStatus(['approved', 'suspended'])
                ->get();
            return UserVotingResource::collection($usersWithApprovedOrSuspendedStatus);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
    //done
}
