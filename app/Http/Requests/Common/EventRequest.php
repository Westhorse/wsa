<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'title'       => ['nullable'],
            'des'       => ['nullable'],
            'short_des'       => ['nullable'],
            'url_text'       => ['nullable'],
            'url_path'       => ['nullable'],
            'type'       => ['nullable'],
            'start_date'       => ['nullable'],
            'end_date'       => ['nullable'],
            'delegates'       => ['nullable', 'integer'],
            'sessions'       => ['nullable', 'integer'],
            'companies'       => ['nullable', 'integer'],
            'countries'       => ['nullable', 'integer'],
            'featured'       => ['nullable', 'boolean'],
            'active'       => ['nullable', 'boolean'],
            'order_id'       => ['nullable', 'integer'],
            'country_id' => ['required' , 'integer', 'exists:countries,id'],
            'city'       => ['nullable'],
            'duration'       => ['nullable', 'integer'],
            'venue'       => ['nullable'],
            'network_id'       => ['nullable'],
        ];
    }
}
