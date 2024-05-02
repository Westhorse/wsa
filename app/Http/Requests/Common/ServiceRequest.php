<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            $certificate = $this->route()->parameter('certificate');
            return [
                'name' => ['nullable' ,'string', 'max:255' , 'unique:certificates,name,'. $certificate],
                'active' => ['nullable' , 'boolean'],
                'order_id' => ['nullable','numeric','min:1'],
            ];
        }else{
            return [
              'name' => ['required' , 'string' , 'max:255' , 'unique:certificates,name'],
              'active' => ['required' , 'boolean'],
              'order_id' => ['required','numeric','min:1'],
            ];
        }
    }
}
