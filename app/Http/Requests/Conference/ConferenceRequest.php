<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class ConferenceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'city_id' => ['required'],
            'country_id' => ['required'],
            'venue' => ['required'],
            'virtual' => ['boolean'],
            'active' => ['required'],
            'order_id' => ['numeric','nullable'],
            'early_bird_active' => ['boolean'],
            'early_bird_end_date' => ['nullable'],
            'reg_deadline_date' => ['nullable'],
            'hotel_booking_max_duration' => ['nullable', 'array'],
            'hotel_booking_max_duration.*' => ['nullable', 'string', 'date'],
            'eb_member_delegate_price' => ['nullable', 'numeric'],
            'eb_member_spouse_price' => ['nullable', 'numeric'],
            'eb_non_member_delegate_price' => ['nullable', 'numeric'],
            'eb_non_member_spouse_price' => ['nullable', 'numeric'],
            'member_delegate_price' => ['nullable', 'numeric'],
            'member_spouse_price' => ['nullable', 'numeric'],
            'non_member_delegate_price' => ['nullable', 'numeric'],
            'non_member_spouse_price' => ['nullable', 'numeric'],
            'duration' => ['nullable', 'array'],
            'duration.*' => ['nullable', 'string', 'date']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
