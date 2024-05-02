<?php

namespace App\Repositories;

use App\Interfaces\EventMenuRepositoryInterface;
use App\Models\EventMenu;
use Illuminate\Database\Eloquent\Model;

class EventMenuRepository extends CrudRepository implements EventMenuRepositoryInterface
{
    protected Model $model;

    public function __construct(EventMenu $model)
    {
        $this->model = $model;
    }
}
