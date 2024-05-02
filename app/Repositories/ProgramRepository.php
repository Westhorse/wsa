<?php

namespace App\Repositories;

use App\Interfaces\ProgramRepositoryInterface;
use App\Models\Program;
use Illuminate\Database\Eloquent\Model;

class ProgramRepository extends CrudRepository implements ProgramRepositoryInterface
{
    protected Model $model;

    public function __construct(Program $model)
    {
        $this->model = $model;
    }
}
