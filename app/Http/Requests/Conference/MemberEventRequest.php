<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class MemberEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [

            'address_line1' => ['nullable'],
            'address_line2' => ['nullable'],
            'website' => ['nullable'],
            'slogan' => ['nullable'],
            'profile' => ['nullable'],
            'employees_num' => ['nullable'],
            'phone' => 'nullable|string',
            'phone_key_id' => 'nullable|integer',
            'fax' => 'nullable|string',
            'fax_key_id' => 'nullable|integer',
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
