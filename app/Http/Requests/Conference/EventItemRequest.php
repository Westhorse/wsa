<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class EventItemRequest extends FormRequest
{
   /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable'],
            'icon' => ['nullable'],
            'order_id' => ['nullable'],
            'active' => ['nullable'],
            'description' => ['nullable'],
            'url' => ['nullable'],
            'short_description' => ['nullable'],
            'button_active' => ['nullable'],
            'post_title' => ['nullable'],
            'sub_title' => ['nullable'],
            'event_section_page_id'  => ['nullable', 'exists:event_section_pages,id'],
            'button.label'    => 'nullable|string',
            'button.url'      => 'nullable|string',
            'button.style'    => 'nullable|string',
            'button.target'   => 'nullable|string',
            'button.icon'     => 'nullable|string',
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
            $validatedData['button'] = $buttonOneData;
        }
        return $validatedData;
    }
}
