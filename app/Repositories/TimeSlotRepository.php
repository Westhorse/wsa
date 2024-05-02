<?php

namespace App\Repositories;

use App\Interfaces\TimeSlotRepositoryInterface;
use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Model;

class TimeSlotRepository extends CrudRepository implements TimeSlotRepositoryInterface
{
    protected Model $model;

    public function __construct(TimeSlot $model)
    {
        $this->model = $model;
    }
}
