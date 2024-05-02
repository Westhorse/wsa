<?php

namespace App\Repositories;


use App\Interfaces\IncotermRepositoryInterface;
use App\Models\Incoterm;
use Illuminate\Database\Eloquent\Model;

class IncotermRepository extends CrudRepository implements IncotermRepositoryInterface
{
    protected Model $model;

    public function __construct(Incoterm $model)
    {
        $this->model = $model;
    }
}
