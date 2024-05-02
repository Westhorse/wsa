<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $content = $this->route()->parameter('testimonial');
            return [
                'name'       => ['nullable'],
                'title'       => ['nullable'],
                'company'       => ['nullable'],
                'country_id'       => ['nullable'],
                'des'       => ['nullable'],
                'short_des'       => ['nullable'],
                'active'       => ['nullable', 'boolean'],
                'show_home'       => ['nullable', 'boolean'],
                'order_id'       => ['nullable', 'integer'],
            ];
        }else{
            return [
                'name'       => ['nullable'],
                'title'       => ['nullable'],
                'company'       => ['nullable'],
                'country_id'       => ['nullable'],
                'des'       => ['nullable'],
                'short_des'       => ['nullable'],
                'active'       => ['nullable', 'boolean'],
                'show_home'       => ['nullable', 'boolean'],
                'order_id'       => ['nullable', 'integer'],
            ];
        }
    }
}
