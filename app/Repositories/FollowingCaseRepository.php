<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\ContactPersonRepositoryInterface;
use App\Interfaces\FollowingCaseRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\ContactPerson;
use App\Models\FollowingCase;
use Illuminate\Database\Eloquent\Model;

class FollowingCaseRepository extends CrudRepository implements FollowingCaseRepositoryInterface
{
    protected Model $model;

    public function __construct(FollowingCase $model)
    {
        $this->model = $model;
    }
}
