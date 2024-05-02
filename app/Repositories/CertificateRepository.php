<?php

namespace App\Repositories;

use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Models\Certificate;
use App\Models\Conference;
use Illuminate\Database\Eloquent\Model;

class CertificateRepository extends CrudRepository implements CertificateRepositoryInterface
{
    protected Model $model;

    public function __construct(Certificate $model)
    {
        $this->model = $model;
    }
}
