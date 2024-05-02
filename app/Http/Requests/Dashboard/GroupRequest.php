<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'active' => ['boolean'],
            'companies.*.id_company' => ['required'],
            'companies.*.type_company' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'companies.*.id_company' => 'The Member field is required.',
            'companies.*.type_company' => 'The Company Type field is required.',
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
