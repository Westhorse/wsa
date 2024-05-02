<?php

namespace App\Repositories;

use App\Interfaces\RoomRepositoryInterface;
use App\Models\Room;
use Illuminate\Database\Eloquent\Model;

class RoomRepository extends CrudRepository implements RoomRepositoryInterface
{
    protected Model $model;

    public function __construct(Room $model)
    {
        $this->model = $model;
    }
}
