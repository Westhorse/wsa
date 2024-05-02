<?php

namespace App\Repositories;

use App\Interfaces\PackageRepositoryInterface;
use App\Models\Package;
use Illuminate\Database\Eloquent\Model;

class PackageRepository extends CrudRepository implements PackageRepositoryInterface
{
    protected Model $model;

    public function __construct(Package $model)
    {
        $this->model = $model;
    }
}
