<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Interfaces\VisitRepositoryInterface;
use App\Models\Visit;

class VisitRepository extends CrudRepository implements VisitRepositoryInterface
{
    protected \Illuminate\Database\Eloquent\Model $model;

    public function __construct(Visit $model)
    {
        $this->model = $model;
    }

    public function allVisit($with = [], $conditions = [], $columns = array('*'), $useNetworkId = false, $useCollection = false)
    {
        $order_by = request(Constants::ORDER_BY) ?? "id";
        $order_by_direction = request(Constants::ORDER_By_DIRECTION) ?? "asc";
        $filter_operator = request(Constants::FILTER_OPERATOR) ?? "=";
        $filters = request(Constants::FILTERS) ?? [];
        $per_page = request(Constants::PER_PAGE) ?? 15;
        $paginate = request(Constants::PAGINATE) ?? false;
        $query = $this->model;

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
