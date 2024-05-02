<?php

namespace App\Http\Resources\Conference;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city_id' => $this->city_id,
            'country_id' => $this->country_id,
            'city' => $this->city->name,
            'country' => $this->country->name,
            'flag' => $this->country->getFirstMediaUrl(),
            'venue' => $this->venue,
            'virtual' => $this->virtual,
            'active' => $this->active,
            'order_id' => $this->order_id,
            'early_bird_active' => $this->early_bird_active,
            'early_bird_end_date' => $this->early_bird_end_date ? $this->early_bird_end_date->format('Y-m-d H:i') : null,
            'reg_deadline_date' => $this->reg_deadline_date ? $this->reg_deadline_date->format('Y-m-d H:i') : null,
            'hotel_booking_max_duration' => $this->hotel_booking_max_duration,
            'eb_member_delegate_price' => $this->eb_member_delegate_price,
            'eb_member_spouse_price' => $this->eb_member_spouse_price,
            'eb_non_member_delegate_price' => $this->eb_non_member_delegate_price,
            'eb_non_member_spouse_price' => $this->eb_non_member_spouse_price,
            'member_delegate_price' => $this->member_delegate_price,
            'member_spouse_price' => $this->member_spouse_price,
            'non_member_delegate_price' => $this->non_member_delegate_price,
            'non_member_spouse_price' => $this->non_member_spouse_price,
            'duration' => $this->duration,
            'isPast' => $this->isEndDatePast,
            'logo_url' => $this->getFirstMediaUrl('logo'),
            'logo' => $this->getFirstMediaResource('logo'),
            'logo_dark_url' => $this->getFirstMediaUrl('logo_dark'),
            'logo_dark' => $this->getFirstMediaResource('logo_dark'),
            'deleted' => isset($this->deleted_at),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
        ];
    }
}
