<?php

namespace App\Repositories;

use App\Interfaces\EventItemRepositoryInterface;
use App\Models\EventItem;
use Illuminate\Database\Eloquent\Model;

class EventItemRepository extends CrudRepository implements EventItemRepositoryInterface
{
    protected Model $model;

    public function __construct(EventItem $model)
    {
        $this->model = $model;
    }
}
