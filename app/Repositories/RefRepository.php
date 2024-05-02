<?php

namespace App\Repositories;

use App\Interfaces\RefRepositoryInterface;
use App\Models\Ref;
use Illuminate\Database\Eloquent\Model;

class RefRepository extends CrudRepository implements RefRepositoryInterface
{
    protected Model $model;

    public function __construct(Ref $model)
    {
        $this->model = $model;
    }
}
