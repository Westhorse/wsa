<?php

namespace App\Http\Resources\Conference;

use App\Models\Delegate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $person = null;
        if ($this->model_type === 'Modules\Conference\Entities\Delegate') {
            $person = new DelegateMeetingResource(Delegate::find($this->person_id));
        } elseif ($this->model_type === 'Modules\Conference\Entities\User') {
            $person = new EventCompanySimpleResource(User::find($this->person_id));
        }

        return [
            'id' => $this->id,
            'ip' => $this->ip,
            'country_id' => $this->country_id,
            'state' => $this->state,
            'city' => $this->city,
            'path' => $this->path,
            'person' => $person,
            'created_at' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            'deleted' => isset($this->deleted_at),
        ];
    }
}
