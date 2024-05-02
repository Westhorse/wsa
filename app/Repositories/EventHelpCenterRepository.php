<?php

namespace App\Repositories;

use App\Interfaces\EventHelpCenterRepositoryInterface;
use App\Models\EventHelpCenter;
use Illuminate\Database\Eloquent\Model;

class EventHelpCenterRepository extends CrudRepository implements EventHelpCenterRepositoryInterface
{
    protected Model $model;

    public function __construct(EventHelpCenter $model)
    {
        $this->model = $model;
    }
}
