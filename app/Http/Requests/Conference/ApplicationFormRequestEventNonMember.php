<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationFormRequestEventNonMember extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required'],
            'country_id' => ['required'], //
            'address_line1' => ['required'],
            'address_line2' => ['nullable'],
            'city' => ['required'],
            'state' => ['nullable'],
            'postal_code' => ['nullable'],

            'website' => ['required'],

            'profile' => ['nullable'],

            'business_est' => ['required'],

            'phone' => 'required|string',
            'phone_key_id' => 'required|integer',//
            'fax' => 'nullable|string',
            'fax_key_id' => 'nullable|integer',//


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
