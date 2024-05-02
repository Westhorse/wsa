<?php

namespace App\Repositories;

use App\Interfaces\SponsorRepositoryInterface;
use App\Models\Sponsor;
use Illuminate\Database\Eloquent\Model;

class SponsorRepository extends CrudRepository implements SponsorRepositoryInterface
{
    protected Model $model;

    public function __construct(Sponsor $model)
    {
        $this->model = $model;
    }
}
