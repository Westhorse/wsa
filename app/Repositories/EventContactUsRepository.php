<?php

namespace App\Repositories;

use App\Interfaces\EventContactUsRepositoryInterface;
use App\Models\EventContactUs;
use Illuminate\Database\Eloquent\Model;

class EventContactUsRepository extends CrudRepository implements EventContactUsRepositoryInterface
{
    protected Model $model;

    public function __construct(EventContactUs $model)
    {
        $this->model = $model;
    }
}
