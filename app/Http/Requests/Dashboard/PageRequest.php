<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
            'name' =>     ['nullable'],
            'slug' =>     ['nullable'],
            'des' =>      ['nullable'],
            'active' =>   ['nullable'],
            'order_id' => ['nullable'],
            'pageSections' => "nullable|array",
            'pageSections.*.page_section_id' => ['required'],
            'pageSections.*.order_id' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'pageSections.*.page_section_id.required' => 'The Section field is required.',
            'pageSections.*.order_id.required' => 'The order ID field is required.',
        ];
    }
}
