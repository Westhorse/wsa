<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Interfaces\DelegateRepositoryInterface;
use App\Models\Conference;
use App\Models\Delegate;
use Illuminate\Database\Eloquent\Model;

class DelegateRepository extends CrudRepository implements DelegateRepositoryInterface
{
    protected Model $model;

    public function __construct(Delegate $model)
    {
        $this->model = $model;
    }

    public function allDelegate($with = [], $conditions = [], $columns = array('*'))
    {
        $conferenceId = request()->header('X-Conference-Id');
        $currentConferenceCollect = Conference::where('id', $conferenceId)->first();
        $order_by = request(Constants::ORDER_BY) ?? "id";
        $order_by_direction = request(Constants::ORDER_By_DIRECTION) ?? "asc";
        $filter_operator = request(Constants::FILTER_OPERATOR) ?? "=";
        $filters = request(Constants::FILTERS) ?? [];
        $per_page = request(Constants::PER_PAGE) ?? 15;
        $paginate = request(Constants::PAGINATE) ?? false;
        $relationFilter = request('relationFilter');

        $query = $this->model;
        $query = $query->where('conference_id', $conferenceId);
        $query = $this->model->where('type', 'delegate');

        if (!empty($relationFilter['memberType'])) {
            if (!empty($relationFilter['memberType'])) {
                $query->whereHas('user.conferences', function ($q) use ($relationFilter) {
                    $q->where('conferences_users.type', 'like', '%' .  $relationFilter['memberType'] . '%');
                });
            }
        }


        if (!empty($relationFilter['orderStatus'])) {
            $query->whereHas('order', function ($q) use ($relationFilter) {
                $q->whereIn('orders.status', $relationFilter['orderStatus']);
            });
        }


        if (!empty($relationFilter['companyName'])) {
            if (!empty($relationFilter['companyName'])) {
                $query->whereHas('user', function ($q) use ($relationFilter) {
                    $q->where('users.name', 'like', '%' .  $relationFilter['companyName'] . '%');
                });
            }
        }

        if (!empty($relationFilter['companyCountryId'])) {
            if (!empty($relationFilter['companyCountryId'])) {
                $query->whereHas('user', function ($q) use ($relationFilter) {
                    $q->where('users.country_id', 'like', '%' .  $relationFilter['companyCountryId'] . '%');
                });
            }
        }

        $all_conditions = array_merge($conditions, $filters);
        $query = $query->filter($all_conditions, $filter_operator);
        if (isset($order_by) && !empty($with))
            $query = $query->with($with)->orderBy($order_by, $order_by_direction);
        if ($paginate && !empty($with))
            return $query->with($with)->paginate($per_page, $columns);
        if (isset($order_by))
            $query = $query->orderBy($order_by, $order_by_direction);
        if ($paginate)
            return $query->paginate($per_page, $columns);
        if (!empty($with))
            return $query->with($with)->get($columns);
        else
            return $query->get($columns);
    }
}
