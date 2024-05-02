<?php

namespace App\Repositories;


use App\Interfaces\ReferralRepositoryInterface;
use App\Models\Referral;
use Illuminate\Database\Eloquent\Model;

class ReferralRepository extends CrudRepository implements ReferralRepositoryInterface
{
    protected Model $model;

    public function __construct(Referral $model)
    {
        $this->model = $model;
    }
}
