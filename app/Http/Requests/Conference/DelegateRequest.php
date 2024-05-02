<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class DelegateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'conference_id' =>$this->header('X-Conference-Id')
        ]);
    }

    public function rules(): array
    {

        $rules = [
            'name' => ['required'],
            'title' => ['required'],
            'job_title' => ['required'],
            'email' => ['required'],
            'tshirt_size_id' => ['required'],
            'phone' => ['required'],
            'cell' => ['nullable'],
            'phone_key_id' => ['required'],
            'cell_key_id' => ['nullable'],
            'extra_dietaries' => ['nullable'],
            'conference_id' => ['required'],

        ];
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['email'][] = 'unique:delegates,email,' . $this->route('delegate')->id;
        } else {
            $rules['email'][] = 'unique:delegates,email';
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
