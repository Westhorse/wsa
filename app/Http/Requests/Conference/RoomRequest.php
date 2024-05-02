<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'count' => ['nullable', 'integer'],
            'price' => ['nullable'],
            'public_show' => ['nullable', 'boolean'],
            'type' => ['nullable', 'in:single,double,other'],
            'active' => ['nullable', 'boolean'],
            'order_id' => ['nullable', 'integer'],
            'delegates_count' => ['nullable'],

            'public_types' => ['nullable', 'array'],
            'public_types.*.type' => ['nullable', 'string'],
            'public_types.*.price' => ['nullable', 'numeric'],


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

        if (isset($validatedData['public_types'])) {
            foreach ($validatedData['public_types'] as &$publicType) {
                $publicType = [
                    'type' => $publicType['type'],
                    'price' => $publicType['price'],
                ];
            }
        }

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
