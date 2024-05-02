<?php

namespace App\Interfaces;

use App\Repositories\ICrudRepository;

interface DelegateRepositoryInterface extends ICrudRepository
{
    public function allDelegate($with = [], $conditions = [], $columns = array('*'));
}
