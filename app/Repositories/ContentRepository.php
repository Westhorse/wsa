<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\ContentRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Content;
use Illuminate\Database\Eloquent\Model;

class ContentRepository extends CrudRepository implements ContentRepositoryInterface
{
    protected Model $model;

    public function __construct(Content $model)
    {
        $this->model = $model;
    }
}
