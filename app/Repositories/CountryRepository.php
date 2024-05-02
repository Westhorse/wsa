<?php

namespace App\Repositories;


use App\Interfaces\CountryRepositoryInterface;
use App\Models\Admin;
use App\Models\Country;
use Illuminate\Database\Eloquent\Model;

class CountryRepository extends CrudRepository implements CountryRepositoryInterface
{
    protected Model $model;

    public function __construct(Country $model)
    {
        $this->model = $model;
    }
}
