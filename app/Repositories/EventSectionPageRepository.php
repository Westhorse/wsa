<?php

namespace App\Repositories;

use App\Interfaces\EventSectionPageRepositoryInterface;
use App\Models\EventSectionPage;
use Illuminate\Database\Eloquent\Model;

class EventSectionPageRepository extends CrudRepository implements EventSectionPageRepositoryInterface
{
    protected Model $model;

    public function __construct(EventSectionPage $model)
    {
        $this->model = $model;
    }
}
