<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageMeetingMobileResource extends JsonResource
{
    protected $name;

    public function __construct($resource, $name)
    {
        parent::__construct($resource);
        $this->name = $name;
    }

    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
