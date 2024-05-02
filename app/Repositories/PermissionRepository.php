<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class PermissionRepository extends CrudRepository implements PermissionRepositoryInterface
{
    protected Model $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }
}
