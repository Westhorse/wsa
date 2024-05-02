<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'active' => ['boolean'],
            'featured' => ['boolean'],
            'order_id' => ['integer'],
            'short_des' => ['nullable'],
            'des' => ['nullable'],
            'publish_date' => ['nullable']
        ];
    }
}
