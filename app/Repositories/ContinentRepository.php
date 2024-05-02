<?php

namespace App\Repositories;

use App\Interfaces\ContinentRepositoryInterface;
use App\Models\Continent;
use Illuminate\Database\Eloquent\Model;

class ContinentRepository extends CrudRepository implements ContinentRepositoryInterface
{
    protected Model $model;

    public function __construct(Continent $model)
    {
        $this->model = $model;
    }
}
