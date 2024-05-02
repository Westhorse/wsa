<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SettingWsaRequest extends FormRequest
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
            'label' => ['nullable'],
            'placeholder' => ['nullable'],
            'des' => ['nullable'],
            'name' => ['nullable'],
            'type' => ['nullable'],
            'data' => ['nullable'],
            'class' => ['nullable'],
            'rules' => ['nullable'],
            'parent_id' => ['nullable'],
            "networks.*" => ['nullable'],
        ];
    }
}
