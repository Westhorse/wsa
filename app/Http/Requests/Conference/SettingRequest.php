<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'label' => ['nullable'],
            'placeholder' => ['nullable'],
            'des' => ['nullable'],
            'name' => ['nullable'],
            'type' => ['nullable'],
            'data' => ['nullable'],
            'class' => ['nullable'],
            'rules' => ['nullable'],
            'value' => ['nullable'],
            'parent_id' => ['nullable'],
            'order_id' => ['nullable'],

            'button.label'    => 'nullable|string',
            'button.url'      => 'nullable|string',
            'button.style'    => 'nullable|string',
            'button.target'   => 'nullable|string',
            'button.icon'     => 'nullable|string',

            'items' => ['nullable', 'array'],
            'items.*.title' => ['nullable', 'string'],
            'items.*.active' => ['nullable', 'boolean'],
            'items.*.target' => ['nullable'],
            'items.*.url' => ['nullable'],
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
        if (isset($validatedData['button'])) {
            $buttonOneData = [
                'label'  => $validatedData['button']['label'],
                'url'  => $validatedData['button']['url'],
                'style'  => $validatedData['button']['style'],
                'target' => $validatedData['button']['target'],
                'icon'   => $validatedData['button']['icon'],
            ];
            $validatedData['button_one'] = $buttonOneData;
        }
        if (isset($validatedData['items'])) {
            foreach ($validatedData['items'] as &$items) {
                $items = [
                    'title' => $items['title'],
                    'active' => $items['active'],
                    'target' => $items['target'],
                    'url' => $items['url'],
                ];
            }
        }

        return $validatedData;
    }
}
