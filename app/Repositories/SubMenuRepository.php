<?php

namespace App\Repositories;

use App\Interfaces\SubMenuRepositoryInterface;
use App\Models\SubMenu;
use Illuminate\Database\Eloquent\Model;

class SubMenuRepository extends CrudRepository implements SubMenuRepositoryInterface
{
    protected Model $model;

    public function __construct(SubMenu $model)
    {
        $this->model = $model;
    }
}
