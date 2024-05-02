<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
    public function rules()
    {
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $menu = $this->route()->parameter('menu');
            return [
                'name' => ['nullable'],
                'active' => ['nullable'],
                'order_id' => ['nullable'],
            ];
        }else{
            return [
                'name' => ['nullable'],
                'active' => ['nullable'],
                'order_id' => ['nullable'],
            ];
        }
    }
}
