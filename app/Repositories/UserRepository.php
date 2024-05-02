<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends CrudRepository implements UserRepositoryInterface
{
    protected Model $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function allMember($with = [], $conditions = [], $columns = ['*'], $useNetworkId = false, $useCollection = false)
    {
        $conferenceId = request()->header('X-Conference-Id');
        $currentConferenceCollect = Conference::where('id', $conferenceId)->first();
        $order_by = request(Constants::ORDER_BY) ?? "id";
        $deleted = request(Constants::Deleted) ?? false;
        $order_by_direction = request(Constants::ORDER_By_DIRECTION) ?? "asc";
        $filter_operator = request(Constants::FILTER_OPERATOR) ?? "Like";
        $filters = request(Constants::FILTERS) ?? [];
        $per_page = request(Constants::PER_PAGE) ?? 15;
        $paginate = request(Constants::PAGINATE) ?? false;

        $relationFilter = request('relationFilter');

        $query = $this->model;

        $query = $query->whereHas('orders', function ($q) use ($relationFilter) {

            if (!empty($relationFilter['sponsorshipItemName'])) {
                $q->whereHas('sponsorshipItems', function ($sq) use ($relationFilter) {
                    $sq->where('name', 'like', '%' . $relationFilter['sponsorshipItemName'] . '%');
                });
            }
            if (!empty($relationFilter['packageName'])) {
                $q->whereHas('package', function ($pq) use ($relationFilter) {
                    $pq->where('name', 'like', '%' . $relationFilter['packageName'] . '%');
                });
            }
        });

        $query = $query->whereHas('conferences', function ($q) use ($conferenceId) {
            $q->where('conference_id', $conferenceId);
        });

        if (!empty($relationFilter['memberType'])) {
            $query->whereHas('conferences', function ($sq) use ($relationFilter) {
                $sq->where('type', $relationFilter['memberType']);
            });
        }


        if (!empty($relationFilter['lastOrderStatus'])) {
            $query = $query->whereHas('orders', function ($q) use ($relationFilter) {
                $q->where('id', function ($subQuery) {
                    $subQuery->from('orders as o2')
                        ->selectRaw('MAX(id)')
                        ->whereColumn('user_id', 'orders.user_id');
                })->whereIn('status', $relationFilter['lastOrderStatus']);
            });
        }

        $all_conditions = array_merge($conditions, $filters);

        if ($deleted == true) {
            $query = $query->onlyTrashed();
        }
        if (!empty($with)) {
            $query = $query->with($with);
        }

        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if (is_numeric($value)) {
                    $query = $query->where($key, '=', $value);
                } else {
                    $query = $query->where($key, 'LIKE', '%' . $value . '%');
                }
            }
        }

        if (isset($order_by)) {
            $query = $query->orderBy($order_by, $order_by_direction);
        }

        if ($paginate) {
            return $query->paginate($per_page, $columns);
        } else {
            return $query->get($columns);
        }
    }
}
