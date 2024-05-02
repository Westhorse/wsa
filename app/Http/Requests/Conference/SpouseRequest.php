<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class SpouseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function prepareForValidation()
    {
        $this->merge([
            'conference_id' => $this->header('X-Conference-Id')
        ]);
    }
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'title' => ['required'],
            'delegate_id' => ['required'],
            'tshirt_size_id' => ['required'],
            'extra_dietaries' => ['nullable'],
            'conference_id' => ['required'],
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
