<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\LogResource;
use App\Http\Resources\Dashboard\UserSimpleResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function logIndex(Request $request)
    {
        $query = DB::table('activity_log')->orderByDesc('id');
        $from = $request->input('from');
        $to = $request->input('to');
        $perPage = $request->input('per_page', 15);
        if ($from && $to) {
            $fromDate = \Carbon\Carbon::parse($from)->startOfDay();
            $toDate = \Carbon\Carbon::parse($to)->endOfDay();

            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }
        $log = $query->paginate($perPage);
        return LogResource::collection($log);
    }


    public function totalCountPerCountry(Request $request)
    {
        try {
            $networkId = $request->header('X-Network-ID');

            $userCountByCountry = DB::table('countries')
                ->select(
                    'countries.name as country_name',
                    DB::raw('SUM(CASE WHEN users_networks.status = "approved" THEN 1 ELSE 0 END) as user_count_approved'),
                    DB::raw('SUM(CASE WHEN users_networks.status = "suspended" THEN 1 ELSE 0 END) as user_count_suspended')
                )
                ->join('users', 'countries.id', '=', 'users.country_id')
                ->join('users_networks', 'users.id', '=', 'users_networks.user_id')
                ->where('users_networks.network_id', $networkId)
                ->whereIn('users_networks.status', ['approved', 'suspended'])
                ->whereNull('users.deleted_at')
                ->groupBy('countries.name')
                ->get();

            $result = [];

            foreach ($userCountByCountry as $country) {
                $result[] = [
                    'country_name' => $country->country_name,
                    'user_count_approved' => $country->user_count_approved,
                    'user_count_suspended' => $country->user_count_suspended,
                ];
            }

            return $result;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function userStatistics(Request $request)
    {
        try {
            $networkId = $request->header('X-Network-ID');

            $total_approved = DB::table('users_networks')
                ->join('users', 'users.id', '=', 'users_networks.user_id')
                ->where('users_networks.network_id', $networkId)
                ->where('users_networks.status', 'approved')
                ->whereNull('users.deleted_at')
                ->count();

            $total_blacklisted = DB::table('users_networks')
                ->join('users', 'users.id', '=', 'users_networks.user_id')
                ->where('users_networks.network_id', $networkId)
                ->where('users_networks.status', 'blacklisted')
                ->whereNull('users.deleted_at')
                ->count();

            $total_suspended = DB::table('users_networks')
                ->join('users', 'users.id', '=', 'users_networks.user_id')
                ->where('users_networks.network_id', $networkId)
                ->where('users_networks.status', 'suspended')
                ->whereNull('users.deleted_at')
                ->count();

            $totalCountriesWithMembersCount = DB::table('countries')
                ->where('active', 1)
                ->whereExists(function ($query) use ($networkId) {
                    $query->select(DB::raw(1))
                        ->from('users')
                        ->join('users_networks', 'users.id', '=', 'users_networks.user_id')
                        ->whereRaw('countries.id = users.country_id')
                        ->whereIn('users_networks.status', ['approved', 'suspended'])
                        ->where('users_networks.network_id', $networkId)
                        ->whereNull('users.deleted_at');
                })->count();

            return [
                'total_approved' => $total_approved,
                'total_blacklisted' => $total_blacklisted,
                'total_suspended' => $total_suspended,
                'total_countries_with_members' => $totalCountriesWithMembersCount,
            ];
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function companiesCount(Request $request)
    {
        try {
            if (empty(request('type'))) {
                $type = ["member", "founder", "vendor", "partner"];
            } else {
                $type = request('type');
            }
            $networkId = $request->header('X-Network-ID');
            $total_type = DB::table('users_networks')
                ->join('users', 'users.id', '=', 'users_networks.user_id')
                ->where('users_networks.network_id', $networkId)
                ->whereIn('users_networks.type', $type)
                ->whereNull('users.deleted_at')
                ->count();

            return [
                'total_type' => $total_type,
            ];
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function renewalUserCount(Request $request)
    {
        try {
            $networkId = $request->header('X-Network-ID');
            $expiredUsersCount = DB::table('users_networks')
                ->join('users', 'users.id', '=', 'users_networks.user_id')
                ->where('users_networks.network_id', $networkId)
                ->where('users_networks.expire_date', '>=', Carbon::now()->subDays(30))
                ->where('users_networks.expire_date', '<', Carbon::now())
                ->get();
            return $expiredUsersCount;
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function RenewalMembersByDate(Request $request)
    {
        try {
            $networkId = $request->header('X-Network-ID');

            $expiredUsersCount = User::whereHas('networks', function ($query) use ($networkId) {
                $query->where('networks.id', $networkId)
                    ->whereDate('users_networks.expire_date', '>=', request('from'))
                    ->whereDate('users_networks.expire_date', '<=', request('to'));
            })->get();
            return  UserSimpleResource::collection($expiredUsersCount);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
