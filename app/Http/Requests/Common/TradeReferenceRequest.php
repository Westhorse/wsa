<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class TradeReferenceRequest extends FormRequest
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
            'name' => ['required'],
            'email' => ['email'],
            'person' => ['required'],
            'job_title' => ['nullable'],
            'city' => ['nullable'],
            'country_id' => ['required'],
            'user_id' => ['nullable'],
        ];
    }
}
