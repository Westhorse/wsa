<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\BenefitRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Models\Admin;
use App\Models\Benefit;
use App\Models\Certificate;
use App\Models\Conference;
use Illuminate\Database\Eloquent\Model;

class BenefitRepository extends CrudRepository implements BenefitRepositoryInterface
{
    protected Model $model;

    public function __construct(Benefit $model)
    {
        $this->model = $model;
    }
}
