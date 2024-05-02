<?php

namespace App\Repositories;

use App\Interfaces\FaqRepositoryInterface;
use App\Models\Faq;
use Illuminate\Database\Eloquent\Model;

class FaqRepository extends CrudRepository implements FaqRepositoryInterface
{
    protected Model $model;

    public function __construct(Faq $model)
    {
        $this->model = $model;
    }
}
