<?php

namespace App\Interfaces;

use App\Repositories\ICrudRepository;

interface UserRepositoryInterface extends ICrudRepository
{
    public function allMember($with = [], $conditions = [], $columns = array('*'));
}
