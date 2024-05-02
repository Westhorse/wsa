<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class NetworkRequest extends FormRequest
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
            $content = $this->route()->parameter('network');
            return [
                'name' => ['required'],
                'slug' => ['nullable'],
                'domain' => ['required'],
                'active' => ['boolean'],
                'collection' => ['boolean'],
                'order_id' => ['nullable'],

            ];
        } else {
            return [
                'name' => ['required'],
                'slug' => ['nullable'],
                'domain' => ['required'],
                'active' => ['boolean'],
                'collection' => ['boolean'],
                'order_id' => ['nullable'],
            ];
        }
    }
}
