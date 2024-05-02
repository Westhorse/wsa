<?php

namespace App\Repositories;

use App\Interfaces\MenuRepositoryInterface;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;

class MenuRepository extends CrudRepository implements MenuRepositoryInterface
{
    protected Model $model;

    public function __construct(Menu $model)
    {
        $this->model = $model;
    }
}
