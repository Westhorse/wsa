<?php

namespace App\Repositories;

use App\Interfaces\ConferenceRepositoryInterface;
use App\Models\Conference;
use Illuminate\Database\Eloquent\Model;

class ConferenceRepository extends CrudRepository implements ConferenceRepositoryInterface
{
    protected Model $model;

    public function __construct(Conference $model)
    {
        $this->model = $model;
    }
}
