<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'name'       => ['nullable'],
            'des'       => ['nullable'],
            'order_id'       => ['nullable', 'integer'],
            "networks.*" => ['nullable'],
        ];
    }
}
