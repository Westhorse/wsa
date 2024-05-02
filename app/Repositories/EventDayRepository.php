<?php

namespace App\Repositories;

use App\Interfaces\EventDayRepositoryInterface;
use App\Models\EventDay;
use Illuminate\Database\Eloquent\Model;

class EventDayRepository extends CrudRepository implements EventDayRepositoryInterface
{
    protected Model $model;

    public function __construct(EventDay $model)
    {
        $this->model = $model;
    }
}

