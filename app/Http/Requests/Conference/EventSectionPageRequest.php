<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class EventSectionPageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'                => ['required'],
            'slug'                => ['required'],
            'post_title'          => ['nullable'],
            'title'               => ['nullable'],
            'sub_title'           => ['nullable'],
            'description'         => ['nullable'],
            'short_description'   => ['nullable'],
            'default'             => ['boolean'],
            'button_one_active'   => ['boolean'],
            'button_two_active'   => ['boolean'],
            'button_one.label'    => 'nullable|string',
            'button_one.url'      => 'nullable|string',
            'button_one.style'    => 'nullable|string',
            'button_one.target'   => 'nullable|string',
            'button_one.icon'     => 'nullable|string',
            'button_two.label'    => 'nullable|string',
            'button_two.url'      => 'nullable|string',
            'button_two.target'   => 'nullable|string',
            'button_two.icon'     => 'nullable|string',
            'button_two.style'    => 'nullable|string',
            'divider.active'      => 'nullable|boolean',
            'divider.position'       => 'nullable|string',
            'divider.style'       => 'nullable|string',
            'video_url'            => ['nullable'],
            'active'              => ['nullable'],
            'order_id'            => ['nullable'],
            'event_page_id'       => ['nullable', 'exists:event_pages,id'],
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
        if (isset($validatedData['button_one'])) {
            $buttonOneData = [
                'label'  => $validatedData['button_one']['label'],
                'url'  => $validatedData['button_one']['url'],
                'style'  => $validatedData['button_one']['style'],
                'target' => $validatedData['button_one']['target'],
                'icon'   => $validatedData['button_one']['icon'],
            ];
            $validatedData['button_one'] = $buttonOneData;
        }
        if (isset($validatedData['button_two'])) {
            $buttonTwoData = [
                'label'  => $validatedData['button_two']['label'],
                'url'  => $validatedData['button_two']['url'],
                'style'  => $validatedData['button_two']['style'],
                'target' => $validatedData['button_two']['target'],
                'icon'   => $validatedData['button_two']['icon'],
            ];
            $validatedData['button_two'] = $buttonTwoData;
        }
        if (isset($validatedData['divider'])) {
            $dividerData = [
                'active'  => $validatedData['divider']['active'],
                'style'  => $validatedData['divider']['style'],
                'position'  => $validatedData['divider']['position'],
            ];
            $validatedData['divider'] = $dividerData;
        }

        return $validatedData;
    }
}
