<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class SponsorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'url' => ['nullable'],
            'name' => ['nullable'],
            'sponsorship_name' => ['nullable'],
            'description' => ['nullable'],
            'event_sponsor' => ['boolean'],
            'order_id' => ['nullable'],
            'active' => ['boolean'],
        ];
    }
}
