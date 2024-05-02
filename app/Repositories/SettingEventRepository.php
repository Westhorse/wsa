<?php

namespace App\Repositories;

use App\Interfaces\SettingEventRepositoryInterface;
use App\Models\SettingEvent;
use Illuminate\Database\Eloquent\Model;

class SettingEventRepository extends CrudRepository implements SettingEventRepositoryInterface
{
    protected Model $model;

    public function __construct(SettingEvent $model)
    {
        $this->model = $model;
    }
}
