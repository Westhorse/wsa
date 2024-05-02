<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\EventRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Event;
use Illuminate\Database\Eloquent\Model;

class EventRepository extends CrudRepository implements EventRepositoryInterface
{
    protected Model $model;

    public function __construct(Event $model)
    {
        $this->model = $model;
    }
}
