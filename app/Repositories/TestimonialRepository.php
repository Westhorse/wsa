<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\ConferenceRepositoryInterface;
use App\Interfaces\TestimonialRepositoryInterface;
use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;

class TestimonialRepository extends CrudRepository implements TestimonialRepositoryInterface
{
    protected Model $model;

    public function __construct(Testimonial $model)
    {
        $this->model = $model;
    }
}
