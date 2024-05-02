<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class EventMenuRequest extends FormRequest
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
            'link'       => ['nullable'],
            'icon'       => ['nullable'],
            'order_id'       => ['nullable'],
            'active'       => ['boolean'],
            'show_icon'       => ['boolean'],
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

