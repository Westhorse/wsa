<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\ContactPersonRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\ContactPerson;
use Illuminate\Database\Eloquent\Model;

class ContactPersonRepository extends CrudRepository implements ContactPersonRepositoryInterface
{
    protected Model $model;

    public function __construct(ContactPerson $model)
    {
        $this->model = $model;
    }
}
