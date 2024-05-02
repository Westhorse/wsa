<?php

namespace App\Interfaces;

use App\Repositories\ICrudRepository;

interface SpouseRepositoryInterface extends ICrudRepository
{
    public function allSpouse($with = [], $conditions = [], $columns = array('*'));
}
