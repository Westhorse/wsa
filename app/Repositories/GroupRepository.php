<?php

namespace App\Repositories;


use App\Interfaces\GroupRepositoryInterface;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;

class GroupRepository extends CrudRepository implements GroupRepositoryInterface
{
    protected Model $model;

    public function __construct(Group $model)
    {
        $this->model = $model;
    }
}
