<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class EventContactUsRequest extends FormRequest
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
            'email' => ['required'],
            'wsa_id' => ['nullable'],
            'phone' => ['nullable'],
            'address' => ['nullable'],
            'subject' => ['nullable'],
            'message' => ['required'],
            'company' => ['nullable'],
            'country_id' => ['nullable'],
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
