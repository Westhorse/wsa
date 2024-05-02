<?php

namespace App\Repositories;

use App\Interfaces\EventPageRepositoryInterface;
use App\Models\EventPage;
use Illuminate\Database\Eloquent\Model;

class EventPageRepository extends CrudRepository implements EventPageRepositoryInterface
{
    protected Model $model;

    public function __construct(EventPage $model)
    {
        $this->model = $model;
    }

}
