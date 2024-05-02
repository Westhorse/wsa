<?php

namespace App\Repositories;

use App\Interfaces\SponsorshipItemRepositoryInterface;
use App\Models\SponsorshipItem;
use Illuminate\Database\Eloquent\Model;

class SponsorshipItemRepository extends CrudRepository implements SponsorshipItemRepositoryInterface
{
    protected Model $model;

    public function __construct(SponsorshipItem $model)
    {
        $this->model = $model;
    }
}
