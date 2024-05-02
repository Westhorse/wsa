<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\ServiceRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

class ServiceRepository extends CrudRepository implements ServiceRepositoryInterface
{
    protected Model $model;

    public function __construct(Service $model)
    {
        $this->model = $model;
    }
}
