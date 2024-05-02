<?php

namespace App\Http\Resources\Conference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DelegateMeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $hasSelfMeeting = null;
        $hasColleagueMeeting = null;
        if (auth()->check()) {
            $user_id = auth()->user()->user_id;
            if ($user_id) {

                $hasSelfMeeting = DB::table('delegates_time_slots')
                    ->where(function ($query) {
                        $query->whereJsonContains('status_request->sender', Auth::id())
                            ->orWhereJsonContains('status_request->receiver', Auth::id());
                    })
                    ->where(function ($query) {
                        $query->whereJsonContains('status_request->sender', $this->id)
                            ->orWhereJsonContains('status_request->receiver', $this->id);
                    })->exists();

                    $hasColleagueMeeting = DB::table('delegates_time_slots')
                    ->where(function ($query) use ($user_id) {
                        $query->where('status_request->sender', '!=', auth()->id())
                            ->whereIn('status_request->sender', function ($query) use ($user_id) {
                                $query->select('id')
                                    ->from('delegates')
                                    ->where('user_id', $user_id);
                            });
                    })
                    ->orWhere(function ($query) use ($user_id) {
                        $query->where('status_request->receiver', '!=', auth()->id())
                            ->whereIn('status_request->receiver', function ($query) use ($user_id) {
                                $query->select('id')
                                    ->from('delegates')
                                    ->where('user_id', $user_id);
                            });
                    })->where(function ($query) {
                        $query->whereJsonContains('status_request->sender', $this->id)
                            ->orWhereJsonContains('status_request->receiver', $this->id);
                    })
                    ->pluck('delegate_id')
                    ->contains($this->id);

            }
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'type' => $this->type,
            'job_title' => $this->job_title ?? null,
            'user_id' => $this->user_id ?? null,
            'image_url' => $this->getFirstMediaUrl(),
            'company' => new EventCompanySimpleResource($this->user),
            'hasSelfMeeting' => $hasSelfMeeting,
            'hasColleagueMeeting' => $hasColleagueMeeting,
        ];
    }
}
