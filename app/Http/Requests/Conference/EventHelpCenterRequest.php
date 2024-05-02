<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class EventHelpCenterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'sub_title' => ['nullable'],
            'post_title' => ['nullable'],
            'type' => ['nullable'],
            'short_description' => ['nullable'],
            'description' => ['nullable'],
            'slug' => ['nullable'],

            'active' => [ 'boolean'],
            'order_id' => ['nullable', 'numeric'],
            'parent_id' => ['nullable', 'exists:event_help_centers,id'],

            'list' => ['nullable', 'array'],
            'list.*.name' => ['nullable', 'string'],
            'list.*.active' => ['nullable', 'boolean'],
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

        if (isset($validatedData['list'])) {
            foreach ($validatedData['list'] as &$feature) {
                $feature = [
                    'name' => $feature['name'],
                    'active' => $feature['active'],
                ];
            }
        }

        return $validatedData;
    }
}
