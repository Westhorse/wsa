<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string'],
            'key' => ['required'],
            'code' => ['required'],
            'active' => ['boolean'],
            'order_id' => ['numeric', 'min:1'],

        ];
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['code'][] = 'unique:countries,code,' . $this->route('country')->id;
        } else {
            $rules['code'][] = 'unique:countries,code';
        }

        return $rules;
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
