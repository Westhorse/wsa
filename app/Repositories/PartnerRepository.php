<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\PartnerRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Model;

class PartnerRepository extends CrudRepository implements PartnerRepositoryInterface
{
    protected Model $model;

    public function __construct(Partner $model)
    {
        $this->model = $model;
    }
}
