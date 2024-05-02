<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        if ($this->subject_type == 'Modules\User\Entities\User' || $this->subject_type == '') {
            $place = 'User';
            if ($this->description == 'login') {
                $user = DB::table('users')->where('id', $this->subject_id)->value('name');
            } else {
                $user = DB::table('users')->where('id', $this->causer_id)->value('name');
            }
        } else {
            // dd("sd");
            $place = 'Admin';
            if ($this->description == 'login') {
                $user = DB::table('admins')->where('id', $this->subject_id)->value('name');
            } else {
                $user = DB::table('admins')->where('id', $this->causer_id)->value('name');
            }
        }
        $attributes = json_decode($this->properties, true)['attributes'] ?? null;
        $ip = $request->ip();

        return [
            'id' => $this->id,
            'description' => $this->description ?? null,
            'module' => class_basename($this->subject_type) ?? null,
            'recordID' => $this->subject_id ?? null,
            'causerModule' => $place ?? null,
            'causer' => $user,
            'ip' => $ip,
            'properties' => $attributes ?? null,
            'date' => $this->created_at ? date('Y-M-d', strtotime($this->created_at)) : null,
            'time' => $this->created_at ? date('H:i:s A', strtotime($this->created_at)) : null,
        ];
    }
}
