<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class ContactPersonRequest extends FormRequest
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
            'user_id' => ['nullable'],
            'title' => ['nullable'],
            'name' => ['nullable'],
            'email' => ['nullable'],
            'birth_date' => ['nullable'],
            'job_title' => ['nullable'],

            // Contact Numbers
            'phone' => 'nullable|string',
            'phone_key_id' => 'nullable|integer',
            'cell' => 'nullable|string',
            'cell_key_id' => 'nullable|integer',
        ];
    }
}
