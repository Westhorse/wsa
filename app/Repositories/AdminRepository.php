<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use Illuminate\Database\Eloquent\Model;

class AdminRepository extends CrudRepository implements AdminRepositoryInterface
{
    protected Model $model;

    public function __construct(Admin $model)
    {
        $this->model = $model;
    }
}
