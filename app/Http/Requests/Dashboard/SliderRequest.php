<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
            'name' => ['nullable'],
            'title' => ['nullable'],
            'bg_url' => ['nullable'],
            'link_type' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
            'order_id' => ['nullable'],
            'sub_title' => ['nullable'],
            'des' => ['nullable'],
            'button_one_active' => ['nullable', 'boolean'],
            'button_text_one' => ['nullable'],
            'button_style_one' => ['nullable'],
            'button_route_one' => ['nullable'],
            'button_icon_one' => ['nullable'],
            'button_link_type_one' => ['nullable', 'boolean'],
            'button_two_active' => ['nullable', 'boolean'],
            'button_text_two' => ['nullable'],
            'button_style_two' => ['nullable'],
            'button_route_two' => ['nullable'],
            'button_icon_two' => ['nullable'],
            'button_link_type_two' => ['nullable', 'boolean'],
        ];
    }
}
