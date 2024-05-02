<?php

namespace App\Repositories;


use App\Interfaces\DietaryRepositoryInterface;
use App\Models\Dietary;
use Illuminate\Database\Eloquent\Model;

class DietaryRepository extends CrudRepository implements DietaryRepositoryInterface
{
    protected Model $model;

    public function __construct(Dietary $model)
    {
        $this->model = $model;
    }
}
