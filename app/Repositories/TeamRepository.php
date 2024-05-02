<?php

namespace App\Repositories;

use App\Interfaces\TeamRepositoryInterface;
use App\Models\Team;
use Illuminate\Database\Eloquent\Model;

class TeamRepository extends CrudRepository implements TeamRepositoryInterface
{
    protected Model $model;

    public function __construct(Team $model)
    {
        $this->model = $model;
    }
}
