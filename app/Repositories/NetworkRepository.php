<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\NetworkRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Network;
use Illuminate\Database\Eloquent\Model;

class NetworkRepository extends CrudRepository implements NetworkRepositoryInterface
{
    protected Model $model;

    public function __construct(Network $model)
    {
        $this->model = $model;
    }
}
