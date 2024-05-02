<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\PageSectionRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\PageSection;
use Illuminate\Database\Eloquent\Model;

class PageSectionRepository extends CrudRepository implements PageSectionRepositoryInterface
{
    protected Model $model;

    public function __construct(PageSection $model)
    {
        $this->model = $model;
    }
}
