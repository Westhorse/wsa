<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ContentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'       => ['nullable'],
            'type'       => ['nullable'],
            'detail'       => ['nullable'],
            'active'     => ['boolean'],
            'parent_id'  => ['nullable'],
            'benefit_id'      => ['nullable'],
            'order_id'   => ['integer']
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
