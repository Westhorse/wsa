<?php

namespace App\Repositories;

use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Models\Certificate;
use App\Models\City;
use App\Models\Conference;
use Illuminate\Database\Eloquent\Model;

class CityRepository extends CrudRepository implements CityRepositoryInterface
{
    protected Model $model;

    public function __construct(City $model)
    {
        $this->model = $model;
    }
}
