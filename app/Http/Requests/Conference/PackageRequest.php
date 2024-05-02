<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable'],
            'count' => ['nullable'],
            'description' => ['nullable'],
            'short_description' => ['nullable'],
            'delegate_count' => ['numeric','nullable'],
            'active' => ['boolean'],
            'is_infinity' => ['boolean'],
            'order_id' => ['numeric','nullable'],
            'price' => ['nullable'],
            'earlybird_price' => ['nullable'],
            'style' => ['nullable'],
            'features' => ['nullable', 'array'],
            'features.*.name' => ['nullable', 'string'],
            'features.*.active' => ['nullable', 'boolean'],
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
    public function customValidated()
    {
        $validatedData = parent::validated();

        if (isset($validatedData['features'])) {
            foreach ($validatedData['features'] as &$feature) {
                $feature = [
                    'name' => $feature['name'],
                    'active' => $feature['active'],
                ];
            }
        }

        return $validatedData;
    }
}

