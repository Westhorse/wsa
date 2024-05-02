<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\ContactPersonRepositoryInterface;
use App\Interfaces\FollowingCaseRepositoryInterface;
use App\Interfaces\TradeReferenceRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\ContactPerson;
use App\Models\FollowingCase;
use App\Models\TradeReference;
use Illuminate\Database\Eloquent\Model;

class TradeReferenceRepository extends CrudRepository implements TradeReferenceRepositoryInterface
{
    protected Model $model;

    public function __construct(TradeReference $model)
    {
        $this->model = $model;
    }
}
