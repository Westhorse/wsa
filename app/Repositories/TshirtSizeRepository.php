<?php

namespace App\Repositories;

use App\Interfaces\TshirtSizeRepositoryInterface;
use App\Models\TshirtSize;
use Illuminate\Database\Eloquent\Model;

class TshirtSizeRepository extends CrudRepository implements TshirtSizeRepositoryInterface
{
    protected Model $model;

    public function __construct(TshirtSize $model)
    {
        $this->model = $model;
    }
}
